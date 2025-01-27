@extends('front.layouts.app')

@section('styles')
    @parent
@endsection

@section('content')
    <div class="main-container-bg main-container">
        @include('front.layouts.header')

        <form action="{{ route('login') }}" method="post">
            @csrf

            <div class="auth-container">
                <div class="auth-text-container">
                    <img src="{{ asset('frontAssets/imgs/satyasena.jpg') }}" alt="not_found"
                        style="width: 100%; border-radius: 8px">
                    <p class="auth-text">Contact: +91 9105144444</p>
                </div>

                <div class="d-flex flex-column" style="margin: 0px 26px; row-gap: 13px">
                    <p class="m-0 enter-mobile">Enter your mobile number</p>
                    <div class="d-flex number-container bg-white input-container-border">
                        <p class="input-p m-0 input-number-country-code">+91</p>
                        <input type="tel" name="mobileNumber" class="input-number w-100 bg-white editable-num" maxlength="10" value="">

                        <div class="padding-top-number" style="margin-right: 10px"></div>
                    </div>

                    <div class="d-flex checkbox-container colGap-13">
                        <div class="login-checkbox-container">
                            <input type="checkbox" class="checkbox-auth custom-checkbox" checked="checked">
                        </div>

                        <div class="terms-container">
                            <label for="termsCheckbox" class="label-text">
                                I certify that above provided information is correct and
                                there is no mistake. I know that all further communication
                                will be done on above provided details
                            </label>
                        </div>
                    </div>
                </div>

                <div class="w-25 d-flex justify-content-center">
                    <div class="position-relative bg-white"></div>
                </div>

                <div class="d-flex justify-content-end flex-column h-100" style="padding: 15px 16px">
                    <button type="submit" class="btn-container" id="btnLogin" disabled>
                        <div></div>

                        Login / Register

                        <div class="arrow-div">
                            <svg width="6" height="11" viewBox="0 0 6 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6 5.75C6 5.9832 5.91646 6.21639 5.73744 6.4005L1.52461 10.7331C1.17852 11.089 0.605669 11.089 0.259572 10.7331C-0.0865241 10.3771 -0.0865241 9.78799 0.259572 9.43206L3.83988 5.75L0.259572 2.06794C-0.0865241 1.71201 -0.0865241 1.12288 0.259572 0.76695C0.605669 0.411017 1.17852 0.411017 1.52461 0.76695L5.73744 5.0995C5.91646 5.28361 6 5.5168 6 5.75Z"
                                    fill="#878D92"></path>
                            </svg>
                        </div>
                    </button>
                </div>

                <div class="position-relative bg-white"></div>
            </div>
        </form>

        <div role="presentation"
            class="MuiDrawer-root MuiDrawer-modal MuiModal-root css-195ptfl-MuiModal-root-MuiDrawer-root d-none"
            id="divTermsCondition">
            <div aria-hidden="true" class="MuiBackdrop-root MuiModal-backdrop css-i9fmh8-MuiBackdrop-root-MuiModal-backdrop"
                style="opacity: 1; transition: opacity 225ms cubic-bezier(0.4, 0, 0.2, 1);"></div>
            <div tabindex="0" data-testid="sentinelStart"></div>

            <div class="MuiPaper-root MuiPaper-elevation MuiPaper-elevation16 MuiDrawer-paper MuiDrawer-paperAnchorBottom css-9emuhu-MuiPaper-root-MuiDrawer-paper"
                tabindex="-1" style="transform: none; transition: transform 225ms cubic-bezier(0, 0, 0.2, 1);">
                <div class="MuiBox-root css-sp5hsl" role="presentation">
                    <div class=" h-100 overflow-hdn">
                        <div variant="h6" class="terms" style="color: white; font-size: larger;">
                            Terms and Conditions
                        </div>

                        <div class="bg-white h-100 drawer-box">
                            <div variant="body2" class="mandatory-text">
                                "You must tick the box to continue, as it is mandatory."
                            </div>

                            <div class="MuiBox-root css-xi606m">
                                <button class="cursor-pointer okay-btn" variant="contained" sx="[object Object]">
                                    <div></div>

                                    <p class="m-0">Okay, Got it</p>
                                    <svg width="35" height="36" viewBox="0 0 35 36" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" alt="Next">
                                        <circle cx="17.5113" cy="17.8951" r="17.1343" fill="white"></circle>
                                        <path
                                            d="M21.7945 18.6981C21.7945 18.9478 21.705 19.1976 21.5133 19.3947L17.0018 24.0344C16.6312 24.4156 16.0177 24.4156 15.6471 24.0344C15.2765 23.6533 15.2765 23.0224 15.6471 22.6412L19.4813 18.6981L15.6471 14.755C15.2765 14.3738 15.2765 13.743 15.6471 13.3618C16.0177 12.9806 16.6312 12.9806 17.0018 13.3618L21.5133 18.0015C21.705 18.1987 21.7945 18.4484 21.7945 18.6981Z"
                                            fill="#F5821F"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div tabindex="0" data-testid="sentinelEnd"></div>
        </div>
    </div>

@section('scripts')
    @parent
    <script>
        const mobileNumberMaxLength = 10;

        $(document).on('change', '.checkbox-auth', function() {
            const mobileNumber = $('.input-number').val();
            const isCheckboxChecked = $(this).is(':checked');

            if (isCheckboxChecked) {
                $(this).attr('checked', 'checked');
            } else {
                $('#divTermsCondition').removeClass('d-none');
                $(this).removeAttr('checked');
            }

            validateLoginValue(mobileNumber, isCheckboxChecked);
        });

        $(document).on('click', '.okay-btn', function(e) {
            $('#divTermsCondition').addClass('d-none');
        });

        $(document).on('keydown', '.input-number', function(e) {
            const allowedKeys = [
                "0",
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
                "Backspace",
                "Delete",
                "ArrowLeft",
                "ArrowRight",
                "Tab",
                "Enter",
            ];

            const input = e.target;

            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case "a":
                    case "c":
                    case "v":
                    case "x":
                    case "z":
                    case "y":
                        return;
                }
            }

            if (!allowedKeys.includes(e.key)) {
                e.preventDefault();
                return;
            }

            if (input.value.length >= mobileNumberMaxLength) {
                if (e.key !== "Backspace" && e.key !== "Delete" && !e.ctrlKey && !e.metaKey) {
                    e.preventDefault();
                }
            }
        });

        $(document).on('keyup', '.input-number', function(e) {
            const mobileNumber = $(this).val();
            const isCheckboxChecked = $('.checkbox-auth').is(':checked');

            validateLoginValue(mobileNumber, isCheckboxChecked);
        });

        function validateLoginValue(mobileNumber, isCheckboxChecked) {
            if (mobileNumber.length == mobileNumberMaxLength && isCheckboxChecked) {
                $('#btnLogin').removeAttr('disabled');
            } else {
                $('#btnLogin').attr('disabled', true);
            }
        }
    </script>
@endsection
@endsection
