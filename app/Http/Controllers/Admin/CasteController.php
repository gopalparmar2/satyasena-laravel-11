<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Caste;

class CasteController extends Controller
{
    public function __construct() {
        $this->middleware('permission:caste-list', ['only' => ['index']]);
        $this->middleware('permission:caste-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:caste-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:caste-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Caste List';

            if (Auth::user()->can('caste-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.caste.create'),
                    'title' => 'Add Caste',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Caste List'
            );

            return view('admin.caste.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $caste = Caste::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $caste->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $caste->whereDate('created_at', $from_date);
                } else {
                    $caste->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($caste)
            ->addColumn('action', function ($caste) {
                $action = '';
                if (Auth::user()->can('caste-edit')) {
                    $action .= '<a href="'.route('admin.caste.edit', $caste->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('caste-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.caste.destroy').'" data-id="'.$caste->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($caste) {
                $status = '';

                if (Auth::user()->can('caste-edit')) {
                    $checkedAttr = $caste->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $caste->id . '" data-url="' . route('admin.caste.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($caste->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($caste) {
                return date('d/m/Y h:i A', strtotime($caste->created_at));
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
            $data['page_title'] = 'Add Caste';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('caste-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.caste.index'),
                    'title' => 'Caste List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Caste'
            );

            return view('admin.caste.create', $data);
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
                if ($request->caste_id != '') {
                    return redirect()->route('admin.caste.edit', $request->caste_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.caste.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->caste_id != '') {
                    $caste = Caste::where('id', $request->caste_id)->first();
                    $action = 'updated';
                } else {
                    $caste = new Caste();
                    $action = 'added';
                }

                $caste->name = $request->name;
                $caste->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($caste->save()) {
                    Session::flash('alert-message', "Caste ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.caste.index');
                } else {
                    Session::flash('alert-message', "Caste not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->caste_id != '') {
                        return redirect()->route('admin.caste.edit', $request->caste_id);
                    } else {
                        return redirect()->route('admin.caste.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('caste_id')) {
                return redirect()->route('admin.caste.edit', $request->caste_id);
            } else {
                return redirect()->route('admin.caste.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Caste';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('caste-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.caste.index'),
                    'title' => 'Caste List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Caste'
            );

            $caste = Caste::find($id);

            if ($caste) {
                $data['caste'] = $caste;

                return view('admin.caste.create', $data);
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
                $caste = Caste::find($request->id);
                $caste->status = $request->status;

                if ($caste->save()) {
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
                $caste = Caste::where('id', $request->id)->first();

                if ($caste->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Caste deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Caste not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
