<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Relationship;
use DataTables;
use Validator;
use Session;
use Auth;

class RelationshipController extends Controller
{
    public function __construct() {
        $this->middleware('permission:relationship-list', ['only' => ['index']]);
        $this->middleware('permission:relationship-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:relationship-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:relationship-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Relationship List';

            if (Auth::user()->can('relationship-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.relationship.create'),
                    'title' => 'Add Relationship',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Relationship List'
            );

            return view('admin.relationship.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $relationship = Relationship::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $relationship->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $relationship->whereDate('created_at', $from_date);
                } else {
                    $relationship->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($relationship)
            ->addColumn('action', function ($relationship) {
                $action = '';
                if (Auth::user()->can('relationship-edit')) {
                    $action .= '<a href="'.route('admin.relationship.edit', $relationship->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('relationship-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.relationship.destroy').'" data-id="'.$relationship->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($relationship) {
                $status = '';

                if (Auth::user()->can('relationship-edit')) {
                    $checkedAttr = $relationship->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $relationship->id . '" data-url="' . route('admin.relationship.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($relationship->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($relationship) {
                return date('d/m/Y h:i A', strtotime($relationship->created_at));
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
            $data['page_title'] = 'Add Relationship';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('relationship-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.relationship.index'),
                    'title' => 'Relationship List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Relationship'
            );

            return view('admin.relationship.create', $data);
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
                if ($request->relationship_id != '') {
                    return redirect()->route('admin.relationship.edit', $request->relationship_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.relationship.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->relationship_id != '') {
                    $relationship = Relationship::where('id', $request->relationship_id)->first();
                    $action = 'updated';
                } else {
                    $relationship = new Relationship();
                    $action = 'added';
                }

                $relationship->name = $request->name;
                $relationship->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($relationship->save()) {
                    Session::flash('alert-message', "Relationship ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.relationship.index');
                } else {
                    Session::flash('alert-message', "Relationship not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->relationship_id != '') {
                        return redirect()->route('admin.relationship.edit', $request->relationship_id);
                    } else {
                        return redirect()->route('admin.relationship.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('relationship_id')) {
                return redirect()->route('admin.relationship.edit', $request->relationship_id);
            } else {
                return redirect()->route('admin.relationship.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Relationship';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('relationship-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.relationship.index'),
                    'title' => 'Relationship List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Relationship'
            );

            $relationship = Relationship::find($id);


            if ($relationship) {
                $data['relationship'] = $relationship;

                return view('admin.relationship.create', $data);
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
                $relationship = Relationship::find($request->id);
                $relationship->status = $request->status;

                if ($relationship->save()) {
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
                $relationship = Relationship::where('id', $request->id)->first();

                if ($relationship->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Relationship deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Relationship not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
