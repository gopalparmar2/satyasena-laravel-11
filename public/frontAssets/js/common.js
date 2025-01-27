const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const currentUrl = window.location.href;

$('.numbers_only').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g, '');
});

$(document).on('click', '.chip', function() {
    $(".chip").removeClass("selected");
    $(this).addClass('selected');

    const gender = $(this).data('value');
    $('#gender').val(gender);

    if (currentUrl.includes("user-details")) {
        checkFormValues();
    }
});

$(document).on('click', '.dropdown', function() {
    isClicked = $(this).find('svg').hasClass('css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon');

    if (isClicked) {
        $(this).find('svg').removeClass('css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon');
        $(this).find('svg').addClass('MuiSelect-iconOpen css-1mf6u8l-MuiSvgIcon-root-MuiSelect-icon');

        $(this).prev('label').removeClass('css-aqpgxn-MuiFormLabel-root-MuiInputLabel-root');
        $(this).prev('label').addClass(
            'MuiInputLabel-shrink Mui-focused css-1c2i806-MuiFormLabel-root-MuiInputLabel-root');

        $(this).addClass('Mui-focused');
    } else {
        $(this).find('svg').removeClass('MuiSelect-iconOpen css-1mf6u8l-MuiSvgIcon-root-MuiSelect-icon');
        $(this).find('svg').addClass('css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon');

        $(this).prev('label').removeClass(
            'MuiInputLabel-shrink Mui-focused css-1c2i806-MuiFormLabel-root-MuiInputLabel-root');
        $(this).prev('label').addClass('css-aqpgxn-MuiFormLabel-root-MuiInputLabel-root');
        $(this).removeClass('Mui-focused');
    }
});

// $(document).on('keyup', '#pincode', function() {
//     const pincode = $(this).val();
//     const url = $(this).data('url');

//     if (pincode.length == 6) {
//         $.ajax({
//             type: "POST",
//             url: url,
//             data: {
//                 "_token": $('meta[name="csrf-token"]').attr('content'),
//                 "pincode": pincode
//             },
//             dataType: "json",
//             success: function(response) {
//                 if (response.success) {
//                     $('.stateOrDistrict').find('svg').removeClass('css-pqjvzy-MuiSvgIcon-root-MuiSelect-icon');
//                     $('.stateOrDistrict').find('svg').addClass('MuiSelect-iconOpen css-1mf6u8l-MuiSvgIcon-root-MuiSelect-icon');

//                     $('.stateOrDistrict').prev('label').removeClass('css-aqpgxn-MuiFormLabel-root-MuiInputLabel-root');
//                     $('.stateOrDistrict').prev('label').addClass('MuiInputLabel-shrink Mui-focused css-1c2i806-MuiFormLabel-root-MuiInputLabel-root');

//                     $('.stateOrDistrict').addClass('Mui-focused');

//                     $('#state').val(response.stateId);
//                     $('#divStateName').html(response.stateName);
//                     $('#state').addClass('d-none');

//                     $('#district').val(response.districtId);
//                     $('#divDistrictName').html(response.districtName);
//                     $('#district').addClass('d-none');

//                     $('#assembly_constituency').val('');
//                     $('#assembly_constituency').removeClass('d-none');
//                     $('#divAssemblyName').html('');

//                     $('#districtUl').html(response.districtHtml);
//                     $('#assemblyUl').html(response.assemblyHtml);

//                     if (currentUrl.includes("user-details")) {
//                         checkFormValues();
//                     }
//                 }
//             }
//         });
//     }
// });

$('#dob').datepicker({
    dateFormat: 'dd-mm-yy',
    endDate: '-18y',
    autoclose: true
}).on("change", function() {
    var selectedDate = $(this).datepicker("getDate");

    if (selectedDate) {
        var today = new Date();
        var age = today.getFullYear() - selectedDate.getFullYear();
        var monthDifference = today.getMonth() - selectedDate.getMonth();
        var dayDifference = today.getDate() - selectedDate.getDate();

        if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            age--;
        }

        $('#age').val(age + ' Yrs');
    }
});

$('#member_dob').datepicker({
    dateFormat: 'dd/mm/yy',
    endDate: '-1y',
    autoclose: true
}).on("change", function() {
    var selectedDate = $(this).datepicker("getDate");

    if (selectedDate) {
        var today = new Date();
        var age = today.getFullYear() - selectedDate.getFullYear();
        var monthDifference = today.getMonth() - selectedDate.getMonth();
        var dayDifference = today.getDate() - selectedDate.getDate();

        if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            age--;
        }

        $('#member_age').val(age + ' Yrs');
    }
});

$('.MuiFilledInput-input').on('focus', function() {
    const label = $(this).closest('.MuiFormControl-root').find('.MuiFormLabel-root');
    const inputParentDiv = $(this).closest('.MuiInputBase-root');

    label.addClass('MuiInputLabel-shrink Mui-focused css-o943dk-MuiFormLabel-root-MuiInputLabel-root')
        .removeClass('css-e4w4as-MuiFormLabel-root-MuiInputLabel-root');
    inputParentDiv.addClass('Mui-focused');
});

