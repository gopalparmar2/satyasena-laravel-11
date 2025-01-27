@extends('front.layouts.app')

@section('styles')
@parent
    <style>
        .css-yf8vq0-MuiSelect-nativeInput {
            opacity:1 !important;
            border: 0px !important;
        }
    </style>
@endsection

@section('content')
    <div class="bg-white main-container">
        @include('front.layouts.header')

        <div class="margin-second d-flex align-items-center pd-heading" style="padding: 0px 25px;">
            <div style="width: 18px;"></div>

            <p class="m-0 fill-details-text">Provide Details</p>

            <div></div>
        </div>

        <div class="position-relative bg-white"></div>

        <div class="pp-main-container">
            <div class="pp-sub-contt">
                <div class="profile-pic-container">
                    @if (isset(auth()->user()->image) && auth()->user()->image != '' && \File::exists(public_path('uploads/users/' . auth()->user()->image)))
                        <div class="MuiAvatar-root MuiAvatar-circular css-11fq0lf-MuiAvatar-root">
                            <img src="{{ asset('uploads/users/' . auth()->user()->image) }}" alt="Profile Image"
                                class="MuiAvatar-img css-1pqm26d-MuiAvatar-img">
                        </div>
                    @else
                        <div class="css-mkijz0-MuiAvatar-root">
                            <svg class="css-10mi8st-MuiSvgIcon-root-MuiAvatar-fallback" focusable="false" aria-hidden="true"
                                viewBox="0 0 24 24" data-testid="PersonIcon">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <button class="pp-upload-btn">
                    <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.345 12.2439L11.7967 3.79222L10.6183 2.61388L2.16667 11.0656V12.2439H3.345ZM4.03583 13.9106H0.5V10.3747L10.0292 0.845551C10.1854 0.689325 10.3974 0.601562 10.6183 0.601562C10.8393 0.601562 11.0512 0.689325 11.2075 0.845551L13.565 3.20305C13.7212 3.35932 13.809 3.57125 13.809 3.79222C13.809 4.01319 13.7212 4.22511 13.565 4.38138L4.03583 13.9106ZM0.5 15.5772H15.5V17.2439H0.5V15.5772Z"
                            fill="#F15600"></path>
                    </svg>
                </button>
            </div>

            @if (isset(auth()->user()->image) && auth()->user()->image != '' && \File::exists(public_path('uploads/users/' . auth()->user()->image)))
                <div class="profile-photo-label" id="profilePhotoLabel">Profile Photo</div>
            @else
                <div class="profile-photo-label" id="profilePhotoLabel">Upload Photo</div>
            @endif
        </div>

        <form action="{{ route('front.store.user.details') }}" method="post">
            @csrf

            <div class="form-container">
                <div class="divider-div"></div>

                <div>
                    <div class="input-container">
                        <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                            <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary Mui-disabled MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                <input type="text" name="mobile_number" id="mobile_number" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Mobile number" value="{{ auth()->user()->mobile_number }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider-div"></div>

                <div class="primary-dropdown-container">
                    <div class="MuiFormControl-root css-1869usk-MuiFormControl-root">
                        <div class="MuiInputBase-root MuiInputBase-input-box MuiInput-root MuiInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-n70jm4-MuiInputBase-root-MuiInput-root-MuiSelect-root dropdown">
                            <div tabindex="0" role="combobox" aria-controls=":ra:" aria-expanded="false" aria-haspopup="listbox" aria-labelledby="demo-simple-select-standard-label demo-simple-select-standard" id="divSalutationName" class="MuiSelect-select MuiSelect-standard MuiInputBase-input MuiInput-input css-1rxz5jq-MuiSelect-select-MuiInputBase-input-MuiInput-input">
                            </div>

                            <input name="salutation" placeholder="Title *" id="salutation" aria-hidden="true" tabindex="-1" class="MuiSelect-nativeInput css-yf8vq0-MuiSelect-nativeInput">

                            <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium MuiSelect-icon MuiSelect-iconStandard css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                                <path d="M7 10l5 5 5-5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="text" name="first_name" id="first_name" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="First Name *" value="{{ old('first_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="text" name="last_name" id="last_name" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Last Name *" value="{{ old('last_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gender-container">
                    <div class="dob-field">
                        <div class="dob-field-one">
                            <div style="font-size: 16px; color: rgb(113, 111, 134);">D.O.B *</div>
                            <div
                                style="display: flex; flex-direction: row; align-items: center; background-color: white; border-radius: 8px; justify-content: end; width: 100%;">
                                <div
                                    class="MuiFormControl-root MuiTextField-root css-r0ibox-MuiFormControl-root-MuiTextField-root">
                                    <div
                                        class="MuiInputBase-root MuiOutlinedInput-root MuiInputBase-colorPrimary MuiInputBase-formControl MuiInputBase-adornedEnd Mui-readOnly MuiInputBase-readOnly css-o9k5xi-MuiInputBase-root-MuiOutlinedInput-root">
                                        <input type="text" name="dob" id="dob" class="!outline-0 focus:border-0 MuiInputBase-input MuiOutlinedInput-input MuiInputBase-inputAdornedEnd Mui-readOnly MuiInputBase-readOnly css-nxo287-MuiInputBase-input-MuiOutlinedInput-input" placeholder="MM/DD/YYYY" autocomplete="off">

                                        <fieldset aria-hidden="true" class="MuiOutlinedInput-notchedOutline css-1d3z3hw-MuiOutlinedInput-notchedOutline">
                                            <legend class="css-ihdtdm">
                                                <span class="notranslate">&ZeroWidthSpace;</span>
                                            </legend>
                                        </fieldset>
                                    </div>
                                </div>

                                <div style="position: absolute; z-index: 0; margin-right: 10px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 2V5" stroke="#F15600" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16 2V5" stroke="#F15600" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M16 3.5C19.33 3.68 21 4.95 21 9.65V15.83C21 19.95 20 22.01 15 22.01H9C4 22.01 3 19.95 3 15.83V9.65C3 4.95 4.67 3.69 8 3.5H16Z" stroke="#F15600" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M20.75 17.5996H3.25" stroke="#F15600" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M12 8.25C10.77 8.25 9.73 8.92 9.73 10.22C9.73 10.84 10.02 11.31 10.46 11.61C9.85 11.97 9.5 12.55 9.5 13.23C9.5 14.47 10.45 15.24 12 15.24C13.54 15.24 14.5 14.47 14.5 13.23C14.5 12.55 14.15 11.96 13.53 11.61C13.98 11.3 14.26 10.84 14.26 10.22C14.26 8.92 13.23 8.25 12 8.25ZM12 11.09C11.48 11.09 11.1 10.78 11.1 10.29C11.1 9.79 11.48 9.5 12 9.5C12.52 9.5 12.9 9.79 12.9 10.29C12.9 10.78 12.52 11.09 12 11.09ZM12 14C11.34 14 10.86 13.67 10.86 13.07C10.86 12.47 11.34 12.15 12 12.15C12.66 12.15 13.14 12.48 13.14 13.07C13.14 13.67 12.66 14 12 14Z" fill="#F15600"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="dob-field-two">
                            <div style="font-size: 16px; color: transparent;">Age</div>
                            <div class="MuiFormControl-root MuiTextField-root age-input css-1rd8emk-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiOutlinedInput-root MuiInputBase-colorPrimary MuiInputBase-formControl Mui-readOnly MuiInputBase-readOnly css-9ddj71-MuiInputBase-root-MuiOutlinedInput-root">
                                    <input type="text" name="age" id="age" placeholder="Age" class="MuiInputBase-input MuiOutlinedInput-input Mui-readOnly MuiInputBase-readOnly css-1t8l2tu-MuiInputBase-input-MuiOutlinedInput-input" readonly>
                                    <fieldset aria-hidden="true" class="MuiOutlinedInput-notchedOutline css-1d3z3hw-MuiOutlinedInput-notchedOutline">
                                        <legend class="css-ihdtdm">
                                            <span class="notranslate">&ZeroWidthSpace;</span>
                                        </legend>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gender-label" style="margin-top: 10px;">Gender *</div>
                    <input type="hidden" name="gender" id="gender" value="">
                    <div class="chip-container">
                        <div class="chip" data-value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Female</div>
                        <div class="chip" data-value="2" {{ old('gender') == 2 ? 'selected' : '' }}>Male</div>
                        <div class="chip" data-value="3" {{ old('gender') == 3 ? 'selected' : '' }}>Other</div>
                    </div>
                </div>

                <div>
                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="text" name="email" id="email" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Email Id" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <span style="color: red;" id="emailErr" class="d-none">Enter a valid email
                    </span>
                </div>

                <div class="divider-div"></div>

                <div class="address-form-container">
                    <div class="add-address-label">Add address</div>

                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="text" name="address" id="address" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Address (House / Flat / Floor No.)" maxlength="100" value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="tel" name="pincode" id="pincode" maxlength="6" class="MuiInputBase-input-box MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Pincode" value="{{ old('pincode') }}" data-url="{{ route('ajax.get_pincode_details') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="primary-dropdown-container">
                        <div class="MuiFormControl-root css-1869usk-MuiFormControl-root">
                            <div class="MuiInputBase-root MuiInputBase-input-box MuiInput-root MuiInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-n70jm4-MuiInputBase-root-MuiInput-root-MuiSelect-root dropdown stateOrDistrict input-box" style="background-color: #cacaca;">
                                <div tabindex="0" role="combobox" aria-controls=":ra:" aria-expanded="false" aria-haspopup="listbox" aria-labelledby="demo-simple-select-standard-label demo-simple-select-standard" id="divStateName" class="MuiSelect-select MuiSelect-standard MuiInputBase-input MuiInput-input css-1rxz5jq-MuiSelect-select-MuiInputBase-input-MuiInput-input position-absolute" data-url="{{ route('ajax.get_all_states') }}">
                                    Gujarat
                                </div>

                                <input name="state" id="state" class="MuiSelect-nativeInput css-yf8vq0-MuiSelect-nativeInput d-none" placeholder="State *" value="7">


                                <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium MuiSelect-icon MuiSelect-iconStandard css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                                    <path d="M7 10l5 5 5-5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="primary-dropdown-container">
                        <div class="MuiFormControl-root css-1869usk-MuiFormControl-root">
                            <div class="MuiInputBase-root MuiInputBase-input-box MuiInput-root MuiInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-n70jm4-MuiInputBase-root-MuiInput-root-MuiSelect-root dropdown stateOrDistrict" style="background-color: #cacaca;">
                                <div tabindex="0" role="combobox" aria-controls=":rb:" aria-expanded="false" aria-haspopup="listbox" aria-labelledby="demo-simple-select-standard-label demo-simple-select-standard" id="divDistrictName" class="MuiSelect-select MuiSelect-standard MuiInputBase-input MuiInput-input  css-1rxz5jq-MuiSelect-select-MuiInputBase-input-MuiInput-input">
                                    Rajkot
                                </div>

                                <input name="district" id="district" class="MuiSelect-nativeInput css-yf8vq0-MuiSelect-nativeInput d-none" placeholder="District *" value="180">

                                <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium MuiSelect-icon MuiSelect-iconStandard css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                                    <path d="M7 10l5 5 5-5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="primary-dropdown-container">
                        <div class="MuiFormControl-root css-1869usk-MuiFormControl-root">
                            <div class="MuiInputBase-root MuiInputBase-input-box MuiInput-root MuiInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-n70jm4-MuiInputBase-root-MuiInput-root-MuiSelect-root dropdown" style="background-color: #cacaca;">
                                <div tabindex="0" role="combobox" aria-controls=":rc:" aria-expanded="false" aria-haspopup="listbox" aria-labelledby="demo-simple-select-standard-label demo-simple-select-standard" id="divAssemblyName" class="MuiSelect-select MuiSelect-standard MuiInputBase-input MuiInput-input css-1rxz5jq-MuiSelect-select-MuiInputBase-input-MuiInput-input">
                                    Jetpur (Rajkot)
                                </div>

                                <input name="assembly_constituency" id="assembly_constituency" class="MuiSelect-nativeInput css-yf8vq0-MuiSelect-nativeInput d-none" placeholder="Assembly constituency (AC)" value="805">

                                <svg class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium MuiSelect-icon MuiSelect-iconStandard css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="ArrowDropDownIcon">
                                    <path d="M7 10l5 5 5-5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider-div"></div>

                <div class="add-address-label">Referral (only a Primary Member can refer)</div>

                <div>
                    <input type="hidden" name="referred_user_id" id="referred_user_id">

                    <div>
                        <div class="input-container">
                            <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root" inputmode="text">
                                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                    <input type="text" name="referral_code" id="referral_code" class="MuiInputBase-input-box MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Mobile number" maxlength="10" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <span style="color: red;" class="d-none" id="errReferral">Please enter valid referral</span>

                    <div class="d-none" id="divReferredName" style="margin-top: 1rem;">
                        <div>
                            <div class="input-container">
                                <div class="MuiFormControl-root MuiTextField-root css-1u3bzj6-MuiFormControl-root-MuiTextField-root">
                                    <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary Mui-disabled MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root">
                                        <input type="text" name="referred_name" id="referred_name" class="MuiInputBase-input-box MuiFilledInput-input Mui-disabled css-10botns-MuiInputBase-input-MuiFilledInput-input" placeholder="Referred by (name)" disabled value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="height-div"></div>

                <div class="checkbox-container checkbox-container-user bg-pledge ">
                    <div>
                        <input type="checkbox" class="user-checkbox" id="isPledge">
                    </div>

                    <div class="checkbox-tnc pledge-style">I pledge and willing to receive regular updates from BJP
                    </div>
                </div>

                <button class="btn-container" id="btnSave" disabled>
                    <div></div>

                    Save

                    <div class="arrow-div">
                        <svg width="6" height="11" viewBox="0 0 6 11" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 5.75C6 5.9832 5.91646 6.21639 5.73744 6.4005L1.52461 10.7331C1.17852 11.089 0.605669 11.089 0.259572 10.7331C-0.0865241 10.3771 -0.0865241 9.78799 0.259572 9.43206L3.83988 5.75L0.259572 2.06794C-0.0865241 1.71201 -0.0865241 1.12288 0.259572 0.76695C0.605669 0.411017 1.17852 0.411017 1.52461 0.76695L5.73744 5.0995C5.91646 5.28361 6 5.5168 6 5.75Z"
                                fill="#878D92"></path>
                        </svg>
                    </div>
                </button>

                <div class="height-div-two"></div>
            </div>
        </form>

        <input type="hidden" id="commonSearchUrl" value="{{ route('ajax.get_search_options') }}">
    </div>

    <div role="presentation" id="menu-state" class="divModal MuiPopover-root MuiMenu-root MuiModal-root css-10nakn3-MuiModal-root-MuiPopover-root-MuiMenu-root d-none" style="display: flex; align-items: center; justify-content: center;">
        <div aria-hidden="true" class="MuiBackdrop-root MuiBackdrop-invisible MuiModal-backdrop css-g3hgs1-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation8 MuiPopover-paper MuiMenu-paper MuiMenu-paper css-12711zd-MuiPaper-root-MuiPopover-paper-MuiMenu-paper" tabindex="-1" style="opacity: 1; transform: none; min-width: 360px; transition: opacity 384ms cubic-bezier(0.4, 0, 0.2, 1), transform 256ms cubic-bezier(0.4, 0, 0.2, 1);  transform-origin: 180px 172.588px;">
            <div class="p-3 search-box" >
                <span class="mb-2 span-close" data-modal="state">
                    <?xml version="1.0" ?>
                    <svg height="20" viewBox="0 0 48 48" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M38 12.83l-2.83-2.83-11.17 11.17-11.17-11.17-2.83 2.83 11.17 11.17-11.17 11.17 2.83 2.83 11.17-11.17 11.17 11.17 2.83-2.83-11.17-11.17z"/><path d="M0 0h48v48h-48z" fill="none"/>
                    </svg>
                </span>

                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root mt-3">
                    <input type="text" name="keyword" id="search-state" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input border searchInput" data-search-type="state" placeholder="search">

                    <span class="clearSearch" data-search-type="state">
                        <?xml version="1.0" encoding="iso-8859-1"?>
                        <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 489.425 489.425" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M122.825,394.663c17.8,19.4,43.2,30.6,69.5,30.6h216.9c44.2,0,80.2-36,80.2-80.2v-200.7c0-44.2-36-80.2-80.2-80.2h-216.9 c-26.4,0-51.7,11.1-69.5,30.6l-111.8,121.7c-14.7,16.1-14.7,40.3,0,56.4L122.825,394.663z M29.125,233.063l111.8-121.8 c13.2-14.4,32-22.6,51.5-22.6h216.9c30.7,0,55.7,25,55.7,55.7v200.6c0,30.7-25,55.7-55.7,55.7h-217c-19.5,0-38.3-8.2-51.5-22.6 l-111.7-121.8C23.025,249.663,23.025,239.663,29.125,233.063z"/>
                                    <path d="M225.425,309.763c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6l47.8-47.8l47.8,47.8c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6 c4.8-4.8,4.8-12.5,0-17.3l-47.9-47.8l47.8-47.8c4.8-4.8,4.8-12.5,0-17.3s-12.5-4.8-17.3,0l-47.8,47.8l-47.8-47.8 c-4.8-4.8-12.5-4.8-17.3,0s-4.8,12.5,0,17.3l47.8,47.8l-47.8,47.8C220.725,297.263,220.725,304.962,225.425,309.763z"/>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>

            <ul class="MuiList-root MuiList-padding MuiMenu-list css-6hp17o-MuiList-root-MuiMenu-list" role="listbox" tabindex="-1" aria-labelledby="demo-simple-select-standard-label" id="stateUl" style="padding-right: 0px; width: calc(100% + 0px);">

            </ul>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

    <div role="presentation" id="menu-district" class="divModal MuiPopover-root MuiMenu-root MuiModal-root css-10nakn3-MuiModal-root-MuiPopover-root-MuiMenu-root d-none" style="display: flex; align-items: center; justify-content: center;">
        <div aria-hidden="true" class="MuiBackdrop-root MuiBackdrop-invisible MuiModal-backdrop css-g3hgs1-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation8 MuiPopover-paper MuiMenu-paper MuiMenu-paper css-12711zd-MuiPaper-root-MuiPopover-paper-MuiMenu-paper" tabindex="-1" style="opacity: 1; transform: none; min-width: 360px; transition: opacity 384ms cubic-bezier(0.4, 0, 0.2, 1), transform 256ms cubic-bezier(0.4, 0, 0.2, 1); transform-origin: 180px 172.588px;">
            <div class="p-3 search-box" >
                <span class="mb-2 span-close" data-modal="district">
                    <?xml version="1.0" ?>
                    <svg height="20" viewBox="0 0 48 48" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M38 12.83l-2.83-2.83-11.17 11.17-11.17-11.17-2.83 2.83 11.17 11.17-11.17 11.17 2.83 2.83 11.17-11.17 11.17 11.17 2.83-2.83-11.17-11.17z"/><path d="M0 0h48v48h-48z" fill="none"/>
                    </svg>
                </span>

                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root mt-3">
                    <input type="text" name="keyword" id="search-district" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input border searchInput" data-search-type="district" placeholder="search">

                    <span class="clearSearch" data-search-type="district">
                        <?xml version="1.0" encoding="iso-8859-1"?>
                        <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 489.425 489.425" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M122.825,394.663c17.8,19.4,43.2,30.6,69.5,30.6h216.9c44.2,0,80.2-36,80.2-80.2v-200.7c0-44.2-36-80.2-80.2-80.2h-216.9 c-26.4,0-51.7,11.1-69.5,30.6l-111.8,121.7c-14.7,16.1-14.7,40.3,0,56.4L122.825,394.663z M29.125,233.063l111.8-121.8 c13.2-14.4,32-22.6,51.5-22.6h216.9c30.7,0,55.7,25,55.7,55.7v200.6c0,30.7-25,55.7-55.7,55.7h-217c-19.5,0-38.3-8.2-51.5-22.6 l-111.7-121.8C23.025,249.663,23.025,239.663,29.125,233.063z"/>
                                    <path d="M225.425,309.763c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6l47.8-47.8l47.8,47.8c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6 c4.8-4.8,4.8-12.5,0-17.3l-47.9-47.8l47.8-47.8c4.8-4.8,4.8-12.5,0-17.3s-12.5-4.8-17.3,0l-47.8,47.8l-47.8-47.8 c-4.8-4.8-12.5-4.8-17.3,0s-4.8,12.5,0,17.3l47.8,47.8l-47.8,47.8C220.725,297.263,220.725,304.962,225.425,309.763z"/>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>

            <ul class="MuiList-root MuiList-padding MuiMenu-list css-6hp17o-MuiList-root-MuiMenu-list" role="listbox" tabindex="-1" aria-labelledby="demo-simple-select-standard-label" id="districtUl" style="padding-right: 0px; width: calc(100% + 0px);">

            </ul>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

    <div role="presentation" id="menu-assembly" class="divModal MuiPopover-root MuiMenu-root MuiModal-root css-10nakn3-MuiModal-root-MuiPopover-root-MuiMenu-root d-none" style="display: flex; align-items: center; justify-content: center;">
        <div aria-hidden="true" class="MuiBackdrop-root MuiBackdrop-invisible MuiModal-backdrop css-g3hgs1-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation8 MuiPopover-paper MuiMenu-paper MuiMenu-paper css-12711zd-MuiPaper-root-MuiPopover-paper-MuiMenu-paper" tabindex="-1" style="opacity: 1; transform: none; min-width: 360px; transition: opacity 384ms cubic-bezier(0.4, 0, 0.2, 1), transform 256ms cubic-bezier(0.4, 0, 0.2, 1); transform-origin: 180px 172.588px;">
            <div class="p-3 search-box" >
                <span class="mb-2 span-close" data-modal="assembly">
                    <?xml version="1.0" ?>
                    <svg height="20" viewBox="0 0 48 48" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M38 12.83l-2.83-2.83-11.17 11.17-11.17-11.17-2.83 2.83 11.17 11.17-11.17 11.17 2.83 2.83 11.17-11.17 11.17 11.17 2.83-2.83-11.17-11.17z"/><path d="M0 0h48v48h-48z" fill="none"/>
                    </svg>
                </span>

                <div class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-colorPrimary MuiInputBase-formControl css-batk84-MuiInputBase-root-MuiFilledInput-root mt-3">
                    <input type="text" name="keyword" id="search-assembly" class="MuiInputBase-input-box MuiInputBase-input MuiFilledInput-input css-10botns-MuiInputBase-input-MuiFilledInput-input border searchInput" data-search-type="assembly" placeholder="search">

                    <span class="clearSearch" data-search-type="assembly">
                        <?xml version="1.0" encoding="iso-8859-1"?>
                        <svg fill="#000000" height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 489.425 489.425" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M122.825,394.663c17.8,19.4,43.2,30.6,69.5,30.6h216.9c44.2,0,80.2-36,80.2-80.2v-200.7c0-44.2-36-80.2-80.2-80.2h-216.9 c-26.4,0-51.7,11.1-69.5,30.6l-111.8,121.7c-14.7,16.1-14.7,40.3,0,56.4L122.825,394.663z M29.125,233.063l111.8-121.8 c13.2-14.4,32-22.6,51.5-22.6h216.9c30.7,0,55.7,25,55.7,55.7v200.6c0,30.7-25,55.7-55.7,55.7h-217c-19.5,0-38.3-8.2-51.5-22.6 l-111.7-121.8C23.025,249.663,23.025,239.663,29.125,233.063z"/>
                                    <path d="M225.425,309.763c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6l47.8-47.8l47.8,47.8c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6 c4.8-4.8,4.8-12.5,0-17.3l-47.9-47.8l47.8-47.8c4.8-4.8,4.8-12.5,0-17.3s-12.5-4.8-17.3,0l-47.8,47.8l-47.8-47.8 c-4.8-4.8-12.5-4.8-17.3,0s-4.8,12.5,0,17.3l47.8,47.8l-47.8,47.8C220.725,297.263,220.725,304.962,225.425,309.763z"/>
                                </g>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>

            <ul class="MuiList-root MuiList-padding MuiMenu-list css-6hp17o-MuiList-root-MuiMenu-list" role="listbox" tabindex="-1" aria-labelledby="demo-simple-select-standard-label" id="assemblyUl" style="padding-right: 0px; width: calc(100% + 0px);">

            </ul>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

    <div role="presentation" id="uploadProfilePicPopup" class="MuiDrawer-root MuiDrawer-modal MuiModal-root css-195ptfl-MuiModal-root-MuiDrawer-root d-none">
        <div aria-hidden="true" class="MuiBackdrop-root MuiModal-backdrop css-i9fmh8-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-elevation16 MuiDrawer-paper MuiDrawer-paperAnchorBottom css-9emuhu-MuiPaper-root-MuiDrawer-paper" tabindex="-1" style="transform: none; transition: transform 225ms cubic-bezier(0, 0, 0.2, 1);">
            <div class="MuiBox-root css-10mm8wz" role="presentation">
                <div class="profile-upload-containerr">
                    <div class="profile-upload-sub-containerr">
                        <div>
                            <label class="file-label">
                                <div class="upload-icon">
                                    <svg width="124" height="95" viewBox="0 0 124 95" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M64.8419 23.5506L64.5283 23.4687C63.583 23.2218 62.9207 22.292 63.254 21.3127L64.8419 23.5506ZM64.8419 23.5506L64.8658 23.5265M64.8419 23.5506L64.8658 23.5265M64.8658 23.5265C65.7025 23.5985 66.5581 23.154 66.8368 22.33C68.8812 16.3162 75.3599 12.0479 82.6512 12.0479C83.6144 12.0479 84.5273 11.3489 84.5273 10.335C84.5273 9.32101 83.6144 8.62205 82.6512 8.62205C73.5045 8.62205 65.7543 13.9565 63.2541 21.3124L64.8658 23.5265Z" fill="#F15600" stroke="#F9FFF9" stroke-width="1.12845"></path>
                                        <path d="M101.015 66.6194H93.1443C92.42 66.6194 91.8324 66.1049 91.8324 65.4707C91.8324 64.8365 92.4199 64.322 93.1443 64.322H101.015C111.866 64.322 120.694 56.5923 120.694 47.0921C120.694 37.5919 111.866 29.8622 101.015 29.8622H100.826C100.446 29.8622 100.084 29.7178 99.8348 29.4659C99.5855 29.214 99.4731 28.88 99.5274 28.5502C99.6446 27.8348 99.7036 27.116 99.7036 26.4162C99.7036 18.1828 92.0524 11.4835 82.6491 11.4835C78.9908 11.4835 75.5021 12.4845 72.5597 14.3789C71.9131 14.7949 70.9948 14.6103 70.6209 13.9875C62.2877 0.0937152 40.5223 -1.77209 29.2381 10.3143C24.4845 15.4062 22.6167 22.0298 24.1134 28.4855C24.2783 29.1985 23.6551 29.863 22.8277 29.863H22.302C11.452 29.863 2.62388 37.5928 2.62388 47.093C2.62388 56.5932 11.452 64.3229 22.302 64.3229H30.1732C30.8975 64.3229 31.4851 64.8373 31.4851 65.4716C31.4851 66.1058 30.8976 66.6202 30.1732 66.6202H22.302C10.005 66.6202 0 57.86 0 47.0929C0 36.6278 9.45106 28.0587 21.2722 27.5861C20.1618 20.8885 22.2852 14.1327 27.2065 8.86047C39.2879 -4.08082 62.4411 -2.63027 72.2284 11.8002C75.3508 10.0862 78.9237 9.18709 82.6486 9.18709C94.0413 9.18709 103.069 17.6774 102.279 27.5968C113.991 28.172 123.317 36.6958 123.317 47.092C123.317 57.86 113.312 66.6194 101.015 66.6194L101.015 66.6194Z" fill="#F15600"></path>
                                        <path d="M28.1216 64.8185C28.1216 81.0328 43.1599 94.0996 61.4831 94.0996C79.8066 94.0996 94.8447 81.0326 94.8447 64.8185C94.8447 48.6041 79.8066 35.5373 61.4831 35.5373C43.1597 35.5373 28.1216 48.6043 28.1216 64.8185ZM31.8744 64.8185C31.8744 50.6309 45.0846 38.9636 61.4831 38.9636C77.8814 38.9636 91.0918 50.6307 91.0918 64.8185C91.0918 79.006 77.8814 90.6733 61.4831 90.6733C45.0849 90.6733 31.8744 79.0062 31.8744 64.8185Z" fill="#F15600" stroke="#F9FFF9" stroke-width="1.12845"></path>
                                        <path d="M60.5578 76.3778C60.5578 77.2218 61.3134 77.7829 62.0825 77.7829C62.8514 77.7829 63.6072 77.2226 63.6072 76.3778V54.5128C63.6072 53.6688 62.8517 53.1076 62.0825 53.1076C61.3133 53.1076 60.5578 53.6688 60.5578 54.5128V76.3778Z" fill="#F15600" stroke="#F15600" stroke-width="1.12845"></path>
                                        <path d="M68.7162 62.2597C69.0173 62.5233 69.3999 62.6457 69.767 62.6457H69.7671C70.133 62.6457 70.5161 62.5246 70.8179 62.2597C71.4498 61.7063 71.4498 60.7747 70.8179 60.2214L63.1344 53.4938C62.5467 52.9792 61.6204 52.9787 61.0327 53.4938C61.0327 53.4938 61.0326 53.4939 61.0326 53.4939C61.0325 53.494 61.0324 53.4941 61.0323 53.4941L53.3492 60.2214C52.7172 60.7747 52.7172 61.7063 53.3492 62.2597C53.9369 62.7743 54.8632 62.7748 55.4509 62.2597C55.451 62.2596 55.451 62.2596 55.4511 62.2596C55.4511 62.2595 55.4512 62.2594 55.4513 62.2594L62.0836 56.4522L68.7162 62.2597C68.7162 62.2597 68.7162 62.2597 68.7162 62.2597Z" fill="#F15600" stroke="#F15600" stroke-width="1.12845"></path>
                                    </svg>
                                </div>

                                <input id="file-upload-gallery" type="file" class="file-input" accept="image/jpeg, image/png, image/heic, image/heif">
                            </label>
                        </div>

                        <div class="select-label">Select</div>

                        <div class="or-label">Supported formats : Jpg, Jpeg, Png, Heic, Heif, Bmp</div>

                        <div class="btns-container">
                            <label for="file-upload-gallery">
                                <div class="gallery-btn">Gallery</div>
                            </label>
                        </div>
                    </div>

                    <button class="cancel-label">Cancel</button>
                </div>
            </div>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

    <div role="presentation" id="previewUploadedImg" class="MuiDrawer-root MuiDrawer-modal MuiModal-root css-195ptfl-MuiModal-root-MuiDrawer-root d-none">
        <div aria-hidden="true" class="MuiBackdrop-root MuiModal-backdrop css-i9fmh8-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-elevation16 MuiDrawer-paper MuiDrawer-paperAnchorBottom css-9emuhu-MuiPaper-root-MuiDrawer-paper" tabindex="-1" style="transform: none; transition: transform 225ms cubic-bezier(0, 0, 0.2, 1);">
            <div class="MuiBox-root css-10mm8wz" role="presentation">
                <div class="profile-upload-containerr">
                    <div class="profile-upload-sub-containerr">
                        <div class="profile-image-container-two">
                            <div class="profile-img-div">
                                <img src="" id="profile-img" alt="Profile" class="profile-img">
                            </div>

                            <button class="" id="btnPreviewCancel">Cancel</button>
                        </div>
                    </div>

                    <button class="width-btn btn-container" id="btnUploadImage" data-img-upload-url="{{ route('front.update.user.image') }}">
                        <div></div>

                        <p class="m-0"> Upload</p>

                        <div id="uploadImgSvg">
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="17.1343" cy="17.1343" r="17.1343" fill="#FE9900"></circle>
                                <path d="M21.4179 17.9376C21.4179 18.1873 21.3284 18.4371 21.1367 18.6342L16.6252 23.2739C16.2546 23.6551 15.6411 23.6551 15.2705 23.2739C14.8999 22.8928 14.8999 22.2619 15.2705 21.8807L19.1046 17.9376L15.2705 13.9945C14.8999 13.6133 14.8999 12.9825 15.2705 12.6013C15.6411 12.2201 16.2546 12.2201 16.6252 12.6013L21.1367 17.241C21.3284 17.4382 21.4179 17.6879 21.4179 17.9376Z" fill="white"></path>
                            </svg>
                        </div>

                        <div class="text-center d-none" id="uploadImgBtnLoader">
                            <div class="spinner-border" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

    <div role="presentation" id="menu-salutation" class="divModal MuiPopover-root MuiMenu-root MuiModal-root css-10nakn3-MuiModal-root-MuiPopover-root-MuiMenu-root d-none" style="display: flex; align-items: center; justify-content: center;">
        <div aria-hidden="true" class="MuiBackdrop-root MuiBackdrop-invisible MuiModal-backdrop css-g3hgs1-MuiBackdrop-root-MuiModal-backdrop" style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>

        <div tabindex="0" data-testid="sentinelStart"></div>

        <div class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation8 MuiPopover-paper MuiMenu-paper MuiMenu-paper css-12711zd-MuiPaper-root-MuiPopover-paper-MuiMenu-paper" tabindex="-1" style="opacity: 1; transform: none; min-width: 360px; transition: opacity 384ms cubic-bezier(0.4, 0, 0.2, 1), transform 256ms cubic-bezier(0.4, 0, 0.2, 1);  transform-origin: 180px 172.588px;">
            <div class="p-3 search-box">
                <span class="mb-2 span-close" data-modal="salutation">
                    <?xml version="1.0" ?>
                    <svg height="20" viewBox="0 0 48 48" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M38 12.83l-2.83-2.83-11.17 11.17-11.17-11.17-2.83 2.83 11.17 11.17-11.17 11.17 2.83 2.83 11.17-11.17 11.17 11.17 2.83-2.83-11.17-11.17z"/>
                        <path d="M0 0h48v48h-48z" fill="none"/>
                    </svg>
                </span>
            </div>

            <ul class="MuiList-root MuiList-padding MuiMenu-list css-6hp17o-MuiList-root-MuiMenu-list" role="listbox" tabindex="-1" aria-labelledby="demo-simple-select-standard-label" id="salutationUl" style="padding-right: 0px; width: calc(100% + 0px);">
                <li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root salutationLi" tabindex="0" role="option" aria-selected="false" data-id="mrs" data-name="Mrs"> Mrs <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>
                <li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root salutationLi" tabindex="0" role="option" aria-selected="false" data-id="mr" data-name="Mr"> Mr <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>
                <li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root salutationLi" tabindex="0" role="option" aria-selected="false" data-id="ms" data-name="Ms"> Ms <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>
            </ul>
        </div>

        <div tabindex="0" data-testid="sentinelEnd"></div>
    </div>

@section('scripts')
    @parent

    <script>
        $(document).on('click', '#divSalutationName', function() {
            $('#menu-salutation').removeClass('d-none');
        });

        $(document).on('click', '.salutationLi', function() {
            const salutationId = $(this).data('id');
            const salutationName = $(this).data('name');

            $('#salutation').val(salutationId);
            $('#divSalutationName').html(salutationName);
            $('#menu-salutation').addClass('d-none');
            $('#salutation').addClass('d-none');

            checkFormValues();
        });

        // $(document).on('click', '#divStateName', function() {
        //     $.ajax({
        //         type: "GET",
        //         url: "{!! route('ajax.get_all_states') !!}",
        //         dataType: "json",
        //         success: function(response) {
        //             if (response.success) {
        //                 $('#stateUl').html(response.html);
        //                 $('#menu-state').removeClass('d-none');
        //             }
        //         }
        //     });
        // });

        // $(document).on('click', '.stateLi', function() {
        //     const stateId = $(this).data('id');
        //     const stateName = $(this).data('name');

        //     $('#state').val(stateId);
        //     $('#divStateName').html(stateName);

        //     $('#district').val('');
        //     $('#district').removeClass('d-none');
        //     $('#divDistrictName').html('');

        //     $('#assembly_constituency').val('');
        //     $('#assembly_constituency').removeClass('d-none');
        //     $('#divAssemblyName').html('');

        //     $('#menu-state').addClass('d-none');
        //     $('#state').addClass('d-none');

        //     $.ajax({
        //         type: "POST",
        //         url: "{!! route('ajax.get_districts_and_assemblies') !!}",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "stateId": stateId
        //         },
        //         dataType: "json",
        //         success: function(response) {
        //             if (response.success) {
        //                 $('#districtUl').html(response.districtHtml);
        //                 $('#assemblyUl').html(response.assemblyHtml);

        //                 checkFormValues();
        //             }
        //         }
        //     });
        // });

        // $(document).on('click', '#divDistrictName', function() {
        //     const stateId = $('#state').val();

        //     if (stateId != '') {
        //         $('#menu-district').removeClass('d-none');
        //     }
        // });

        // $(document).on('click', '.districtLi', function() {
        //     const districtId = $(this).data('id');
        //     const districtName = $(this).data('name');

        //     $('#district').val(districtId);
        //     $('#divDistrictName').html(districtName);
        //     $('#menu-district').addClass('d-none');
        //     $('#district').addClass('d-none');

        //     checkFormValues();
        // });

        // $(document).on('click', '#divAssemblyName', function() {
        //     const stateId = $('#state').val();

        //     if (stateId != '') {
        //         $('#menu-assembly').removeClass('d-none');
        //     }
        // });

        // $(document).on('click', '.assemblyLi', function() {
        //     const assemblyId = $(this).data('id');
        //     const assemblyName = $(this).data('name');

        //     $('#assembly_constituency').val(assemblyId);
        //     $('#divAssemblyName').html(assemblyName);
        //     $('#menu-assembly').addClass('d-none');
        //     $('#assembly_constituency').addClass('d-none');

        //     checkFormValues();
        // });

        $(document).on('change', '#first_name, #last_name, #dob, #isPledge', function() {
            checkFormValues();
        });

        function checkFormValues() {
            const salutation = $('#salutation').val();
            const first_name = $('#first_name').val();
            const last_name = $('#last_name').val();
            const dob = $('#dob').val();
            const gender = $('#gender').val();
            const state = $('#state').val();
            const district = $('#district').val();
            const assembly = $('#assembly_constituency').val();
            const isPledgeChecked = $('#isPledge').is(':checked');
            const isReferralValid = $('#errReferral').hasClass('d-none');

            const isSaveBtnDisabled = $('#btnSave').is(':disabled');
            let isValidValues = false;

            if (salutation != '' && first_name != '' && last_name != '' && dob != '' && gender != '' && state != '' && district != '' && assembly != '' &&
            isReferralValid && isPledgeChecked) {
                isValidValues = true;
            }

            if (isValidValues) {
                $('#btnSave').removeAttr('disabled');
            } else {
                $('#btnSave').attr('disabled', true);
            }
        }

        $(document).on('keyup, change', '#referral_code', function() {
            const input = $(this).val();

            if (input == '') {
                $('#errReferral').addClass('d-none');
                checkFormValues();
            }

            if (isNaN(input)) {
                $(this).val(input.toUpperCase().substring(0, 6));

                if (input.length === 6) {
                    checkUser(input, 'referral');
                    return
                }
            } else {
                $(this).val(input.substring(0, 10));

                if (input.length === 10) {
                    checkUser(input, 'mobile');
                    return
                }
            }

            const referred_user_id = $('#referred_user_id').val();
            const referred_name = $('#referred_name').val();

            if (referred_user_id != '') {
                $('#referred_user_id').val('');
            }
            if (referred_name != '') {
                $('#referred_name').val('');
            }

            $('#divReferredName').addClass('d-none');
        });

        function checkUser(value, type) {
            $('#errReferral').addClass('d-none');
            $('#divReferredName').addClass('d-none');

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
                        $('#divReferredName').removeClass('d-none');
                    } else {
                        $('#errReferral').removeClass('d-none');
                    }

                    checkFormValues()
                }
            });
        }
    </script>
@endsection
@endsection
