<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;
use App\Models\Relationship;
use App\Models\BloodGroup;
use App\Models\Profession;
use App\Models\Religion;
use App\Models\Category;
use App\Models\Caste;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Session;
use File;
use Auth;

class HomeController extends Controller
{
    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Home';

            $user = Auth::user();

            if (!$user->is_details_filled) {
                return redirect()->route('front.show.user.details.form');
            }

            $data['user'] = $user;

            return view('front.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function showVerifyOtpForm() {
        try {
            $data = [];
            $data['page_title'] = 'Verify Otp';

            $isOtpVerificationOn = \Config::get('app.isOtpVerificationOn');

            if ($isOtpVerificationOn) {
                return view('front.auth.verify_otp', $data);
            }

            return redirect()->route('front.store.user.details');
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function verifyOtp(Request $request) {
        try {
            dd($request->all());

            if ($user->otp === $inputOtp && Carbon::now()->lt($user->otp_expires_at)) {
                // OTP is valid
            } else {
                // OTP is invalid or expired
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function showUserDetailsForm() {
        try {
            $data = [];
            $data['page_title'] = 'Provide Details';

            return view('front.user.details', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function updateUserImage(Request $request) {
        try {
            if ($request->ajax()) {
                $rules = [
                    'image' => 'required|mimes:jpg,jpeg,png|max:10240'
                ];

                $messages = [
                    'image.required' => 'The image field is required.',
                    'image.mimes' => 'Please insert image only.',
                    'image.max' => 'Image should be less than 10 MB.',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                if ($image = $request->file('image')) {
                    $userFolderPath = public_path('uploads/users/');
                    if (!File::isDirectory($userFolderPath)) {
                        File::makeDirectory($userFolderPath, 0777, true, true);
                    }

                    $user = Auth::user();

                    if ($user->image != '') {
                        $userImage = public_path('uploads/users/'.$user->image);

                        if (File::exists($userImage)) {
                            unlink($userImage);
                        }
                    }

                    $userImage = date('YmdHis') . "." . $image->extension();
                    Image::useImageDriver(ImageDriver::Gd)->loadFile($image->path())->optimize()->save($userFolderPath.$userImage);
                    $user->image = $userImage;
                    $user->save();

                    $response['success'] = true;
                    $response['image'] = asset('uploads/users/'.$user->image);
                    $response['message'] = 'Image updated successfully.';

                    return response()->json($response);
                }

                $response['success'] = false;
                $response['message'] = 'Something went wrong.';

                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function storeUserDetails(Request $request) {
        try {
            $user = Auth::user();

            $user->salutation = $request->salutation;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->email = $request->email;
            $user->dob = date('Y-m-d', strtotime($request->dob));
            $user->age = str_replace(' Yrs', '', $request->age);
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state_id = $request->state;
            $user->district_id = $request->district;
            $user->assembly_id = $request->assembly_constituency;
            $user->referred_user_id = $request->referred_user_id;
            $user->is_details_filled = 1;

            if ($user->membership_number == null) {
                $user->membership_number = generateMembershipNumber();
            }

            if ($user->referral_code == null) {
                $user->referral_code = generateMyReferralCode() ;
            }

            if ($user->save()) {
                return redirect()->route('front.index');
            }

            Session::flash('alert-message', 'Something went wrong');
            Session::flash('alert-class','error');

            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function showUpdateDetailsForm() {
        try {
            $data = [];
            $data['page_title'] = 'Update Details';

            $user = Auth::user();

            if (!$user->is_details_filled) {
                return redirect()->route('front.show.user.details.form');
            }

            $data['user'] = $user;
            $data['religions'] = Religion::whereStatus(1)->get();
            $data['categories'] = Category::whereStatus(1)->get();
            $data['castes'] = Caste::whereStatus(1)->get();
            $data['professions'] = Profession::whereStatus(1)->get();
            $data['relationships'] = Relationship::whereStatus(1)->get();
            $data['bloodgroups'] = BloodGroup::whereStatus(1)->get();

            return view('front.user.update_details', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function updateDetails(Request $request) {
        try {
            $user = Auth::user();

            $user->salutation = $request->salutation;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->dob = date('Y-m-d', strtotime($request->dob));
            $user->age = str_replace(' Yrs', '', $request->age);
            $user->gender = $request->gender;
            $user->email = $request->email;
            $user->blood_group_id = $request->blood_group_id;
            $user->color_id = $request->color_id;
            $user->religion_id = $request->religion_id;
            $user->category_id = $request->category_id;
            $user->caste_id = $request->caste_id;
            $user->profession_id = $request->profession_id;
            $user->whatsapp_number = $request->whatsapp_number;
            $user->relationship_name = $request->relationship_name;

            if ($user->save()) {
                $response['success'] = true;
                $response['message'] = 'Personal details updated successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Something went wrong.';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function storeFamilyMemberDetails(Request $request) {
        try {
            $user = Auth::user();

            if ($request->familyMemberId != null) {
                $familyMember = User::find($request->familyMemberId);
                $resMessage = 'Family member updated sucessfully.';
            } else {
                $familyMember = new User();
                $resMessage = 'Family member added sucessfully.';
            }

            $familyMember->parent_id = $user->id;
            $familyMember->relationship_id = $request->relationship_id;
            $familyMember->first_name = $request->first_name;
            $familyMember->last_name = $request->last_name;
            $familyMember->name = $request->first_name.' '.$request->last_name;
            $familyMember->mobile_number = $request->mobile_number;
            $familyMember->dob = date('Y-m-d', strtotime($request->dob));
            $familyMember->age = str_replace(' Yrs', '', $request->age);

            if ($familyMember->save()) {
                $memberDetailsHtml = '';

                if ($user->familyMembers->count() > 0) {
                    $familyMembers = $user->familyMembers;

                    $memberDetailsHtml = \View::make('front.user.family_member_list', compact('familyMembers'))->render();
                }

                $response['success'] = true;
                $response['message'] = $resMessage;
                $response['memberDetailsHtml'] = $memberDetailsHtml;
                $response['membersCount'] = $user->familyMembers->count();
            } else {
                $response['success'] = false;
                $response['message'] = 'Something went wrong.';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function deleteFamilyMember(Request $request) {
        try {
            $familyMember = User::find($request->id);

            if (!$familyMember) {
                $response['success'] = false;
                $response['message'] = 'Family member not found.';
            } else {
                if ($familyMember->delete()) {
                    $response['success'] = true;
                    $response['message'] = 'Family member removed successfully.';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Something went wrong.';
                }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getFamilyMember(Request $request) {
        try {
            $familyMember = User::find($request->id);

            if (!$familyMember) {
                $response['success'] = false;
                $response['message'] = 'Family member not found.';
            } else {
                $response['success'] = true;
                $response['message'] = 'Family member details fetched successfully.';

                $response['first_name'] = $familyMember->first_name;
                $response['last_name'] = $familyMember->last_name;
                $response['relationship_id'] = $familyMember->relationship_id;
                $response['relationship_name'] = $familyMember->relationship ? $familyMember->relationship->name : '';
                $response['dob'] = date('m/d/Y', strtotime($familyMember->dob));
                $response['age'] = $familyMember->age;
                $response['mobile_number'] = $familyMember->mobile_number;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function storeContactDetails(Request $request) {
        try {
            $user = Auth::user();

            $user->address = $request->address;
            $user->pincode = $request->pincode;
            $user->state_id = $request->state;
            $user->district_id = $request->district;
            $user->assembly_id = $request->assembly_constituency;
            $user->zila_id = $request->zila_id;
            $user->village_id = $request->village_id;
            $user->mandal_id = $request->mandal_id;
            $user->booth_id = $request->booth_id;
            $user->landline_number = $request->landline_number;
            $user->ward_id = $request->ward_id;

            if ($user->save()) {
                $response['success'] = true;
                $response['message'] = 'Contact details updated successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Something went wrong.';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function referral() {
        try {
            $data = [];
            $data['page_title'] = 'Refferal';

            return view('front.user.referral', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    public function thankYou() {
        try {
            $data = [];
            $data['page_title'] = 'Thank You';

            return view('front.user.thank_you', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }
}
