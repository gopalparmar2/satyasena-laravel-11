<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AjaxController;

use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\AssemblyConstituencyController;
use App\Http\Controllers\Admin\VillageController;
use App\Http\Controllers\Admin\ProfessionController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ReligionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BoothController;
use App\Http\Controllers\Admin\MandalController;
use App\Http\Controllers\Admin\PincodeController;
use App\Http\Controllers\Admin\ZilaController;
use App\Http\Controllers\Admin\RelationshipController;
use App\Http\Controllers\Admin\CasteController;
use App\Http\Controllers\Admin\BloodGroupController;

use App\Http\Controllers\Front\HomeController as FrontHomeController;

Auth::routes();

Route::controller(AjaxController::class)->group(function () {
    Route::group(['as' => 'ajax.', 'prefix' => 'ajax'], function() {
        Route::get('/get-states', 'getStates')->name('get_states');
        Route::get('/get-all-states', 'getAllStates')->name('get_all_states');
        Route::post('/get-districts-and-assemblies', 'getDistrictAndAssemblies')->name('get_districts_and_assemblies');
        Route::post('/get-pincode-details', 'getPincodeDetails')->name('get_pincode_details');
        Route::post('/check-referral-code', 'checkReferralCode')->name('check.referral.code');
        Route::post('/get-zilas', 'getZilas')->name('get_zilas');
        Route::post('/get-villages', 'getVillages')->name('get_villages');
        Route::post('/get-mandals', 'getMandals')->name('get_mandals');
        Route::post('/get-booths', 'getBooths')->name('get_booths');

        Route::get('/get-assembly', 'getAssemblaies')->name('get_assembly');
        Route::get('/get-zilas', 'getZilasDD')->name('get_zila_dd');
        Route::get('/get-mandals', 'getMandalDD')->name('get_mandal_dd');
        Route::get('/get-districts', 'getDistricts')->name('get_districts');
        Route::get('/get-villages', 'getVillageDD')->name('get_village_dd');
        Route::get('/get-booths', 'getBoothDD')->name('get_booth_dd');
        Route::post('/get-pincode-data', 'getPincodeData')->name('get_pincode_data');
        Route::post('/get-search-options', 'getSearchOptions')->name('get_search_options');
    });
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    Auth::routes();

    Route::group(['middleware' => ['auth', 'isAdmin']], function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('index');
        Route::post('/get-color-chart-data', [AdminHomeController::class, 'getColorChartData'])->name('get.color.chart.data');
        Route::post('/get-caste-chart-data', [AdminHomeController::class, 'getCasteChartData'])->name('get.caste.chart.data');
        Route::post('/get-surname-chart-data', [AdminHomeController::class, 'getSurnameChartData'])->name('get.surname.chart.data');

        Route::controller(ProfileController::class)->group(function () {
            Route::group(['as' => 'profile.', 'prefix' => 'profile'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/update', 'update')->name('update');
                Route::post('/password/update', 'password_update')->name('password.update');
                Route::post('/check_password', 'check_password')->name('check.password');
            });
        });

        Route::controller(PermissionController::class)->group(function () {
            Route::group(['as' => 'permission.', 'prefix' => 'permission'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(RoleController::class)->group(function () {
            Route::group(['as' => 'role.', 'prefix' => 'role'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(AdminUserController::class)->group(function () {
            Route::group(['as' => 'user.', 'prefix' => 'user'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/view/{id}', 'view')->name('view');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
                Route::post('/exists', 'exists')->name('exists');
                Route::post('/export', 'export')->name('export');
            });
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::group(['as' => 'category.', 'prefix' => 'category'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(ProfessionController::class)->group(function () {
            Route::group(['as' => 'profession.', 'prefix' => 'profession'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(EducationController::class)->group(function () {
            Route::group(['as' => 'education.', 'prefix' => 'education'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(ReligionController::class)->group(function () {
            Route::group(['as' => 'religion.', 'prefix' => 'religion'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(StateController::class)->group(function () {
            Route::group(['as' => 'state.', 'prefix' => 'state'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(DistrictController::class)->group(function () {
            Route::group(['as' => 'district.', 'prefix' => 'district'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(AssemblyConstituencyController::class)->group(function () {
            Route::group(['as' => 'assemblyConstituency.', 'prefix' => 'assembly-constituency'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(VillageController::class)->group(function () {
            Route::group(['as' => 'village.', 'prefix' => 'village'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(BoothController::class)->group(function () {
            Route::group(['as' => 'booth.', 'prefix' => 'booth'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(MandalController::class)->group(function () {
            Route::group(['as' => 'mandal.', 'prefix' => 'mandal'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(PincodeController::class)->group(function () {
            Route::group(['as' => 'pincode.', 'prefix' => 'pincode'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(ZilaController::class)->group(function () {
            Route::group(['as' => 'zila.', 'prefix' => 'zila'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(RelationshipController::class)->group(function () {
            Route::group(['as' => 'relationship.', 'prefix' => 'relationship'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(CasteController::class)->group(function () {
            Route::group(['as' => 'caste.', 'prefix' => 'caste'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });

        Route::controller(BloodGroupController::class)->group(function () {
            Route::group(['as' => 'bloodgroup.', 'prefix' => 'blood-group'], function() {
                Route::get('/', 'index')->name('index');
                Route::post('/datatable', 'datatable')->name('datatable');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/destroy', 'destroy')->name('destroy');
                Route::post('/change_status', 'change_status')->name('change.status');
            });
        });
    });
});

Route::group(['as' => 'front.'], function () {
    Route::controller(FrontHomeController::class)->group(function () {
        Route::group(['middleware' => ['auth', 'checkProfileCompletion']], function () {
            Route::get('/', 'index')->name('index');

            Route::get('/verify-otp', 'showVerifyOtpForm')->name('show.verify.otp.form');
            Route::post('/verify-otp', 'verifyOtp')->name('verify.otp');

            Route::get('/user-details', 'showUserDetailsForm')->name('show.user.details.form');
            Route::post('/user-details', 'storeUserDetails')->name('store.user.details');
            Route::post('/update-user-image', 'updateUserImage')->name('update.user.image');

            Route::get('/update-details', 'showUpdateDetailsForm')->name('show.update.details.form');
            Route::post('/update-details', 'updateDetails')->name('update.details');

            Route::post('/store-family-member-details', 'storeFamilyMemberDetails')->name('store.family.member.details');
            Route::post('/delete-family-member', 'deleteFamilyMember')->name('delete.family.member');
            Route::post('/get-family-member', 'getFamilyMember')->name('get.family.member');

            Route::post('/store-contact-details', 'storeContactDetails')->name('store.contact.details');

            Route::get('/referral', 'referral')->name('refferal');
            Route::get('/thank-you', 'thankYou')->name('thankyou');
        });
    });
});
