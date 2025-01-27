<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Village;
use DataTables;
use Validator;
use Session;
use Auth;

class VillageController extends Controller
{
    public function __construct() {
        $this->middleware('permission:village-list', ['only' => ['index']]);
        $this->middleware('permission:village-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:village-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:village-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Village List';

            if (Auth::user()->can('village-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.village.create'),
                    'title' => 'Add Village',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Village List'
            );

            return view('admin.village.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $village = Village::query();

        if ($request->has('filter')) {
            if (isset($request->filter['state_id']) && $request->filter['state_id'] != '') {
                $state_id = $request->filter['state_id'];

                $village->whereHas('assembly', function($q) use($state_id) {
                    $q->where('state_id', $state_id);
                });
            }

            if (isset($request->filter['assembly_id']) && $request->filter['assembly_id'] != '') {
                $assembly_id = $request->filter['assembly_id'];

                $village->where('assembly_id', $assembly_id);
            }

            if (isset($request->filter['priority']) && $request->filter['priority'] != '') {
                $priority = $request->filter['priority'];

                $village->where('priority', $priority);
            }

            if ($request->filter['fltStatus'] != '') {
                $village->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $village->whereDate('created_at', $from_date);
                } else {
                    $village->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($village)
            ->addColumn('action', function ($village) {
                $action = '';
                if (Auth::user()->can('village-edit')) {
                    $action .= '<a href="'.route('admin.village.edit', $village->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('village-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.village.destroy').'" data-id="'.$village->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('priority', function ($village) {
                return $village->priority == 1 ? 'Yes' : 'No';
            })
            ->addColumn('assembly', function ($village) {
                return $village->assembly ? $village->assembly->name : '';
            })
            ->addColumn('state', function ($village) {
                return (isset($village->assembly) && isset($village->assembly->state)) ? $village->assembly->state->name : '';
            })
            ->editColumn('status', function ($village) {
                $status = '';

                if (Auth::user()->can('village-edit')) {
                    $checkedAttr = $village->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $village->id . '" data-url="' . route('admin.village.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($village->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($village) {
                return date('d/m/Y h:i A', strtotime($village->created_at));
            })
            ->orderColumn('id', function ($query, $order) {
                $query->orderBy('id', $order);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->orderColumn('status', function ($query, $order) {
                $query->orderBy('status', $order);
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create() {
        try {
            $data['page_title'] = 'Add Village';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('village-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.village.index'),
                    'title' => 'Village List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Village'
            );

            return view('admin.village.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'name' => 'required',
                'state_id' => 'required',
                'assembly_id' => 'required',
                'name' => 'required',
            ];

            $messages = [
                'name.required' => 'The name field is required.',
                'state_id.required' => 'The state field is required.',
                'assembly_id.required' => 'The assembly constituency field is required.',
                'name.required' => 'The name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->village_id != '') {
                    return redirect()->route('admin.village.edit', $request->village_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.village.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->village_id != '') {
                    $village = Village::where('id', $request->village_id)->first();
                    $action = 'updated';
                } else {
                    $village = new Village();
                    $action = 'added';
                }

                $village->name = $request->name;
                $village->assembly_id = $request->assembly_id;
                $village->priority = ($request->has('priority') && $request->priority == 'on') ? 1 : 0;
                $village->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($village->save()) {
                    Session::flash('alert-message', "Village ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.village.index');
                } else {
                    Session::flash('alert-message', "Village not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->village_id != '') {
                        return redirect()->route('admin.village.edit', $request->village_id);
                    } else {
                        return redirect()->route('admin.village.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('village_id')) {
                return redirect()->route('admin.village.edit', $request->village_id);
            } else {
                return redirect()->route('admin.village.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Village';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('village-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.village.index'),
                    'title' => 'Village List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Village'
            );

            $village = Village::find($id);


            if ($village) {
                $data['village'] = $village;

                return view('admin.village.create', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function change_status(Request $request) {
        if ($request->ajax()) {
            try {
                $village = Village::find($request->id);
                $village->status = $request->status;

                if ($village->save()) {
                    $response['success'] = true;
                    $response['message'] = "Status has been changed successfully.";
                } else {
                    $response['success'] = false;
                    $response['message'] = "Status has been changed unsuccessfully.";
                }

                return response()->json($response);
            } catch (\Exception $e) {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    public function destroy(Request $request) {
        try {
            if ($request->ajax()) {
                $village = Village::where('id', $request->id)->first();

                if ($village->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Village deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Village not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
