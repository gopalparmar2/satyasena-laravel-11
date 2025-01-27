<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use DataTables;
use Validator;
use Session;
use Auth;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Category List';

            if (Auth::user()->can('category-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.category.create'),
                    'title' => 'Add Category',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Category List'
            );

            return view('admin.category.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $category = Category::query();

        if ($request->has('filter')) {
            if ($request->filter['fltStatus'] != '') {
                $category->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $category->whereDate('created_at', $from_date);
                } else {
                    $category->whereBetween('created_at', [$from_date, $to_date]);
                }
            }
        }

        return DataTables::eloquent($category)
            ->addColumn('action', function ($category) {
                $action = '';
                if (Auth::user()->can('category-edit')) {
                    $action .= '<a href="'.route('admin.category.edit', $category->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }

                if (Auth::user()->can('category-delete')) {
                    $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.category.destroy').'" data-id="'.$category->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }

                return $action;
            })
            ->editColumn('status', function ($category) {
                $status = '';

                if (Auth::user()->can('category-edit')) {
                    $checkedAttr = $category->status == 1 ? 'checked' : '';
                    $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $category->id . '" data-url="' . route('admin.category.change.status') . '" ' . $checkedAttr . '> </div>';
                } else {
                    $status = ($category->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($category) {
                return date('d/m/Y h:i A', strtotime($category->created_at));
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
            $data['page_title'] = 'Add Category';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('category-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.category.index'),
                    'title' => 'Category List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add Category'
            );

            return view('admin.category.create', $data);
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
                if ($request->category_id != '') {
                    return redirect()->route('admin.category.edit', $request->category_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.category.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->category_id != '') {
                    $category = Category::where('id', $request->category_id)->first();
                    $action = 'updated';
                } else {
                    $category = new Category();
                    $action = 'added';
                }

                $category->name = $request->name;
                $category->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;

                if ($category->save()) {
                    Session::flash('alert-message', "Category ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.category.index');
                } else {
                    Session::flash('alert-message', "Category not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->category_id != '') {
                        return redirect()->route('admin.category.edit', $request->category_id);
                    } else {
                        return redirect()->route('admin.category.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('category_id')) {
                return redirect()->route('admin.category.edit', $request->category_id);
            } else {
                return redirect()->route('admin.category.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit Category';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('category-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.category.index'),
                    'title' => 'Category List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit Category'
            );

            $category = Category::find($id);


            if ($category) {
                $data['category'] = $category;

                return view('admin.category.create', $data);
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
                $category = Category::find($request->id);
                $category->status = $request->status;

                if ($category->save()) {
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
                $category = Category::where('id', $request->id)->first();

                if ($category->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Category deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Category not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