$('.MuiFilledInput-input').on('blur', function() {
    let inputVal = $(this).val();

    if (inputVal == '') {
        const label = $(this).closest('.MuiFormControl-root').find('.MuiFormLabel-root');
        const inputParentDiv = $(this).closest('.MuiInputBase-root');

        label.removeClass('MuiInputLabel-shrink Mui-focused css-o943dk-MuiFormLabel-root-MuiInputLabel-root')
            .addClass('css-e4w4as-MuiFormLabel-root-MuiInputLabel-root');
        inputParentDiv.removeClass('Mui-focused');
    }
});

$(document).on('click', '.pp-upload-btn', function() {
    $('#uploadProfilePicPopup').removeClass('d-none');
});

$(document).on('click', '.cancel-label', function() {
    $('#uploadProfilePicPopup').addClass('d-none');
});

$(document).on('click', '#btnPreviewCancel', function() {
    $('#profile-img').attr('src', '');
    $('#previewUploadedImg').addClass('d-none');
    $('#uploadProfilePicPopup').removeClass('d-none');
});

$(document).on('change', '#file-upload-gallery', function() {
    if (this.files && this.files[0]) {
        $('#uploadProfilePicPopup').addClass('d-none');

        var reader = new FileReader();

        reader.onload = function(e) {
            $('#profile-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

        $('#previewUploadedImg').removeClass('d-none');
    }
});

$(document).on('click', '#btnUploadImage', function() {
    let formData = new FormData();
    formData.append('image', $('#file-upload-gallery')[0].files[0]);
    const imgUploadUrl = $(this).data('img-upload-url');
    $('#uploadImgSvg').addClass('d-none');
    $('#uploadImgBtnLoader').removeClass('d-none');

    $.ajax({
        type: "POST",
        url: imgUploadUrl,
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                let imgHrml =
                    '<div class="MuiAvatar-root MuiAvatar-circular css-11fq0lf-MuiAvatar-root"> <img src="' +
                    response.image +
                    '" alt="Profile Image" class="MuiAvatar-img css-1pqm26d-MuiAvatar-img"> </div>';

                $('.profile-pic-container').html(imgHrml);
                $('#profilePhotoLabel').html('Profile Photo');
            } else {
                setTimeout(function() {
                    Toast.fire({ icon: 'error', title: response.message });
                }, 2000);
            }

            $('#profile-img').attr('src', '');
            $('#previewUploadedImg').addClass('d-none');

            $('#uploadImgSvg').removeClass('d-none');
            $('#uploadImgBtnLoader').addClass('d-none');
        }
    });
});

$(document).on('keyup change', '#email', function() {
    const emailErr = $('#emailErr').hasClass('d-none');
    const email = $(this).val();

    if (email == '' && !emailErr) {
        $('#emailErr').addClass('d-none');
    }

    if (email != '') {
        if (isValidEmail(email)) {
            $('#emailErr').addClass('d-none');
        } else {
            $('#emailErr').removeClass('d-none');
        }
    }

    if (currentUrl.includes("user-details")) {
        checkFormValues();
    }
});

function isValidEmail(email) {
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

$(document).on('click', '.span-close', function() {
    const modal = $(this).data('modal');
    $('#menu-' + modal).addClass('d-none');
    $('.searchInput').val('');

    // getCommonValues(modal);
});

$(document).on('click', '.optionLi', function() {
    $('.searchInput').val('');

    const searchType = $(this).data('li-type');

    getCommonValues(searchType);
});

$(document).on('keyup', '.searchInput', function() {
    const searchType = $(this).data('search-type');
    const searchValue = $(this).val();

    getCommonValues(searchType, searchValue);
});

$(document).on('click', '.clearSearch', function() {
    const searchType = $(this).data('search-type');

    $('#search-'+searchType).val('');

    getCommonValues(searchType);
});

function getCommonValues(searchType, searchValue = '') {
    let stateId = '';
    let zilaId = '';
    let assemblyId = '';

    if (searchType != 'state') {
        stateId = $('#state').val();
    }

    if (searchType == 'mandal') {
        // zilaId = $('#zila_id').val();
        zilaId = 431;
    }

    if (searchType == 'booth') {
        assemblyId = $('#assembly_constituency').val();
        $('#boothUl').html('');
        $('#boothUl').html('<p style="text-align: center;" id="boothLoad">Loading...</p>');
    }

    if (searchType == 'village') {
        assemblyId = $('#assembly_constituency').val();
    }

    $.ajax({
        type: "POST",
        url: $('#commonSearchUrl').val(),
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            keyword: searchValue,
            type: searchType,
            stateId: stateId,
            zilaId: zilaId,
            assemblyId: assemblyId
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                $('#'+searchType+'Ul').html(response.html);
            } else {
                $('#boothLoad').html('No data found');
            }
        }
    });
}
