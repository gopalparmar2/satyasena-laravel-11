@extends('admin.layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('assets/libs/dropify/dist/css/dropify.min.css') }}">

    <style>
        #btn_add_more, #btn_edit_more, .btn_remove {
            margin-top: 26px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('admin.user.store') }}" name="addfrm" id="addfrm" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <input type="hidden" name="user_id" id="user_id" value="{{ isset($user) ? $user->id : '' }}">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3 controls">
                                    <label class="form-label @error('salutation') is-invalid @enderror">Select Salutation <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="salutation" id="salutation">
                                        <option value="">Select Salutation</option>
                                        <option value="mrs" {{ (old('salutation') == 'mrs' || (isset($user) && $user->salutation == 'mrs')) ? 'selected' : '' }}>Mrs</option>
                                        <option value="mr" {{ (old('salutation') == 'mr' || (isset($user) && $user->salutation == 'mr')) ? 'selected' : '' }}>Mr</option>
                                        <option value="ms" {{ (old('salutation') == 'ms' || (isset($user) && $user->salutation == 'ms')) ? 'selected' : '' }}>Ms</option>
                                    </select>

                                    @error('salutation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label @error('first_name') is-invalid @enderror">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" placeholder="Enter first name">

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label @error('last_name') is-invalid @enderror">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name', isset($user) ? $user->last_name : '') }}" placeholder="Enter last name">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3 controls">
                                    <label class="form-label @error('blood_group_id') is-invalid @enderror">Select Blood Group <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="blood_group_id" id="blood_group_id">
                                        <option value="">Select Gender</option>
                                        @foreach ($bloodGroups as $bloodGroup)
                                            <option value="{{ $bloodGroup->id }}" {{ (old('blood_group_id') == $bloodGroup->id || (isset($user) && $user->blood_group_id == $bloodGroup->id) ? 'selected' : '') }}>{{ $bloodGroup->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('email') is-invalid @enderror">Email Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Enter email address">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('mobile_number') is-invalid @enderror">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control numbers_only" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', isset($user) ? $user->mobile_number : '') }}" minlength="10" maxlength="10" placeholder="Enter mobile number">

                                    @error('mobile_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3 controls">
                                    <label class="form-label @error('dob') is-invalid @enderror">Select D.O.B <span class="text-danger">*</span></label>
                                    <div class="input-group" id="dobdatepicker">
                                        <input type="text" name="dob" id="dob" class="form-control" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" data-date-container='#dobdatepicker' data-provide="datepicker" data-date-autoclose="true" value="{{ (isset($user) && $user->dob != '') ? date('d/m/Y', strtotime($user->dob)) : '' }}">

                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>

                                    @error('salutation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input type="text" class="form-control" name="age" id="age" value="{{ old('age', isset($user) ? $user->age : '') }}" placeholder="Age" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label @error('gender') is-invalid @enderror">Select Gender <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="1" {{ (old('gender') == 1 || (isset($user) && $user->gender == 1) ? 'selected' : '') }}>Female</option>
                                        <option value="2" {{ (old('gender') == 2 || (isset($user) && $user->gender == 2) ? 'selected' : '') }}>Male</option>
                                        <option value="3" {{ (old('gender') == 3 || (isset($user) && $user->gender == 3) ? 'selected' : '') }}>Other</option>
                                    </select>

                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('address') is-invalid @enderror">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address', isset($user) ? $user->address : '') }}" placeholder="Enter address">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('pincode') is-invalid @enderror">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control numbers_only" name="pincode" id="pincodeDD" value="{{ old('pincode', isset($user) ? $user->pincode : '') }}" minlength="6" maxlength="6" placeholder="Enter pincode">

                                    @error('pincode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select State <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="state_id" id="state_id">
                                        @if (isset($user) && isset($user->state))
                                            <option value="{{ $user->state->id }}" selected>{{ $user->state->name }}</option>
                                        @endif
                                    </select>

                                    @error('state_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select District <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="district_id" id="district_id">
                                        @if (isset($user) && isset($user->district))
                                            <option value="{{ $user->district->id }}" selected>{{ $user->district->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Assembly Constituency (AC) <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="assembly_id" id="assembly_id">
                                        <option value="">Select Assembly Constituency (AC)</option>
                                        @if (isset($user) && isset($user->assemblyConstituency))
                                            <option value="{{ $user->assemblyConstituency->id }}" selected>{{ $user->assemblyConstituency->name }}</option>
                                        @endif
                                    </select>

                                    @error('assembly_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Village <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="village_id" id="village_id">
                                        <option value="">Select Village</option>
                                        @if (isset($user) && isset($user->village))
                                            <option value="{{ $user->village->id }}" selected>{{ $user->village->name }}</option>
                                        @endif
                                    </select>

                                    @error('village_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Religion <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="religion_id" id="religion_id">
                                        <option value="">Select Religion</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion->id }}" {{ (isset($user) && $user->religion_id != '' && $user->religion_id == $religion->id) ? 'selected' : '' }}>{{ $religion->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('religion_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Category <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="category_id" id="category_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (isset($user) && $user->category_id != '' && $user->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Education <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="education_id" id="education_id">
                                        <option value="">Select Education</option>
                                        @foreach ($educations as $education)
                                            <option value="{{ $education->id }}" {{ (isset($user) && $user->education_id != '' && $user->education_id == $education->id) ? 'selected' : '' }}>{{ $education->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('education_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Caste <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="caste_id" id="caste_id">
                                        <option value="">Select Caste</option>
                                        @foreach ($castes as $caste)
                                            <option value="{{ $caste->id }}" {{ (isset($user) && $user->caste_id != '' && $user->caste_id == $caste->id) ? 'selected' : '' }}>{{ $caste->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('caste_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Profession <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="profession_id" id="profession_id">
                                        <option value="">Select Profession</option>
                                        @foreach ($professions as $profession)
                                            <option value="{{ $profession->id }}" {{ (isset($user) && $user->profession_id != '' && $user->profession_id == $profession->id) ? 'selected' : '' }}>{{ $profession->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('profession_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('whatsapp_number') is-invalid @enderror">Whatsapp / Alternative number</label>
                                    <input type="text" class="form-control numbers_only" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', isset($user) ? $user->whatsapp_number : '') }}" maxlength="10" placeholder="Enter whatsapp / alternative number">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('relationship_name') is-invalid @enderror">Father / Spouse name</label>
                                    <input type="text" class="form-control" name="relationship_name" id="relationship_name" value="{{ old('relationship_name', isset($user) ? $user->relationship_name : '') }}" placeholder="Enter father / spouse name">
                                </div>
                            </div>
                        </div>

                        @php
                            $image  = (isset($user->image) && $user->image != '' && \File::exists(public_path('uploads/users/'.$user->image))) ? asset('uploads/users/'.$user->image) : '';

                            $imageExits = '';

                            if ($image) {
                                $imageExits = 'image-exist';
                            }
                        @endphp

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label @error('image') is-invalid @enderror">Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control dropify {{ $imageExits }}" name="image" id="image" data-default-file="{{ $image }}" data-allowed-file-extensions="gif png jpg jpeg" data-max-file-size="5M" data-show-errors="true" data-errors-position="outside" data-show-remove="false">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Referral (only a Primary Member can refer)</h5>

                        <div class="row">
                            <input type="hidden" name="referred_user_id" id="referred_user_id">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="referral_code" class="form-label">Mobile number / Referral code</label>
                                    <input type="text" class="form-control" name="referral_code" id="referral_code" value="{{ old('referral_code', (isset($user) && isset($user->reffered_user)) ? $user->reffered_user->referral_code : '') }}" placeholder="Mobile number / Referral code">

                                    <span id="errReferral" class="error errorMessage d-none">Please enter valid referral.</span>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Referred by (name)</label>
                                    <input type="text" class="form-control" name="referred_name" id="referred_name" value="{{ old('referred_name', (isset($user) && isset($user->reffered_user)) ? $user->reffered_user->name : '') }}" placeholder="Referred by (name)" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">If you are a BJP karyakarta? Please fill these information</h5>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="landline_number" class="form-label">Landline Number</label>
                                    <input type="text" class="form-control" name="landline_number" id="landline_number" value="{{ old('landline_number', isset($user) ? $user->landline_number : '') }}" minlength="10" maxlength="10" placeholder="Landline Number">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Zila</label>
                                    <select class="form-control select2" name="zila_id" id="zila_id">
                                        @if (isset($user) && isset($user->zila))
                                            <option value="{{ $user->zila->id }}" selected>{{ $user->zila->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Mandal</label>
                                    <select class="form-control select2" name="mandal_id" id="mandal_id">
                                        @if (isset($user) && isset($user->mandal))
                                            <option value="{{ $user->mandal->id }}" selected>{{ $user->mandal->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Ward</label>
                                    <select class="form-control select2" name="ward_id" id="ward_id">
                                        <option value="">Select Ward</option>
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ (isset($user) && $user->ward_id != '' && $user->ward_id == $i) ? 'selected' : '' }}>{{ 'Ward '.$i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select Booth</label>
                                    <select class="form-control select2" name="booth_id" id="booth_id">
                                        @if (isset($user) && isset($user->booth))
                                            <option value="{{ $user->booth->id }}" selected>{{ $user->booth->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Family Members</h5>

                        <div id="div_add_more">
                            @if (isset($user) && isset($user->familyMembers) && $user->familyMembers->count() > 0)
                                @foreach ($user->familyMembers as $key => $userFamilyMembers)
                                    <div class="row row_user_family_members">
                                        <div class="col-lg-2">
                                            <div class="mb-3">
                                                <label for="first_name_{{ $key }}" class="form-label">First Name</label>
                                                <input type="text" name="familyMembers[{{ $key }}][first_name]" class="form-control" id="first_name_{{ $key }}" data-key="{{ $key }}" value="{{ $userFamilyMembers->first_name }}" placeholder="Enter first name">

                                                @error('familyMembers[{{ $key }}][first_name]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="mb-3">
                                                <label for="last_name_{{ $key }}" class="form-label">Last Name</label>
                                                <input type="text" name="familyMembers[{{ $key }}][last_name]" class="form-control" id="last_name_{{ $key }}" data-key="{{ $key }}" value="{{ $userFamilyMembers->last_name }}" placeholder="Enter last name">

                                                @error('familyMembers[{{ $key }}][last_name]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3 controls">
                                                <label class="form-label">Select Relationship</label>
                                                <select class="form-control select2" name="familyMembers[{{ $key }}][relationship_id]" id="relationship_id_{{ $key }}" data-key="{{ $key }}">
                                                    <option value="">Select Salutation</option>
                                                    @foreach ($relationships as $relationship)
                                                        <option value="{{ $relationship->id }}" {{ $userFamilyMembers->relationship_id == $relationship->id ? 'selected' : '' }}>{{ $relationship->name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('familyMembers[{{ $key }}][relationship_id]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3 controls">
                                                <label class="form-label">Select D.O.B</label>
                                                <div class="input-group" id="div_dob_{{ $key }}">
                                                    <input type="text" name="familyMembers[{{ $key }}][dob]" id="dob_{{ $key }}" class="form-control multipleDate" placeholder="dd/mm/yyyy" data-key="{{ $key }}" data-date-format="dd/mm/yyyy" data-date-container='#div_dob_{{ $key }}' data-provide="datepicker" data-date-autoclose="true" value="{{ (isset($userFamilyMembers->dob) && $userFamilyMembers->dob != '') ? date('d/m/Y', strtotime($userFamilyMembers->dob)) : '' }}">

                                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                </div>

                                                @error('familyMembers[{{ $key }}][dob]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label class="form-label">Age</label>
                                                <input type="text" class="form-control" name="familyMembers[{{ $key }}][age]" id="age_{{ $key }}" value="{{ $userFamilyMembers->age }}" placeholder="Age" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control numbers_only" name="familyMembers[{{ $key }}][mobile_number]" id="mobile_number_{{ $key }}" value="{{ $userFamilyMembers->mobile_number }}" minlength="10" maxlength="10" placeholder="Enter mobile number">

                                                @error('familyMembers[{{ $key }}][mobile_number]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-1">
                                            @if ($key == 0)
                                                <button type="button" id="btn_add_more" class="btn btn-primary">
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger btn_remove">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row row_user_family_members">
                                    <div class="col-lg-2">
                                        <div class="mb-3">
                                            <label for="first_name_0" class="form-label">First Name</label>
                                            <input type="text" name="familyMembers[0][first_name]" class="form-control" id="first_name_0" data-key="0" value="{{ old('familyMembers[0][first_name]') }}" placeholder="Enter first name">

                                            @error('familyMembers[0][first_name]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <div class="mb-3">
                                            <label for="last_name_0" class="form-label">Last Name</label>
                                            <input type="text" name="familyMembers[0][last_name]" class="form-control" id="last_name_0" data-key="0" value="{{ old('familyMembers[0][last_name]') }}" placeholder="Enter last name">

                                            @error('familyMembers[0][last_name]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3 controls">
                                            <label class="form-label">Select Relationship</label>
                                            <select class="form-control select2" name="familyMembers[0][relationship_id]" id="relationship_id_0" data-key="0">
                                                <option value="">Select Salutation</option>
                                                @foreach ($relationships as $relationship)
                                                    <option value="{{ $relationship->id }}" {{ old('familyMembers[0][relationship_id]') == $relationship->id ? 'selected' : ''}}>{{ $relationship->name }}</option>
                                                @endforeach
                                            </select>

                                            @error('familyMembers[0][relationship_id]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3 controls">
                                            <label class="form-label">Select D.O.B</label>
                                            <div class="input-group" id="div_dob_0">
                                                <input type="text" name="familyMembers[0][dob]" id="dob_0" data-key="0" class="form-control multipleDate" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" data-date-container='#div_dob_0' data-provide="datepicker" data-date-autoclose="true" value="">

                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>

                                            @error('familyMembers[0][dob]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label">Age</label>
                                            <input type="text" class="form-control" name="familyMembers[0][age]" id="age_0" value="{{ old('familyMembers[0][age]') }}" placeholder="Age" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile Number</label>
                                            <input type="text" class="form-control numbers_only" name="familyMembers[0][mobile_number]" id="mobile_number_0" value="{{ old('familyMembers[0][mobile_number]') }}" minlength="10" maxlength="10" placeholder="Enter mobile number">

                                            @error('familyMembers[0][mobile_number]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-1">
                                        <button type="button" id="btn_add_more" class="btn btn-primary">
                                            <i class="bx bx-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-md button-responsive">Submit</button>
                    <a href="#" class="btn btn-secondary w-md button-responsive">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('assets/libs/dropify/dist/js/dropify.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.dropify').dropify();

            $.validator.addMethod("enhancedEmail", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z]{2,})(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value);
            }, "Please enter a valid email address.");

            $("#addfrm").validate({
                errorElement: "span",
                errorPlacement: function(label, element) {
                    label.addClass('errorMessage');

                    if (element.attr("type") == "radio" || element.hasClass('select2') || element.attr("name") == "description" || element.attr("name") == "password") {
                        $(element).parents('.controls').append(label)
                    } else if (element.hasClass('dropify')) {
                        label.insertAfter(element.closest('div'));
                    } else {
                        label.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass(errorClass).addClass(validClass)
                },
                ignore: [],
                rules: {
                    salutation: {
                        required: true
                    },
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    blood_group_id: {
                        required: true
                    },
                    email: {
                        required: true,
                        enhancedEmail: true,
                        remote: {
                            url: '{!! route("admin.user.exists") !!}',
                            type: "POST",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: $("#user_id").val()
                            },
                        }
                    },
                    mobile_number: {
                        required: true
                    },
                    dob: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    pincode: {
                        required: true
                    },
                    state_id: {
                        required: true
                    },
                    district_id: {
                        required: true
                    },
                    assembly_id: {
                        required: true
                    },
                    village_id: {
                        required: true
                    },
                    religion_id: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    caste_id: {
                        required: true
                    },
                    // education_id: {
                    //     required: true
                    // },
                    profession_id: {
                        required: true
                    },
                    image: {
                        required: ($('#user_id').val() == '') ? true : false,
                    }
                },
                messages: {
                    salutation: {
                        required: "The salutation field is required."
                    },
                    first_name: {
                        required: "The first name field is required."
                    },
                    last_name: {
                        required: "The last name field is required."
                    },
                    blood_group_id: {
                        required: "The blood group field is required."
                    },
                    email: {
                        required: "The email field is required.",
                        remote: "Email already taken."
                    },
                    mobile_number: {
                        required: "The mobile number field is required."
                    },
                    dob: {
                        required: "The dob field is required."
                    },
                    gender: {
                        required: "The gender field is required."
                    },
                    address: {
                        required: "The address field is required."
                    },
                    pincode: {
                        required: "The pincode field is required."
                    },
                    state_id: {
                        required: "The state field is required."
                    },
                    district_id: {
                        required: "The district field is required."
                    },
                    assembly_id: {
                        required: "The assembly constituency field is required."
                    },
                    village_id: {
                        required: "The village field is required."
                    },
                    religion_id: {
                        required: "The religion field is required."
                    },
                    category_id: {
                        required: "The category field is required."
                    },
                    caste_id: {
                        required: "The caste field is required."
                    },
                    // education_id: {
                    //     required: "The education field is required."
                    // },
                    profession_id: {
                        required: "The profession field is required."
                    },
                    image: {
                        required: "The image field is required."
                    }
                },
            })
        });

        $(document).on('change', '#dob', function () {
            var selectedDate = $(this).datepicker("getDate");

            if (selectedDate) {
                var today = new Date();
                var age = today.getFullYear() - selectedDate.getFullYear();
                var monthDifference = today.getMonth() - selectedDate.getMonth();
                var dayDifference = today.getDate() - selectedDate.getDate();

                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--;
                }

                $('#age').val(age);
            }
        });

        $('#state_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_states') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
            },
            placeholder: 'Select State',
        });

        $(document).on('change', '#state_id', function(e) {
            $('#district_id').attr('disabled', true);
            $("#district_id").val(null).trigger("change");
            $('#assembly_id').attr('disabled', true);
            $("#assembly_id").val(null).trigger("change");
            $('#zila_id').attr('disabled', true);
            $("#zila_id").val(null).trigger("change");

            if (this.value) {
                $('#district_id').attr('disabled', false);
                $('#assembly_id').attr('disabled', false);
                $('#zila_id').attr('disabled', false);
            }
        });

        $('#district_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_districts') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        stateId: $('#state_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select District',
        });

        $('#assembly_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_assembly') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        stateId: $('#state_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select Assembly Constituency (AC)',
        });

        $(document).on('change', '#assembly_id', function(e) {
            let optionSelected = $("option:selected", this);
            $('#booth_id').attr('disabled', true);
            $("#booth_id").val(null).trigger("change");
            $('#village_id').attr('disabled', true);
            $("#village_id").val(null).trigger("change");

            if (this.value) {
                $('#booth_id').attr('disabled', false);
                $('#village_id').attr('disabled', false);
            }
        });

        $('#village_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_village_dd') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        assemblyId: $('#assembly_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select Village',
        });

        $('#booth_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_booth_dd') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        assemblyId: $('#assembly_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select Booth',
        });

        $('#zila_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_zila_dd') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        stateId: $('#state_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select Zila',
        });

        $(document).on('change', '#zila_id', function(e) {
            let optionSelected = $("option:selected", this);
            $('#mandal_id').attr('disabled', true);
            $("#mandal_id").val(null).trigger("change");

            if (this.value) {
                $('#mandal_id').attr('disabled', false);
            }
        });

        $('#mandal_id').select2({
            allowClear: true,
            ajax: {
                url: "{!! route('ajax.get_mandals') !!}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        zilaId: $('#zila_id').find(":selected").val()
                    };
                },
            },
            placeholder: 'Select Mandal',
        });

        $(document).on('keyup', '#referral_code', function() {
            const input = $(this).val();

            if (input == '') {
                $('#errReferral').addClass('d-none');
                return
            }

            if (isNaN(input)) {
                $(this).val(input.toUpperCase().substring(0, 6));

                if (input.length === 6) {
                    checkUser(input, 'referral');
                } else {
                    $('#errReferral').removeClass('d-none');
                }

                return
            } else {
                $(this).val(input.substring(0, 10));

                if (input.length === 10) {
                    checkUser(input, 'mobile');
                } else {
                    $('#errReferral').removeClass('d-none');
                }

                return
            }

            const referred_user_id = $('#referred_user_id').val();
            const referred_name = $('#referred_name').val();

            if (referred_user_id != '') {
                $('#referred_user_id').val('');
            }
            if (referred_name != '') {
                $('#referred_name').val('');
            }
        });

        function checkUser(value, type) {
            $.ajax({
                url: "{!! route('ajax.check.referral.code') !!}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "value": value,
                    "type": type,
                },
                success: function(response) {
                    if (response.success) {
                        $('#referred_user_id').val(response.user_id);
                        $('#referred_name').val(response.referred_name);
                        $('#errReferral').addClass('d-none');
                    } else {
                        $('#errReferral').removeClass('d-none');
                    }
                }
            });
        }

        $(document).on('change', '.multipleDate', function() {
            let selectedDate = $(this).datepicker("getDate");
            let key = $(this).data('key');

            if (selectedDate) {
                let today = new Date();
                let age = today.getFullYear() - selectedDate.getFullYear();
                let monthDifference = today.getMonth() - selectedDate.getMonth();
                let dayDifference = today.getDate() - selectedDate.getDate();

                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--;
                }

                $('#age_'+key).val(age);
            }
        });

        var i = ($('#user_id').val() != '') ? $('.row_user_family_members').length : 0;

        $(document).on('click', '#btn_add_more', function() {
            ++i;

            $('#div_add_more').append('<div class="row row_user_family_members"> <div class="col-lg-2"> <div class="mb-3"> <label for="first_name_'+i+'" class="form-label">First Name</label> <input type="text" name="familyMembers['+i+'][first_name]" class="form-control" id="first_name_'+i+'" data-key="'+i+'" value="" placeholder="Enter first name"> @error("familyMembers['+i+'][first_name]") <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror </div> </div> <div class="col-lg-2"> <div class="mb-3"> <label for="last_name_'+i+'" class="form-label">Last Name</label> <input type="text" name="familyMembers['+i+'][last_name]" class="form-control" id="last_name_'+i+'" data-key="'+i+'" value="" placeholder="Enter last name"> @error("familyMembers['+i+'][last_name]") <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror </div> </div> <div class="col-md-2"> <div class="mb-3 controls"> <label class="form-label">Select Relationship</label> <select class="form-control select2" name="familyMembers['+i+'][relationship_id]"  id="relationship_id_'+i+'" data-key="'+i+'"> <option value="">Select Salutation</option> @foreach ($relationships as $relationship) <option value="{{ $relationship->id }}">{{ $relationship->name }}</option> @endforeach </select> @error("familyMembers['+i+'][relationship_id]") <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror </div> </div> <div class="col-md-2"> <div class="mb-3 controls"> <label class="form-label">Select D.O.B</label> <div class="input-group" id="div_dob_'+i+'"> <input type="text" name="familyMembers['+i+'][dob]" id="dob_'+i+'" data-key="'+i+'" class="form-control multipleDate" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" data-date-container="#div_dob_'+i+'" data-provide="datepicker" data-date-autoclose="true"> <span class="input-group-text"><i class="mdi mdi-calendar"></i></span> </div> @error("familyMembers['+i+'][dob]") <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror </div> </div> <div class="col-md-1"> <div class="mb-3"> <label class="form-label">Age</label> <input type="text" class="form-control" name="familyMembers['+i+'][age]" id="age_'+i+'" data-key="'+i+'" value="" placeholder="Age" readonly> </div> </div> <div class="col-md-2"> <div class="mb-3"> <label class="form-label">Mobile Number</label> <input type="text" class="form-control numbers_only" name="familyMembers['+i+'][mobile_number]" id="mobile_number_'+i+'" data-key="'+i+'" value="" minlength="10" maxlength="10" placeholder="Enter mobile number"> @error("familyMembers['+i+'][mobile_number]") <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror </div> </div> <div class="col-lg-1"> <button type="button" class="btn btn-danger btn_remove"> <i class="bx bx-x"></i> </button> </div> </div>');

            $('.select2').select2();
        });

        $(document).on('click', '.btn_remove', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endsection
