@extends('front.layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('content')
    <div class="main-container-bg main-container">
        @include('front.layouts.header')

        <div class="user-area-container">
            <div class="congrats-modal"></div>

            <div class="margin-dividerr"></div>
            <div class="margin-dividerrr"></div>

            <a href="{{ route('front.show.update.details.form') }}">
                <div class="update-btn">Update details</div>
            </a>

            <p class="more-text">Click above to share more about yourself, donate, and make other members join the BJP</p>

            <div class="dashboard-divider"></div>
            {{-- <h3 class="mt-4 i-card-header">Share Your Referral Link</h3> --}}

            {{-- <div class="loader-container"></div> --}}

            {{-- <div class="d-flex refer-link-container justify-content-between cursor-pointer ">
                <a class="copy-to-clipboard" target="_blank" href="https://narendramodi.in/bjpsadasyata2024/S0189B">
                    <p class="refer-link-text m-0 "> https://narendramodi.in/<br>bjpsadasyata2024/S0189B</p>
                </a>

                <div class="copy-text">
                    <button>COPY</button>
                </div>
            </div> --}}

            <div class="mt-3" style="display: flex; padding: 0px 60px; gap: 10px; align-items: center;">
                <div style="flex: 1 1 0%; font-size: 10px; font-weight: 300;">
                    <p class="referral-code-text">Your Referral Code : <span></span>
                        <span class="code-span">{{ $user->referral_code }}</span>
                    </p>
                </div>
            </div>

            {{-- <button class="d-flex align-items-center justify-content-center share-btn-text">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.167 18.3337C13.4725 18.3337 12.8823 18.0906 12.3962 17.6045C11.91 17.1184 11.667 16.5281 11.667 15.8337C11.667 15.7503 11.6878 15.5559 11.7295 15.2503L5.87533 11.8337C5.6531 12.042 5.39616 12.2053 5.10449 12.3237C4.81283 12.442 4.50033 12.5009 4.16699 12.5003C3.47255 12.5003 2.88227 12.2573 2.39616 11.7712C1.91005 11.285 1.66699 10.6948 1.66699 10.0003C1.66699 9.30588 1.91005 8.7156 2.39616 8.22949C2.88227 7.74338 3.47255 7.50032 4.16699 7.50032C4.50033 7.50032 4.81283 7.55949 5.10449 7.67782C5.39616 7.79616 5.6531 7.95921 5.87533 8.16699L11.7295 4.75032C11.7017 4.6531 11.6845 4.55949 11.6778 4.46949C11.6712 4.37949 11.6675 4.27866 11.667 4.16699C11.667 3.47255 11.91 2.88227 12.3962 2.39616C12.8823 1.91005 13.4725 1.66699 14.167 1.66699C14.8614 1.66699 15.4517 1.91005 15.9378 2.39616C16.4239 2.88227 16.667 3.47255 16.667 4.16699C16.667 4.86144 16.4239 5.45171 15.9378 5.93782C15.4517 6.42394 14.8614 6.66699 14.167 6.66699C13.8337 6.66699 13.5212 6.60782 13.2295 6.48949C12.9378 6.37116 12.6809 6.2081 12.4587 6.00032L6.60449 9.41699C6.63227 9.51421 6.64977 9.6081 6.65699 9.69866C6.66421 9.78921 6.66755 9.88977 6.66699 10.0003C6.66644 10.1109 6.6631 10.2117 6.65699 10.3028C6.65088 10.3939 6.63338 10.4875 6.60449 10.5837L12.4587 14.0003C12.6809 13.792 12.9378 13.6289 13.2295 13.5112C13.5212 13.3934 13.8337 13.3342 14.167 13.3337C14.8614 13.3337 15.4517 13.5767 15.9378 14.0628C16.4239 14.5489 16.667 15.1392 16.667 15.8337C16.667 16.5281 16.4239 17.1184 15.9378 17.6045C15.4517 18.0906 14.8614 18.3337 14.167 18.3337Z"
                        fill="white"></path>
                </svg>

                <p class="m-0 text-white">Share</p>
            </button> --}}

            {{-- <div class="dashboard-divider"></div> --}}

            {{-- <div class="d-flex ref-text-container">
                <div class="text-content">
                    <p class="m-1 ref-text-d">Referral Count</p>
                    <p class="ref-digit m-0">0</p>
                </div>

                <div>
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.7866 7.78647L20.3333 15.3331L12.7866 22.8798L11.8533 21.9331L18.4533 15.3331L11.8533 8.73313L12.7866 7.78647Z"
                            fill="black"></path>
                    </svg>
                </div>
            </div> --}}

            <div class="dashboard-divider margin-divider-new"></div>
            <h3 class="mt-4 i-card-header">Membership Card</h3>
            <p class="membership-number-text">Membership Number : <span class="membership-number">{{ $user->membership_number }}</span> </p>
            <p class="membership-number-text">Date of Joining : <span class="membership-number">{{ date('d M, Y', strtotime($user->created_at)) }}</span> </p>

            <div class="loader-container"></div>

            <div>
                <div class="position-relative mt-16 id-card-border" id="id-card-image">
                    <img src="{{ asset('frontAssets/imgs/id-card.png') }}" class="id-card-svg-img" alt="Not available">
                    <p class="id-card-user-name">{{ $user->name }}</p>
                    <p class="id-card-state">{{ $user->state ? $user->state->name : '' }}</p>
                    <p class="id-card-user-membership-id user-member-ship-id">{{ formatMembershipNumber($user->membership_number) }}</p>

                    @if ($user->image != '' && \File::exists(public_path('uploads/users/' . $user->image)))
                        <img src="{{ asset('uploads/users/'.auth()->user()->image) }}" class="idcard-user-img" alt="user image">
                    @endif

                    <canvas height="160" width="160" class="idcard-qr" style="height: 128px; width: 128px;">
                    </canvas>
                </div>

                {{-- <div class="action-container">
                    <div class="id-share-btn ">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.167 18.3337C13.4725 18.3337 12.8823 18.0906 12.3962 17.6045C11.91 17.1184 11.667 16.5281 11.667 15.8337C11.667 15.7503 11.6878 15.5559 11.7295 15.2503L5.87533 11.8337C5.6531 12.042 5.39616 12.2053 5.10449 12.3237C4.81283 12.442 4.50033 12.5009 4.16699 12.5003C3.47255 12.5003 2.88227 12.2573 2.39616 11.7712C1.91005 11.285 1.66699 10.6948 1.66699 10.0003C1.66699 9.30588 1.91005 8.7156 2.39616 8.22949C2.88227 7.74338 3.47255 7.50032 4.16699 7.50032C4.50033 7.50032 4.81283 7.55949 5.10449 7.67782C5.39616 7.79616 5.6531 7.95921 5.87533 8.16699L11.7295 4.75032C11.7017 4.6531 11.6845 4.55949 11.6778 4.46949C11.6712 4.37949 11.6675 4.27866 11.667 4.16699C11.667 3.47255 11.91 2.88227 12.3962 2.39616C12.8823 1.91005 13.4725 1.66699 14.167 1.66699C14.8614 1.66699 15.4517 1.91005 15.9378 2.39616C16.4239 2.88227 16.667 3.47255 16.667 4.16699C16.667 4.86144 16.4239 5.45171 15.9378 5.93782C15.4517 6.42394 14.8614 6.66699 14.167 6.66699C13.8337 6.66699 13.5212 6.60782 13.2295 6.48949C12.9378 6.37116 12.6809 6.2081 12.4587 6.00032L6.60449 9.41699C6.63227 9.51421 6.64977 9.6081 6.65699 9.69866C6.66421 9.78921 6.66755 9.88977 6.66699 10.0003C6.66644 10.1109 6.6631 10.2117 6.65699 10.3028C6.65088 10.3939 6.63338 10.4875 6.60449 10.5837L12.4587 14.0003C12.6809 13.792 12.9378 13.6289 13.2295 13.5112C13.5212 13.3934 13.8337 13.3342 14.167 13.3337C14.8614 13.3337 15.4517 13.5767 15.9378 14.0628C16.4239 14.5489 16.667 15.1392 16.667 15.8337C16.667 16.5281 16.4239 17.1184 15.9378 17.6045C15.4517 18.0906 14.8614 18.3337 14.167 18.3337Z"
                                fill="#2AAC7E">
                            </path>
                        </svg>
                    </div>

                    <div class="download-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.99967 13.333L5.83301 9.16634L6.99967 7.95801L9.16634 10.1247V3.33301H10.833V10.1247L12.9997 7.95801L14.1663 9.16634L9.99967 13.333ZM4.99967 16.6663C4.54134 16.6663 4.14912 16.5033 3.82301 16.1772C3.4969 15.8511 3.33356 15.4586 3.33301 14.9997V12.4997H4.99967V14.9997H14.9997V12.4997H16.6663V14.9997C16.6663 15.458 16.5033 15.8505 16.1772 16.1772C15.8511 16.5038 15.4586 16.6669 14.9997 16.6663H4.99967Z"
                                fill="white">
                            </path>
                        </svg>
                    </div>
                </div> --}}
            </div>

            <div class="dashboard-dividerr"></div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

@endsection
