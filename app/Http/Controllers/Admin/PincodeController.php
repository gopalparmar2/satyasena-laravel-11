<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pincode;
use DataTables;
use Validator;
use Session;
use Auth;

class PincodeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:pincode-list', ['only' => ['index']]);
        $this->middleware('permission:pincode-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:pincode-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:pincode-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Pincode List';

            if (Auth::user()->can('pincode-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.pincode.create'),
                    'title' => 'Add Pincode',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Pincode List'
            );

            return view('admin.pincode.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $pincode = Pincode::query();

        if ($request->has('filter')) {
            if (isset($request->filter['state_id']) && $request->filter['state_id'] != '') {
                $state_id = $request->filter['state_id'];

                $pincode->whereHas('zila', function($q) use($state_id) {
                    $q->where('state_id', $state_id);
                });
            }

            if (isset($request->filter['zilla_id']) && $request->filter['zilla_id'] != '') {
                $zilla_id = $request->filter['zilla_id'];

                $pincode->where('zilla_id', $zilla_id);
            }

            if ($request->filter['fltStatus'] != '') {
                $pincode->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $pincode->whereDate('created_at', $from_date);
                } else {
                    $pincode->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($pincode)
            ->addColumn('action', function ($pincode) {
                $action = '';
                if (Auth::user()->can('pincode-edit')) {
                    $action .= '<a href="'.route('admin.pincode.edit', $pincode->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('pincode-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.pincode.destroy').'" data-id="'.$pincode->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->addColumn('state', function ($pincode) {
                return $pincode->state ? $pincode->state->name : '';
            })
            ->addColumn('district', function ($pincode) {
                return $pincode->district ? $pincode->district->name : '';
            })
            ->editColumn('status', function ($pincode) {
                $status = '';

                if (Auth::user()->can('pincode-edit')) {
                    $checkedAttr = $pincode->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $pincode->id . '" data-url="' . route('admin.pincode.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($pincode->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($pincode) {
                return date('d/m/Y h:i A', strtotime($pincode->created_at));
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
            $data['page_title'] = 'Add Pincode';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('pincode-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.pincode.index'),
                    'title' => 'Pincode List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Pincode'
            );

            return view('admin.pincode.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'state_id' => 'required',
                'district_id' => 'required',
                'office_name' => 'required',
                'taluka' => 'required',
                'pincode' => 'required',
            ];

            $messages = [
                'state_id.required' => 'The state field is required.',
                'district_id.required' => 'The district field is required.',
                'office_name.required' => 'The office name field is required.',
                'taluka.required' => 'The taluka field is required.',
                'pincode.required' => 'The pincode field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->pincode_id != '') {
                    return redirect()->route('admin.pincode.edit', $request->pincode_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.pincode.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->pincode_id != '') {
                    $pincode = Pincode::find($request->pincode_id);
                    $action = 'updated';
                } else {
                    $pincode = new Pincode();
                    $action = 'added';
                }

                $pincode->state_id = $request->state_id;
                $pincode->district_id = $request->district_id;
                $pincode->office_name = $request->office_name;
                $pincode->taluka = $request->taluka;
                $pincode->pincode = $request->pincode;
                $pincode->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($pincode->save()) {
                    Session::flash('alert-message', "Pincode ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.pincode.index');
                } else {
                    Session::flash('alert-message', "Pincode not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->pincode_id != '') {
                        return redirect()->route('admin.pincode.edit', $request->pincode_id);
                    } else {
                        return redirect()->route('admin.pincode.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('pincode_id')) {
                return redirect()->route('admin.pincode.edit', $request->pincode_id);
            } else {
                return redirect()->route('admin.pincode.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Pincode';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('pincode-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.pincode.index'),
                    'title' => 'Pincode List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Pincode'
            );

            $pincode = Pincode::find($id);


            if ($pincode) {
                $data['pincode'] = $pincode;

                return view('admin.pincode.create', $data);
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
                $pincode = Pincode::find($request->id);
                $pincode->status = $request->status;

                if ($pincode->save()) {
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
                $pincode = Pincode::where('id', $request->id)->first();

                if ($pincode->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Pincode deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Pincode not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
