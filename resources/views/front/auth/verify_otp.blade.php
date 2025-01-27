<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BJP | Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontAssets/css/styles.css') }}">
    <style>
        .swal2-popup.swal2-toast {
            font-size: 13px;
        }
    </style>
    </div>
</head>

<body>
    <div class="main-container-bg main-container">
        <div class="d-flex align-style align-items-center secondary-container fixed-position">
            <div class="d-flex align-items-center" style="column-gap: 6px;">
                <img src="{{ asset('frontAssets/imgs/logo.png') }}" alt="logo">
                <p class="m-0 bjp-text">BHARATIYA JANATA PARTY</p>
            </div>
        </div>

        <div class="auth-container">
            <div class="auth-text-container">
                <img src="{{ asset('frontAssets/imgs/img2.png') }}" alt="not_found" style="width: 100%; border-radius: 8px">
                <p class="auth-text">Contact: +91 9105144444</p>
            </div>
            <div class="d-flex flex-column" style="margin: 0px 26px; row-gap: 13px;">
                <p class="m-0 enter-mobile">Enter Verification Code</p>

                <div class="d-flex number-container input-container-bg input-container-border">
                    <p class="input-p m-0 number-country-code ">+91</p>
                    <input class="input-number w-100 input-container-bg non-editable-num" maxlength="10" type="tel" value="{{ auth()->user()->mobile_number }}" disabled="">

                    <div class="padding-top-number" id="btnEditMobileNumber" style="margin-right: 10px;">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="cursor-pointer ">
                            <path d="M2.5605 10.4781L10.167 2.87159L9.1065 1.81109L1.5 9.41759V10.4781H2.5605ZM3.18225 11.9781H0V8.79584L8.57625 0.21959C8.7169 0.0789865 8.90763 0 9.1065 0C9.30537 0 9.4961 0.0789865 9.63675 0.21959L11.7585 2.34134C11.8991 2.48199 11.9781 2.67272 11.9781 2.87159C11.9781 3.07046 11.8991 3.26119 11.7585 3.40184L3.18225 11.9781ZM0 13.4781H13.5V14.9781H0V13.4781Z" fill="black"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="otp-input-container flex-column">
                <div style="display: flex; align-items: center;">
                    <input autocomplete="off" aria-label="Please enter OTP character 1" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                    <input autocomplete="off" aria-label="Please enter OTP character 2" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                    <input autocomplete="off" aria-label="Please enter OTP character 3" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                    <input autocomplete="off" aria-label="Please enter OTP character 4" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                    <input autocomplete="off" aria-label="Please enter OTP character 5" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                    <input autocomplete="off" aria-label="Please enter OTP character 6" type="number" inputmode="numeric" value="" style="width: 54px; text-align: center; column-gap: 8px; border: 1px solid rgb(204, 204, 204); border-radius: 8px; height: 54px; font-size: 20px; color: rgb(0, 0, 0); font-weight: 400; caret-color: blue;">
                </div>

                <div class="d-flex justify-content-end">
                    <button disabled="" class="timer-class" style="color: rgb(158, 158, 158); cursor: not-allowed;">
                        <p class="m-0">Resend Verification Code <span class="span-resend">00:40</span></p>
                    </button>
                </div>
            </div>

            <div class="w-25 d-flex justify-content-center">
                <div class="position-relative bg-white"></div>
            </div>

            <div class="d-flex justify-content-end flex-column h-100" style="padding: 15px 16px;">
                <button class="btn-container" disabled="">
                    <div></div>

                    Continue

                    <div class="arrow-div">
                        <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 5.75C6 5.9832 5.91646 6.21639 5.73744 6.4005L1.52461 10.7331C1.17852 11.089 0.605669 11.089 0.259572 10.7331C-0.0865241 10.3771 -0.0865241 9.78799 0.259572 9.43206L3.83988 5.75L0.259572 2.06794C-0.0865241 1.71201 -0.0865241 1.12288 0.259572 0.76695C0.605669 0.411017 1.17852 0.411017 1.52461 0.76695L5.73744 5.0995C5.91646 5.28361 6 5.5168 6 5.75Z" fill="#878D92"></path>
                        </svg>
                    </div>
                </button>
            </div>

            <div class="position-relative bg-white"></div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).on('click', '#btnEditMobileNumber', function() {
            window.location.href = "{{ route('login') }}";
        });
    </script>
</body>
</html>
