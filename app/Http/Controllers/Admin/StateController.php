<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\State;
use DataTables;
use Validator;
use Session;
use Auth;

class StateController extends Controller
{
    public function __construct() {
        $this->middleware('permission:state-list', ['only' => ['index']]);
        $this->middleware('permission:state-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:state-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:state-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'State List';

            if (Auth::user()->can('state-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.state.create'),
                    'title' => 'Add State',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'State List'
            );

            return view('admin.state.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $state = State::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $state->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $state->whereDate('created_at', $from_date);
                } else {
                    $state->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($state)
            ->addColumn('action', function ($state) {
                $action = '';
                if (Auth::user()->can('state-edit')) {
                    $action .= '<a href="'.route('admin.state.edit', $state->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('state-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.state.destroy').'" data-id="'.$state->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($state) {
                $status = '';

                if (Auth::user()->can('state-edit')) {
                    $checkedAttr = $state->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $state->id . '" data-url="' . route('admin.state.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($state->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($state) {
                return date('d/m/Y h:i A', strtotime($state->created_at));
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
            $data['page_title'] = 'Add State';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('state-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.state.index'),
                    'title' => 'State List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add State'
            );

            return view('admin.state.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'name' => 'required',
            ];

            $messages = [
                'name.required' => 'The name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->state_id != '') {
                    return redirect()->route('admin.state.edit', $request->state_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.state.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->state_id != '') {
                    $state = State::where('id', $request->state_id)->first();
                    $action = 'updated';
                } else {
                    $state = new State();
                    $action = 'added';
                }

                $state->name = $request->name;
                $state->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($state->save()) {
                    Session::flash('alert-message', "State ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.state.index');
                } else {
                    Session::flash('alert-message', "State not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->state_id != '') {
                        return redirect()->route('admin.state.edit', $request->state_id);
                    } else {
                        return redirect()->route('admin.state.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('state_id')) {
                return redirect()->route('admin.state.edit', $request->state_id);
            } else {
                return redirect()->route('admin.state.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit State';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('state-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.state.index'),
                    'title' => 'State List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit State'
            );

            $state = State::find($id);


            if ($state) {
                $data['state'] = $state;

                return view('admin.state.create', $data);
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
                $state = State::find($request->id);
                $state->status = $request->status;

                if ($state->save()) {
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
                $state = State::where('id', $request->id)->first();

                if ($state->delete()) {
                    $return['success'] = true;
                    $return['message'] = "State deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "State not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
