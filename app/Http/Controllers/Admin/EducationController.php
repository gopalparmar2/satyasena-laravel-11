<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Education;
use DataTables;
use Validator;
use Session;
use Auth;

class EducationController extends Controller
{
    public function __construct() {
        $this->middleware('permission:education-list', ['only' => ['index']]);
        $this->middleware('permission:education-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:education-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:education-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Education List';

            if (Auth::user()->can('education-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.education.create'),
                    'title' => 'Add Education',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Education List'
            );

            return view('admin.education.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $education = Education::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $education->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $education->whereDate('created_at', $from_date);
                } else {
                    $education->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($education)
            ->addColumn('action', function ($education) {
                $action = '';
                if (Auth::user()->can('education-edit')) {
                    $action .= '<a href="'.route('admin.education.edit', $education->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('education-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.education.destroy').'" data-id="'.$education->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($education) {
                $status = '';

                if (Auth::user()->can('education-edit')) {
                    $checkedAttr = $education->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $education->id . '" data-url="' . route('admin.education.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($education->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($education) {
                return date('d/m/Y h:i A', strtotime($education->created_at));
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
            $data['page_title'] = 'Add Education';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('education-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.education.index'),
                    'title' => 'Education List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Education'
            );

            return view('admin.education.create', $data);
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
                if ($request->education_id != '') {
                    return redirect()->route('admin.education.edit', $request->education_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.education.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->education_id != '') {
                    $education = Education::where('id', $request->education_id)->first();
                    $action = 'updated';
                } else {
                    $education = new Education();
                    $action = 'added';
                }

                $education->name = $request->name;
                $education->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($education->save()) {
                    Session::flash('alert-message', "Education ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.education.index');
                } else {
                    Session::flash('alert-message', "Education not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->education_id != '') {
                        return redirect()->route('admin.education.edit', $request->education_id);
                    } else {
                        return redirect()->route('admin.education.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('education_id')) {
                return redirect()->route('admin.education.edit', $request->education_id);
            } else {
                return redirect()->route('admin.education.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Education';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('education-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.education.index'),
                    'title' => 'Education List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Education'
            );

            $education = Education::find($id);


            if ($education) {
                $data['education'] = $education;

                return view('admin.education.create', $data);
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
                $education = Education::find($request->id);
                $education->status = $request->status;

                if ($education->save()) {
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
                $education = Education::where('id', $request->id)->first();

                if ($education->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Education deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Education not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
