<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;
use App\Models\Caste;
use App\Models\Relationship;
use App\Models\Profession;
use App\Models\Education;
use App\Models\BloodGroup;
use App\Models\Religion;
use App\Models\Category;
use App\Models\Roles;
use App\Models\User;
use App\Models\Village;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'store', 'change_status']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'User List';

            if (Auth::user()->can('user-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.user.create'),
                    'title' => 'Add User',
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'User List'
            );

            $data['roles'] = Roles::whereStatus(1)->get();
            $data['castes'] = Caste::whereStatus(1)->orderBy('name', 'asc')->get();
            $data['villages'] = Village::whereStatus(1)->orderBy('name', 'asc')->get();

            return view('admin.user.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request) {
        $user = User::where('id', '!=', Auth::id());

        if ($request->has('filter')) {
            if (isset($request->filter['name']) && $request->filter['name'] != '') {
                $name = $request->filter['name'];
                $user->where('name', 'like', '%'.$name.'%');
            }

            if (isset($request->filter['village_id']) && $request->filter['village_id'] != '') {
                $village_id = $request->filter['village_id'];
                $user->where('village_id', $village_id);
            }

            if (isset($request->filter['caste_id']) && $request->filter['caste_id'] != '') {
                $caste_id = $request->filter['caste_id'];
                $user->where('caste_id', $caste_id);
            }

            if (isset($request->filter['last_name']) && $request->filter['last_name'] != '') {
                $last_name = $request->filter['last_name'];
                $user->where('last_name', 'like', '%'.$last_name.'%');
            }

            if (isset($request->filter['business_name']) && $request->filter['business_name'] != '') {
                $business_name = $request->filter['business_name'];
                $user->whereHas('profession', function ($query) use ($business_name) {
                    $query->where('name', 'like', '%'.$business_name.'%');
                });
            }

            if (isset($request->filter['mobile_number']) && $request->filter['mobile_number'] != '') {
                $mobile_number = $request->filter['mobile_number'];

                $user->where('mobile_number', $mobile_number);
            }

            if ($request->filter['fltStatus'] != '') {
                $user->where('status', $request->filter['fltStatus']);
            }

            if ($request->filter['date'] != '') {
                $date = explode(' - ', $request->filter['date']);
                $from_date = date('Y-m-d', strtotime($date[0]));
                $to_date = date('Y-m-d', strtotime($date[1]));

                if ($from_date == $to_date) {
                    $user->whereDate('created_at', $from_date);
                } else {
                    $user->whereBetween('created_at', [$from_date, $to_date]);
                }
            }

            if ($request->filter['is_mobile_number'] != '') {
                if ($request->filter['is_mobile_number'] == 1) {
                    $user->whereNotNull('mobile_number');
                } else {
                    $user->whereNull('mobile_number');
                }
            }
        }

        return DataTables::eloquent($user)
            ->addColumn('action', function ($user) {
                $action = '';
                if ($user->parent_id == null) {
                    if (Auth::user()->can('user-list')) {
                        $action .= '<a href="'.route('admin.user.view', $user->id).'" class="btn btn-outline-secondary btn-sm" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                    }

                    if (Auth::user()->can('user-edit')) {
                        $action .= '<a href="'.route('admin.user.edit', $user->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                    }

                    if (Auth::user()->can('user-delete')) {
                        $action .= '<a class="btn btn-outline-secondary btn-sm btnDelete" data-url="'.route('admin.user.destroy').'" data-id="'.$user->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }

                return $action;
            })
            ->addColumn('full_name', function ($user) {
                $fullName = $user->name;

                if ($user->salutation != '') {
                    $fullName = ucfirst($user->salutation).'. '.$user->name;
                }

                return $fullName;
            })
            ->editColumn('dob', function ($user) {
                return $user->dob ? date('d/m/Y', strtotime($user->dob)) : '';
            })
            ->editColumn('age', function ($user) {
                return $user->age ? $user->age.' Yrs' : '';
            })
            ->editColumn('gender', function ($user) {
                $gender = '';

                if ($user->gender == 1) {
                    $gender = 'Female';
                } elseif ($user->gender == 2) {
                    $gender = 'Male';
                } elseif ($user->gender == 3) {
                    $gender = 'Other';
                }

                return $gender;
            })
            ->editColumn('image', function ($user) {
                $image = '';

                if ($user->image != '' && File::exists(public_path('uploads/users/' . $user->image))) {
                    $image = '<img src="' . asset('uploads/users/' . $user->image) . '" id="users" class="rounded-circle header-profile-user" alt="Avatar">';
                }

                return $image;
            })
            ->editColumn('status', function ($user) {
                $status = '';

                if ($user->parent_id == null) {
                    if (Auth::user()->can('user-edit')) {
                        $checkedAttr = $user->status == 1 ? 'checked' : '';
                        $status = '<div class="form-check form-switch form-switch-md mb-3" dir="ltr"> <input class="form-check-input js-switch" type="checkbox" data-id="' . $user->id . '" data-url="' . route('admin.user.change.status') . '" ' . $checkedAttr . '> </div>';
                    } else {
                        $status = ($user->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                    }
                } else {
                    $status = ($user->status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">InActive</span>';
                }

                return $status;
            })
            ->addColumn('caste', function ($user) {
                return $user->caste ? $user->caste->name : '';
            })
            ->editColumn('business_name', function ($user) {
                return $user->profession ? $user->profession->name : '';
            })
            ->editColumn('created_at', function($user) {
                return date('d/m/Y h:i A', strtotime($user->created_at));
            })
            ->orderColumn('id', function ($query, $order) {
                $query->orderBy('id', $order);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->orderColumn('email', function ($query, $order) {
                $query->orderBy('email', $order);
            })
            ->orderColumn('status', function ($query, $order) {
                $query->orderBy('status', $order);
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action', 'image', 'status'])
            ->make(true);
    }

    public function create() {
        try {
            $data['page_title'] = 'Add User';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('user-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.user.index'),
                    'title' => 'User List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Add User'
            );

            $data['religions'] = Religion::whereStatus(1)->select('id', 'name')->get();
            $data['categories'] = Category::whereStatus(1)->select('id', 'name')->get();
            $data['professions'] = Profession::whereStatus(1)->select('id', 'name')->get();
            $data['relationships'] = Relationship::whereStatus(1)->select('id', 'name')->get();
            $data['castes'] = Caste::whereStatus(1)->select('id', 'name')->get();
            $data['bloodGroups'] = BloodGroup::whereStatus(1)->select('id', 'name')->get();

            return view('admin.user.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request) {
        try {
            $rules = [
                'salutation' => 'required',
                'first_name'    => 'required',
                'last_name'    => 'required',
                'blood_group_id'    => 'required',
                'dob'    => 'required',
                'gender'    => 'required',
                'address'    => 'required',
                'pincode'    => 'required',
                'state_id'    => 'required',
                'district_id'    => 'required',
                'assembly_id'    => 'required',
                'village_id'    => 'required',
                'religion_id'    => 'required',
                'category_id'    => 'required',
                'caste_id'    => 'required',
                // 'education_id'    => 'required',
                'profession_id'    => 'required',
            ];

            if ($request->has('user_id') && $request->user_id != '') {
                if ($request->has('email') && $request->email != '') {
                    $rules['email'] = 'required|unique:users,email,'.$request->user_id;
                }

                $rules['mobile_number'] = 'required|unique:users,mobile_number,'.$request->user_id;
            } else {
                if ($request->has('email') && $request->email != '') {
                    $rules['email'] = 'required|unique:users,email';
                }

                $rules['mobile_number'] = 'required|unique:users,mobile_number';
            }

            if ($request->has('image')) {
                $rules['image'] = 'required|mimes:jpg,jpeg,png|max:4096';
            }

            $messages = [
                'salutation.required'      => 'The salutation field is required.',
                'first_name.required'      => 'The first name field is required.',
                'last_name.required'       => 'The last name field is required.',
                'blood_group_id.required'  => 'The blood group field is required.',
                'email.required'           => 'The email field is required.',
                'email.unique'             => 'The email already exists.',
                'mobile_number.required'   => 'The mobile number field is required.',
                'mobile_number.unique'     => 'The mobile number already exists.',
                'dob.required'             => 'The dob field is required.',
                'gender.required'          => 'The gender field is required.',
                'address.required'         => 'The address field is required.',
                'pincode.required'         => 'The pincode field is required.',
                'state_id.required'        => 'The state field is required.',
                'district_id.required'     => 'The district field is required.',
                'assembly_id.required'     => 'The assembly field is required.',
                'village_id.required'      => 'The village field is required.',
                'religion_id.required'     => 'The religion field is required.',
                'category_id.required'     => 'The category field is required.',
                'caste_id.required'        => 'The caste field is required.',
                'education_id.required'    => 'The education field is required.',
                'profession_id.required'   => 'The profession field is required.',
                'image.required'           => 'The image field is required.',
                'image.mimes'              => 'Please insert image only.',
                'image.max'                => 'Image should be less than 4 MB.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('user_id') && $request->user_id != '') {
                    return redirect()->route('admin.user.edit', $request->user_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->route('admin.user.create')
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->has('user_id') && $request->user_id != '') {
                    $user = User::where('id', $request->user_id)->first();
                    $action = 'updated';
                } else {
                    $user = new User();
                    $action = 'added';

                    $user->is_details_filled = 1;
                    $user->referral_code = generateMyReferralCode() ;
                    $user->membership_number = generateMembershipNumber();
                }

                $user->salutation = $request->salutation;
                $user->name = $request->first_name.' '.$request->last_name;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->blood_group_id = $request->blood_group_id;
                $user->email = $request->email;
                $user->mobile_number = $request->mobile_number;

                if ($request->dob != '') {
                    $explededDob = explode('/', $request->dob);
                    $userDob = $explededDob[2].'-'.$explededDob[1].'-'.$explededDob[0];
                    $user->dob = $userDob;
                }

                $user->age = $request->age;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->pincode = $request->pincode;
                $user->state_id = $request->state_id;
                $user->district_id = $request->district_id;
                $user->assembly_id = $request->assembly_id;
                $user->village_id = $request->village_id;
                $user->religion_id = $request->religion_id;
                $user->category_id = $request->category_id;
                $user->caste_id = $request->caste_id;
                // $user->education_id = $request->education_id;
                $user->profession_id = $request->profession_id;
                $user->whatsapp_number = $request->whatsapp_number;
                $user->relationship_name = $request->relationship_name;
                $user->referred_user_id = $request->referred_user_id;
                $user->landline_number = $request->landline_number;
                $user->zila_id = $request->zila_id;
                $user->mandal_id = $request->mandal_id;
                $user->ward_id = $request->ward_id;
                $user->booth_id = $request->booth_id;

                if ($image = $request->file('image')) {
                    $userFolderPath = public_path('uploads/users/');
                    if (!File::isDirectory($userFolderPath)) {
                        File::makeDirectory($userFolderPath, 0777, true, true);
                    }

                    if ($user->image != '') {
                        $userImage = public_path('uploads/users/'.$user->image);

                        if (File::exists($userImage)) {
                            unlink($userImage);
                        }
                    }

                    $userImage = date('YmdHis') . "." . $image->extension();
                    Image::useImageDriver(ImageDriver::Gd)->loadFile($image->path())->optimize()->save($userFolderPath.$userImage);
                    $user->image = $userImage;
                }

                if ($user->save()) {
                    if (count($request->familyMembers) > 0 && $request->familyMembers[0]['first_name'] != '') {
                        if ($request->has('user_id') && $request->user_id != '') {
                            $parentUser = User::where('id', $request->user_id)->first();

                            if ($parentUser->familyMembers->count() > 0) {
                                User::where('parent_id', $request->user_id)->delete();
                            }
                        }

                        foreach ($request->familyMembers as $member) {
                            $familyMember = new User();
                            $familyMember->parent_id = $user->id;
                            $familyMember->relationship_id = $member['relationship_id'];
                            $familyMember->name = $member['first_name'].' '.$member['last_name'];
                            $familyMember->first_name = $member['first_name'];
                            $familyMember->last_name = $member['last_name'];
                            $familyMember->mobile_number = $member['mobile_number'];
                            $familyMember->age = $member['age'];

                            if ($member['dob'] != '') {
                                $explededMemberDob = explode('/', $member['dob']);
                                $memberDob = $explededMemberDob[2].'-'.$explededMemberDob[1].'-'.$explededMemberDob[0];
                                $familyMember->dob = $memberDob;
                            }

                            $familyMember->save();
                        }
                    }

                    Session::flash('alert-message', "User ".$action." successfully.");
                    Session::flash('alert-class','success');

                    return redirect()->route('admin.user.index');
                } else {
                    Session::flash('alert-message', "User not ".$action.".");
                    Session::flash('alert-class','error');

                    if ($request->has('user_id') && $request->user_id != '') {
                        return redirect()->route('admin.user.edit', $request->user_id);
                    } else {
                        return redirect()->route('admin.user.create');
                    }
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            if ($request->has('user_id') && $request->user_id != '') {
                return redirect()->route('admin.user.edit', $request->user_id);
            } else {
                return redirect()->route('admin.user.create');
            }
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Edit User';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('user-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.user.index'),
                    'title' => 'User List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'Edit User'
            );

            $user = User::find($id);

            if ($user) {
                $data['user'] = $user;
                $data['religions'] = Religion::whereStatus(1)->select('id', 'name')->get();
                $data['categories'] = Category::whereStatus(1)->select('id', 'name')->get();
                $data['professions'] = Profession::whereStatus(1)->select('id', 'name')->get();
                $data['relationships'] = Relationship::whereStatus(1)->select('id', 'name')->get();
                $data['castes'] = Caste::whereStatus(1)->select('id', 'name')->get();
                $data['bloodGroups'] = BloodGroup::whereStatus(1)->select('id', 'name')->get();

                return view('admin.user.create', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function view($id) {
        try {
            $data['page_title'] = 'View User';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            if (Auth::user()->can('user-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.user.index'),
                    'title' => 'User List'
                );
            }

            $data['breadcrumb'][] = array(
                'title' => 'View User'
            );

            $user = User::find($id);


            if ($user) {
                $data['user'] = $user;

                return view('admin.user.view', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function exists(Request $request){
        if (isset($request->id) && $request->id != '') {
            $result = User::where('id', '!=', $request->id)->where('email', $request->email)->count();
        } else {
            $result = User::where('email',$request->email)->count();
        }

        if ($result > 0) {
            return response()->json(false);
        } else {
            return response()->json(true);
        }
    }

    public function change_status(Request $request) {
        if ($request->ajax()) {
            try {
                $role = User::find($request->id);
                $role->status = $request->status;

                if ($role->save()) {
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
                $user = User::where('id', $request->id)->first();

                if ($user) {
                    if ($user->image != '') {
                        $userImage = public_path('uploads/users/'.$user->image);

                        if (File::exists($userImage)) {
                            unlink($userImage);
                        }
                    }

                    $user->delete();

                    $return['success'] = true;
                    $return['message'] = "User deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "User not found.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function export(Request $request) {
        try {
            $fileName = 'users_'.date('YmdHis').'.xlsx';

            return Excel::download(new UsersExport($request), $fileName);
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
