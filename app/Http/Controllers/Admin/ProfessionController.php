<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Profession;
use DataTables;
use Validator;
use Session;
use Auth;

class ProfessionController extends Controller
{
    public function __construct() {
        $this->middleware('permission:profession-list', ['only' => ['index']]);
        $this->middleware('permission:profession-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:profession-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:profession-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Profession List';

            if (Auth::user()->can('profession-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.profession.create'),
                    'title' => 'Add Profession',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Profession List'
            );

            return view('admin.profession.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $profession = Profession::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $profession->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $profession->whereDate('created_at', $from_date);
                } else {
                    $profession->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($profession)
            ->addColumn('action', function ($profession) {
                $action = '';
                if (Auth::user()->can('profession-edit')) {
                    $action .= '<a href="'.route('admin.profession.edit', $profession->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('profession-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.profession.destroy').'" data-id="'.$profession->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($profession) {
                $status = '';

                if (Auth::user()->can('profession-edit')) {
                    $checkedAttr = $profession->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $profession->id . '" data-url="' . route('admin.profession.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($profession->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($profession) {
                return date('d/m/Y h:i A', strtotime($profession->created_at));
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
            $data['page_title'] = 'Add Profession';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('profession-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.profession.index'),
                    'title' => 'Profession List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Profession'
            );

            return view('admin.profession.create', $data);
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
                if ($request->profession_id != '') {
                    return redirect()->route('admin.profession.edit', $request->profession_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.profession.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->profession_id != '') {
                    $profession = Profession::where('id', $request->profession_id)->first();
                    $action = 'updated';
                } else {
                    $profession = new Profession();
                    $action = 'added';
                }

                $profession->name = $request->name;
                $profession->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($profession->save()) {
                    Session::flash('alert-message', "Profession ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.profession.index');
                } else {
                    Session::flash('alert-message', "Profession not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->profession_id != '') {
                        return redirect()->route('admin.profession.edit', $request->profession_id);
                    } else {
                        return redirect()->route('admin.profession.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('profession_id')) {
                return redirect()->route('admin.profession.edit', $request->profession_id);
            } else {
                return redirect()->route('admin.profession.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Profession';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('profession-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.profession.index'),
                    'title' => 'Profession List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Profession'
            );

            $profession = Profession::find($id);


            if ($profession) {
                $data['profession'] = $profession;

                return view('admin.profession.create', $data);
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
                $profession = Profession::find($request->id);
                $profession->status = $request->status;

                if ($profession->save()) {
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
                $profession = Profession::where('id', $request->id)->first();

                if ($profession->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Profession deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Profession not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
