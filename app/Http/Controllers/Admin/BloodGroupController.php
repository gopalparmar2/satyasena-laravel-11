<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BloodGroup;

class BloodGroupController extends Controller
{
    public function __construct() {
        $this->middleware('permission:blood-group-list', ['only' => ['index']]);
        $this->middleware('permission:blood-group-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:blood-group-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:blood-group-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Blood Group List';

            if (Auth::user()->can('blood-group-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.bloodgroup.create'),
                    'title' => 'Add Blood Group',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Blood Group List'
            );

            return view('admin.bloodgroup.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $bloodGroup = BloodGroup::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $bloodGroup->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $bloodGroup->whereDate('created_at', $from_date);
                } else {
                    $bloodGroup->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($bloodGroup)
            ->addColumn('action', function ($bloodGroup) {
                $action = '';
                if (Auth::user()->can('blood-group-edit')) {
                    $action .= '<a href="'.route('admin.bloodgroup.edit', $bloodGroup->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('blood-group-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.bloodgroup.destroy').'" data-id="'.$bloodGroup->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($bloodGroup) {
                $status = '';

                if (Auth::user()->can('blood-group-edit')) {
                    $checkedAttr = $bloodGroup->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $bloodGroup->id . '" data-url="' . route('admin.bloodgroup.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($bloodGroup->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($bloodGroup) {
                return date('d/m/Y h:i A', strtotime($bloodGroup->created_at));
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
            $data['page_title'] = 'Add Blood Group';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('blood-group-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.bloodgroup.index'),
                    'title' => 'Blood Group List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Blood Group'
            );

            return view('admin.bloodgroup.create', $data);
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
                'name.required' => 'The blood group field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->blood_group_id != '') {
                    return redirect()->route('admin.bloodgroup.edit', $request->blood_group_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.bloodgroup.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->blood_group_id != '') {
                    $bloodGroup = BloodGroup::where('id', $request->blood_group_id)->first();
                    $action = 'updated';
                } else {
                    $bloodGroup = new BloodGroup();
                    $action = 'added';
                }

                $bloodGroup->name = $request->name;
                $bloodGroup->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($bloodGroup->save()) {
                    Session::flash('alert-message', "Blood Group ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.bloodgroup.index');
                } else {
                    Session::flash('alert-message', "Blood Group not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->blood_group_id != '') {
                        return redirect()->route('admin.bloodgroup.edit', $request->blood_group_id);
                    } else {
                        return redirect()->route('admin.bloodgroup.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('blood_group_id')) {
                return redirect()->route('admin.bloodgroup.edit', $request->blood_group_id);
            } else {
                return redirect()->route('admin.bloodgroup.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Blood Group';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('blood-group-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.bloodgroup.index'),
                    'title' => 'Blood Group List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Blood Group'
            );

            $bloodgroup = BloodGroup::find($id);

            if ($bloodgroup) {
                $data['bloodgroup'] = $bloodgroup;

                return view('admin.bloodgroup.create', $data);
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
                $bloodGroup = BloodGroup::find($request->id);
                $bloodGroup->status = $request->status;

                if ($bloodGroup->save()) {
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
                $bloodGroup = BloodGroup::where('id', $request->id)->first();

                if ($bloodGroup->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Blood group deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Blood group not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
