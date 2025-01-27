<div class="d-flex align-style align-items-center secondary-container fixed-position">
    <div class="d-flex align-items-center " style="column-gap: 6px;">
        <img src="{{ asset('frontAssets/imgs/logo.png') }}" alt="logo" height="30px;">

        <p class="m-0 bjp-text">{{ strtoupper(config('app.name')) }}</p>
    </div>

    @if (auth()->user())
        <div class="header-wrapper">
            <div class="dropdown-container">
                <button class="css-1pe4mpk-MuiButtonBase-root-MuiIconButton-root" tabindex="0" type="button"
                    aria-haspopup="true" aria-label="Change Language" data-mui-internal-clone-element="true">
                    <span class="css-8je8zh-MuiTouchRipple-root"></span>
                </button>

                <p class="selected-language">en</p>
            </div>

            <div class="logout-icon" onclick="event.preventDefault(); document.getElementById('frmLogout').submit();">
                <svg class="css-i4bv87-MuiSvgIcon-root" focusable="false" aria-hidden="true" viewBox="0 0 24 24"
                    data-testid="LogoutIcon">
                    <path
                        d="m17 7-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4z">
                    </path>
                </svg>
            </div>

            <form id="frmLogout" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    @endif
</div>
