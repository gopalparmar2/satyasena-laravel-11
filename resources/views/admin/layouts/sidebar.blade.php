<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.index') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @canany(['user-list', 'role-list', 'permission-list'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-user"></i>
                            <span key="t-layouts"> Users Management </span>
                        </a>

                        <ul class="sub-menu" aria-expanded="true">
                            @can('role-list')
                                <li {{ (\Route::is('admin.role.index') || \Route::is('admin.role.edit') || \Route::is('admin.role.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.role.index') }}" key="t-light-sidebar"> Roles </a>
                                </li>
                            @endcan

                            @can('permission-list')
                                <li {{ (\Route::is('admin.permission.index') || \Route::is('admin.permission.edit') || \Route::is('admin.permission.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.permission.index') }}" key="t-light-sidebar"> Permissions </a>
                                </li>
                            @endcan

                            @can('user-list')
                                <li {{ (\Route::is('admin.user.index') || \Route::is('admin.user.edit') || \Route::is('admin.user.create') || \Route::is('admin.user.view')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.user.index') }}" key="t-light-sidebar"> Users </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['category-list', 'profession-list', 'religion-list', 'state-list', 'district-list', 'assembly-constituency-list', 'caste-list', 'blood-group-list'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-grid-alt"></i>
                            <span key="t-layouts"> Master Management </span>
                        </a>

                        <ul class="sub-menu" aria-expanded="true">
                            @can('category-list')
                                <li {{ (\Route::is('admin.category.index') || \Route::is('admin.category.edit') || \Route::is('admin.category.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.category.index') }}" key="t-light-sidebar"> Category </a>
                                </li>
                            @endcan

                            @can('profession-list')
                                <li {{ (\Route::is('admin.profession.index') || \Route::is('admin.profession.edit') || \Route::is('admin.profession.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.profession.index') }}" key="t-light-sidebar"> Profession </a>
                                </li>
                            @endcan

                            {{-- @can('education-list')
                                <li {{ (\Route::is('admin.education.index') || \Route::is('admin.education.edit') || \Route::is('admin.education.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.education.index') }}" key="t-light-sidebar"> Education </a>
                                </li>
                            @endcan --}}

                            @can('religion-list')
                                <li {{ (\Route::is('admin.religion.index') || \Route::is('admin.religion.edit') || \Route::is('admin.religion.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.religion.index') }}" key="t-light-sidebar"> Religion </a>
                                </li>
                            @endcan

                            @can('state-list')
                                <li {{ (\Route::is('admin.state.index') || \Route::is('admin.state.edit') || \Route::is('admin.state.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.state.index') }}" key="t-light-sidebar"> State </a>
                                </li>
                            @endcan

                            @can('district-list')
                                <li {{ (\Route::is('admin.district.index') || \Route::is('admin.district.edit') || \Route::is('admin.district.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.district.index') }}" key="t-light-sidebar"> District </a>
                                </li>
                            @endcan

                            @can('assembly-constituency-list')
                                <li {{ (\Route::is('admin.assemblyConstituency.index') || \Route::is('admin.assemblyConstituency.edit') || \Route::is('admin.assemblyConstituency.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.assemblyConstituency.index') }}" key="t-light-sidebar"> Assembly Constituency </a>
                                </li>
                            @endcan

                            @can('village-list')
                                <li {{ (\Route::is('admin.village.index') || \Route::is('admin.village.edit') || \Route::is('admin.village.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.village.index') }}" key="t-light-sidebar"> Village </a>
                                </li>
                            @endcan

                            @can('booth-list')
                                <li {{ (\Route::is('admin.booth.index') || \Route::is('admin.booth.edit') || \Route::is('admin.booth.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.booth.index') }}" key="t-light-sidebar"> Booth </a>
                                </li>
                            @endcan

                            @can('mandal-list')
                                <li {{ (\Route::is('admin.mandal.index') || \Route::is('admin.mandal.edit') || \Route::is('admin.mandal.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.mandal.index') }}" key="t-light-sidebar"> Mandal </a>
                                </li>
                            @endcan

                            @can('pincode-list')
                                <li {{ (\Route::is('admin.pincode.index') || \Route::is('admin.pincode.edit') || \Route::is('admin.pincode.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.pincode.index') }}" key="t-light-sidebar"> Pincode </a>
                                </li>
                            @endcan

                            @can('zila-list')
                                <li {{ (\Route::is('admin.zila.index') || \Route::is('admin.zila.edit') || \Route::is('admin.zila.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.zila.index') }}" key="t-light-sidebar"> Zila </a>
                                </li>
                            @endcan

                            @can('relationship-list')
                                <li {{ (\Route::is('admin.relationship.index') || \Route::is('admin.relationship.edit') || \Route::is('admin.relationship.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.relationship.index') }}" key="t-light-sidebar"> Relationship </a>
                                </li>
                            @endcan

                            @can('caste-list')
                                <li {{ (\Route::is('admin.caste.index') || \Route::is('admin.caste.edit') || \Route::is('admin.caste.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.caste.index') }}" key="t-light-sidebar"> Caste </a>
                                </li>
                            @endcan

                            @can('blood-group-list')
                                <li {{ (\Route::is('admin.bloodgroup.index') || \Route::is('admin.bloodgroup.edit') || \Route::is('admin.bloodgroup.create')) ? 'class=mm-active' : '' }}>
                                    <a href="{{ route('admin.bloodgroup.index') }}" key="t-light-sidebar"> Blood Group </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>
    </div>
</div>
