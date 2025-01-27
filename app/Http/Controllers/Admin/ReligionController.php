<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Religion;
use DataTables;
use Validator;
use Session;
use Auth;

class ReligionController extends Controller
{
    public function __construct() {
        $this->middleware('permission:religion-list', ['only' => ['index']]);
        $this->middleware('permission:religion-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:religion-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:religion-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Religion List';

            if (Auth::user()->can('religion-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.religion.create'),
                    'title' => 'Add Religion',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Religion List'
            );

            return view('admin.religion.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $religion = Religion::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $religion->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $religion->whereDate('created_at', $from_date);
                } else {
                    $religion->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($religion)
            ->addColumn('action', function ($religion) {
                $action = '';
                if (Auth::user()->can('religion-edit')) {
                    $action .= '<a href="'.route('admin.religion.edit', $religion->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('religion-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.religion.destroy').'" data-id="'.$religion->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($religion) {
                $status = '';

                if (Auth::user()->can('religion-edit')) {
                    $checkedAttr = $religion->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $religion->id . '" data-url="' . route('admin.religion.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($religion->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($religion) {
                return date('d/m/Y h:i A', strtotime($religion->created_at));
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
            $data['page_title'] = 'Add Religion';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('religion-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.religion.index'),
                    'title' => 'Religion List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Religion'
            );

            return view('admin.religion.create', $data);
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
                if ($request->religion_id != '') {
                    return redirect()->route('admin.religion.edit', $request->religion_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.religion.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->religion_id != '') {
                    $religion = Religion::where('id', $request->religion_id)->first();
                    $action = 'updated';
                } else {
                    $religion = new Religion();
                    $action = 'added';
                }

                $religion->name = $request->name;
                $religion->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($religion->save()) {
                    Session::flash('alert-message', "Religion ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.religion.index');
                } else {
                    Session::flash('alert-message', "Religion not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->religion_id != '') {
                        return redirect()->route('admin.religion.edit', $request->religion_id);
                    } else {
                        return redirect()->route('admin.religion.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('religion_id')) {
                return redirect()->route('admin.religion.edit', $request->religion_id);
            } else {
                return redirect()->route('admin.religion.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Religion';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('religion-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.religion.index'),
                    'title' => 'Religion List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Religion'
            );

            $religion = Religion::find($id);


            if ($religion) {
                $data['religion'] = $religion;

                return view('admin.religion.create', $data);
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
                $religion = Religion::find($request->id);
                $religion->status = $request->status;

                if ($religion->save()) {
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
                $religion = Religion::where('id', $request->id)->first();

                if ($religion->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Religion deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Religion not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
