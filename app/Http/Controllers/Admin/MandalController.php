<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mandal;
use DataTables;
use Validator;
use Session;
use Auth;

class MandalController extends Controller
{
    public function __construct() {
        $this->middleware('permission:mandal-list', ['only' => ['index']]);
        $this->middleware('permission:mandal-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:mandal-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:mandal-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Mandal List';

            if (Auth::user()->can('mandal-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.mandal.create'),
                    'title' => 'Add Mandal',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Mandal List'
            );

            return view('admin.mandal.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $mandal = Mandal::query();

        if ($request->has('filter')) {
            if (isset($request->filter['state_id']) && $request->filter['state_id'] != '') {
                $state_id = $request->filter['state_id'];

                $mandal->whereHas('zila', function($q) use($state_id) {
                    $q->where('state_id', $state_id);
                });
            }

            if (isset($request->filter['zilla_id']) && $request->filter['zilla_id'] != '') {
                $zilla_id = $request->filter['zilla_id'];

                $mandal->where('zilla_id', $zilla_id);
            }

            if ($request->filter['fltStatus'] != '') {
                $mandal->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $mandal->whereDate('created_at', $from_date);
                } else {
                    $mandal->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($mandal)
            ->addColumn('action', function ($mandal) {
                $action = '';
                if (Auth::user()->can('mandal-edit')) {
                    $action .= '<a href="'.route('admin.mandal.edit', $mandal->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('mandal-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.mandal.destroy').'" data-id="'.$mandal->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->addColumn('state_name', function ($mandal) {
                return (isset($mandal->zila) && isset($mandal->zila->state)) ? $mandal->zila->state->name : '';
            })
            ->addColumn('zila_name', function ($mandal) {
                return $mandal->zila ? $mandal->zila->name : '';
            })
            ->editColumn('status', function ($mandal) {
                $status = '';

                if (Auth::user()->can('mandal-edit')) {
                    $checkedAttr = $mandal->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $mandal->id . '" data-url="' . route('admin.mandal.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($mandal->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($mandal) {
                return date('d/m/Y h:i A', strtotime($mandal->created_at));
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
            $data['page_title'] = 'Add Mandal';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('mandal-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.mandal.index'),
                    'title' => 'Mandal List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Mandal'
            );

            return view('admin.mandal.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'state_id' => 'required',
                'zilla_id' => 'required',
                'name' => 'required',
            ];

            $messages = [
                'state_id.required' => 'The state field is required.',
                'zilla_id.required' => 'The zila field is required.',
                'name.required' => 'The name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->mandal_id != '') {
                    return redirect()->route('admin.mandal.edit', $request->mandal_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.mandal.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->mandal_id != '') {
                    $mandal = Mandal::find($request->mandal_id);
                    $action = 'updated';
                } else {
                    $mandal = new Mandal();
                    $action = 'added';
                }

                $mandal->name = $request->name;
                $mandal->zilla_id = $request->zilla_id;
                $mandal->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($mandal->save()) {
                    Session::flash('alert-message', "Mandal ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.mandal.index');
                } else {
                    Session::flash('alert-message', "Mandal not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->mandal_id != '') {
                        return redirect()->route('admin.mandal.edit', $request->mandal_id);
                    } else {
                        return redirect()->route('admin.mandal.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('mandal_id')) {
                return redirect()->route('admin.mandal.edit', $request->mandal_id);
            } else {
                return redirect()->route('admin.mandal.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Mandal';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('mandal-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.mandal.index'),
                    'title' => 'Mandal List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Mandal'
            );

            $mandal = Mandal::find($id);


            if ($mandal) {
                $data['mandal'] = $mandal;

                return view('admin.mandal.create', $data);
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
                $mandal = Mandal::find($request->id);
                $mandal->status = $request->status;

                if ($mandal->save()) {
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
                $mandal = Mandal::where('id', $request->id)->first();

                if ($mandal->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Mandal deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Mandal not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
