<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Booth;
use Yajra\DataTables\Facades\DataTables;

class BoothController extends Controller
{
    public function __construct() {
        $this->middleware('permission:booth-list', ['only' => ['index']]);
        $this->middleware('permission:booth-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:booth-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:booth-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Booth List';

            if (Auth::user()->can('booth-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.booth.create'),
                    'title' => 'Add Booth',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Booth List'
            );

            return view('admin.booth.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $booth = Booth::query();

        if ($request->has('filter')) {
            if ($request->filter['state_id'] != '') {
                $stateId = $request->filter['state_id'];

                $booth->whereHas('assembly', function ($q) use($stateId) {
                    $q->where('state_id', $stateId);
                });
            }

            if ($request->filter['assembly_id'] != '') {
                $booth->where('assembly_id', $request->filter['assembly_id']);
            }

            if ($request->filter['fltStatus'] != '') {
                $booth->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $booth->whereDate('created_at', $from_date);
                } else {
                    $booth->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($booth)
            ->addColumn('action', function ($booth) {
                $action = '';

                if (Auth::user()->can('booth-edit')) {
                    $action .= '<a href="'.route('admin.booth.edit', $booth->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('booth-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.booth.destroy').'" data-id="'.$booth->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('state_name', function($booth) {
                return (isset($booth->assembly) && isset($booth->assembly->state)) ? $booth->assembly->state->name : '';
            })
            ->editColumn('assembly_constituencies', function($booth) {
                return $booth->assembly ? $booth->assembly->name : '';
            })
            ->editColumn('status', function ($booth) {
                $status = '';

                if (Auth::user()->can('booth-edit')) {
                    $checkedAttr = $booth->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $booth->id . '" data-url="' . route('admin.booth.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($booth->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($booth) {
                return date('d/m/Y h:i A', strtotime($booth->created_at));
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
            $data['page_title'] = 'Add Booth';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('booth-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.booth.index'),
                    'title' => 'Booth List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Booth'
            );

            return view('admin.booth.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'state_id' => 'required',
                'assembly_id' => 'required',
                'name' => 'required',
            ];

            $messages = [
                'state_id.required' => 'The state field is required.',
                'assembly_id.required' => 'The assembly constituency field is required.',
                'name.required' => 'The name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->booth_id != '') {
                    return redirect()->route('admin.booth.edit', $request->booth_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.booth.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->booth_id != '') {
                    $booth = Booth::find($request->booth_id);
                    $action = 'updated';
                } else {
                    $booth = new Booth();
                    $action = 'added';
                }

                $booth->name = $request->name;
                $booth->assembly_id = $request->assembly_id;
                $booth->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($booth->save()) {
                    Session::flash('alert-message', "Booth ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.booth.index');
                } else {
                    Session::flash('alert-message', "Booth not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->booth_id != '') {
                        return redirect()->route('admin.booth.edit', $request->booth_id);
                    } else {
                        return redirect()->route('admin.booth.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('booth_id')) {
                return redirect()->route('admin.booth.edit', $request->booth_id);
            } else {
                return redirect()->route('admin.booth.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Booth';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('booth-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.booth.index'),
                    'title' => 'Booth List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Booth'
            );

            $booth = Booth::find($id);


            if ($booth) {
                $data['booth'] = $booth;

                return view('admin.booth.create', $data);
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
                $booth = Booth::find($request->id);
                $booth->status = $request->status;

                if ($booth->save()) {
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
                $booth = Booth::where('id', $request->id)->first();

                if ($booth->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Booth deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Booth not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
