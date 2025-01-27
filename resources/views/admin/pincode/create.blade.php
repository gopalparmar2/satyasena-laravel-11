@extends('admin.layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('styles')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.pincode.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        @isset($pincode)
                            <input type="hidden" name="pincode_id" id="pincode_id" value="{{ $pincode->id }}">
                        @endisset

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select State <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="state_id" id="state_id">
                                        @if (isset($pincode) && isset($pincode->state))
                                            <option value="{{ $pincode->state->id }}" selected>{{ $pincode->state->name }}</option>
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
                                        @if (isset($pincode) && isset($pincode->district))
                                            <option value="{{ $pincode->district->id }}" selected>{{ $pincode->district->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('office_name') is-invalid @enderror">Office Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="office_name" id="office_name" value="{{ old('office_name', isset($pincode) ? $pincode->office_name : '') }}">
                                    @error('office_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('taluka') is-invalid @enderror">Taluka <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="taluka" id="taluka" value="{{ old('taluka', isset($pincode) ? $pincode->taluka : '') }}">
                                    @error('taluka')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label @error('pincode') is-invalid @enderror">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control numbers_only" name="pincode" id="pincode" value="{{ old('pincode', isset($pincode) ? $pincode->pincode : '') }}" maxlength="6">
                                    @error('pincode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" name="status" class="form-check-input" {{ isset($pincode) && $pincode->status === 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md button-responsive">Submit</button>
                            <a href="{{ route('admin.pincode.index') }}" class="btn btn-secondary w-md button-responsive">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('assets/libs/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/libs/validate/additional-methods.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#addfrm").validate({
                errorElement: "span",
                errorPlacement: function(label, element) {
                    label.addClass('errorMessage');

                    if (element.attr("type") == "radio" || element.hasClass('select2') || element.attr("name") == "description" || element.attr("type") == "checkbox") {
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
                    state_id: {
                        required: true
                    },
                    district_id: {
                        required: true
                    },
                    office_name: {
                        required: true
                    },
                    taluka: {
                        required: true
                    },
                    pincode: {
                        required: true
                    }
                },
                messages: {
                    state_id: {
                        required: 'The state field is required.'
                    },
                    district_id: {
                        required: 'The district field is required.'
                    },
                    office_name: {
                        required: 'The office name field is required.'
                    },
                    taluka: {
                        required: 'The taluka field is required.'
                    },
                    pincode: {
                        required: 'The pincode field is required.'
                    },
                }
            })
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

        $('#state_id').on('change', function(e) {
            let optionSelected = $("option:selected", this);
            $('#district_id').attr('disabled', true);
            $("#district_id").val(null).trigger("change");

            if (this.value) {
                $('#district_id').attr('disabled', false);
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
    </script>
@endsection
