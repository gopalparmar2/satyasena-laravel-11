<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Zilla;
use DataTables;
use Validator;
use Session;
use Auth;

class ZilaController extends Controller
{
    public function __construct() {
        $this->middleware('permission:zila-list', ['only' => ['index']]);
        $this->middleware('permission:zila-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:zila-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:zila-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Zila List';

            if (Auth::user()->can('zila-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.zila.create'),
                    'title' => 'Add Zila',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Zila List'
            );

            return view('admin.zila.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $zila = Zilla::query();

        if ($request->has('filter')) {
            if (isset($request->filter['state_id']) && $request->filter['state_id'] != '') {
                $state_id = $request->filter['state_id'];

                $zila->where('state_id', $state_id);
            }

            if ($request->filter['fltStatus'] != '') {
                $zila->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $zila->whereDate('created_at', $from_date);
                } else {
                    $zila->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($zila)
            ->addColumn('action', function ($zila) {
                $action = '';
                if (Auth::user()->can('zila-edit')) {
                    $action .= '<a href="'.route('admin.zila.edit', $zila->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('zila-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.zila.destroy').'" data-id="'.$zila->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->addColumn('state_name', function ($zila) {
                return $zila->state ? $zila->state->name : '';
            })
            ->editColumn('status', function ($zila) {
                $status = '';

                if (Auth::user()->can('zila-edit')) {
                    $checkedAttr = $zila->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $zila->id . '" data-url="' . route('admin.zila.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($zila->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($zila) {
                return date('d/m/Y h:i A', strtotime($zila->created_at));
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
            $data['page_title'] = 'Add Zila';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('zila-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.zila.index'),
                    'title' => 'Zila List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Zila'
            );

            return view('admin.zila.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'name' => 'required',
                'state_id' => 'required',
            ];

            $messages = [
                'name.required' => 'The name field is required.',
                'state_id.required' => 'The state field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->zila_id != '') {
                    return redirect()->route('admin.zila.edit', $request->zila_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.zila.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->zila_id != '') {
                    $zila = Zilla::where('id', $request->zila_id)->first();
                    $action = 'updated';
                } else {
                    $zila = new Zilla();
                    $action = 'added';
                }

                $zila->name = $request->name;
                $zila->state_id = $request->state_id;
                $zila->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($zila->save()) {
                    Session::flash('alert-message', "Zila ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.zila.index');
                } else {
                    Session::flash('alert-message', "Zila not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->zila_id != '') {
                        return redirect()->route('admin.zila.edit', $request->zila_id);
                    } else {
                        return redirect()->route('admin.zila.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('zila_id')) {
                return redirect()->route('admin.zila.edit', $request->zila_id);
            } else {
                return redirect()->route('admin.zila.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Zila';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('zila-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.zila.index'),
                    'title' => 'Zila List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Zila'
            );

            $zila = Zilla::find($id);


            if ($zila) {
                $data['zila'] = $zila;

                return view('admin.zila.create', $data);
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
                $zila = Zilla::find($request->id);
                $zila->status = $request->status;

                if ($zila->save()) {
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
                $zila = Zilla::where('id', $request->id)->first();

                if ($zila->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Zila deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Zila not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
