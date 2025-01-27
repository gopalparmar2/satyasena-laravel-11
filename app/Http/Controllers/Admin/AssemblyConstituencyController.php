<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AssemblyConstituency;
use DataTables;
use Validator;
use Session;
use Auth;

class AssemblyConstituencyController extends Controller
{
    public function __construct() {
        $this->middleware('permission:assembly-constituency-list', ['only' => ['index']]);
        $this->middleware('permission:assembly-constituency-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:assembly-constituency-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:assembly-constituency-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Assembly Constituency List';

            if (Auth::user()->can('assembly-constituency-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.assemblyConstituency.create'),
                    'title' => 'Add Assembly Constituency',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Assembly Constituency List'
            );

            return view('admin.assemblyConstituency.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $assemblyConstituency = AssemblyConstituency::query();

        if ($request->has('filter')) {
            if (isset($request->filter['state_id']) && $request->filter['state_id'] != '') {
                $state_id = $request->filter['state_id'];

                $assemblyConstituency->where('state_id', $state_id);
            }

            if ($request->filter['fltStatus'] != '') {
                $assemblyConstituency->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $assemblyConstituency->whereDate('created_at', $from_date);
                } else {
                    $assemblyConstituency->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($assemblyConstituency)
            ->addColumn('action', function ($assemblyConstituency) {
                $action = '';
                if (Auth::user()->can('assembly-constituency-edit')) {
                    $action .= '<a href="'.route('admin.assemblyConstituency.edit', $assemblyConstituency->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('assembly-constituency-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.assemblyConstituency.destroy').'" data-id="'.$assemblyConstituency->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->addColumn('state_name', function ($assemblyConstituency) {
                return $assemblyConstituency->state ? $assemblyConstituency->state->name : '';
            })
            ->editColumn('status', function ($assemblyConstituency) {
                $status = '';

                if (Auth::user()->can('assembly-constituency-edit')) {
                    $checkedAttr = $assemblyConstituency->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $assemblyConstituency->id . '" data-url="' . route('admin.assemblyConstituency.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($assemblyConstituency->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($assemblyConstituency) {
                return date('d/m/Y h:i A', strtotime($assemblyConstituency->created_at));
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
            $data['page_title'] = 'Add Assembly Constituency';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('assembly-constituency-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.assemblyConstituency.index'),
                    'title' => 'Assembly Constituency List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Assembly Constituency'
            );

            return view('admin.assemblyConstituency.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'name' => 'required',
                'state_id' => 'required',
                'number' => 'required|integer',
            ];

            $messages = [
                'name.required' => 'The name field is required.',
                'state_id.required' => 'The state field is required.',
                'number.required' => 'The number field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->assembly_constituency_id != '') {
                    return redirect()->route('admin.assemblyConstituency.edit', $request->assembly_constituency_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.assemblyConstituency.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->assembly_constituency_id != '') {
                    $assemblyConstituency = AssemblyConstituency::where('id', $request->assembly_constituency_id)->first();
                    $action = 'updated';
                } else {
                    $assemblyConstituency = new AssemblyConstituency();
                    $action = 'added';
                }

                $assemblyConstituency->name = $request->name;
                $assemblyConstituency->state_id = $request->state_id;
                $assemblyConstituency->number = $request->number;
                $assemblyConstituency->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($assemblyConstituency->save()) {
                    Session::flash('alert-message', "Assembly Constituency ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.assemblyConstituency.index');
                } else {
                    Session::flash('alert-message', "Assembly Constituency not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->assembly_constituency_id != '') {
                        return redirect()->route('admin.assemblyConstituency.edit', $request->assembly_constituency_id);
                    } else {
                        return redirect()->route('admin.assemblyConstituency.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('assembly_constituency_id')) {
                return redirect()->route('admin.assemblyConstituency.edit', $request->assembly_constituency_id);
            } else {
                return redirect()->route('admin.assemblyConstituency.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Assembly Constituency';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('assembly-constituency-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.assemblyConstituency.index'),
                    'title' => 'Assembly Constituency List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Assembly Constituency'
            );

            $assemblyConstituency = AssemblyConstituency::find($id);


            if ($assemblyConstituency) {
                $data['assemblyConstituency'] = $assemblyConstituency;

                return view('admin.assemblyConstituency.create', $data);
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
                $assemblyConstituency = AssemblyConstituency::find($request->id);
                $assemblyConstituency->status = $request->status;

                if ($assemblyConstituency->save()) {
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
                $assemblyConstituency = AssemblyConstituency::where('id', $request->id)->first();

                if ($assemblyConstituency->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Assembly Constituency deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Assembly Constituency not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
