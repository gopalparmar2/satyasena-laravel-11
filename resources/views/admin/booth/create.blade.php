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
                    <form action="{{ route('admin.booth.store') }}" name="addfrm" id="addfrm" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        @isset($booth)
                            <input type="hidden" name="booth_id" id="booth_id" value="{{ $booth->id }}">
                        @endisset

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 controls">
                                    <label class="form-label">Select State <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="state_id" id="state_id">
                                        @if (isset($booth) && isset($booth->assembly) && isset($booth->assembly->state))
                                            <option value="{{ $booth->assembly->state->id }}" selected>{{ $booth->assembly->state->name }}</option>
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
                                    <label class="form-label">Select Assembly Constituency <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="assembly_id" id="assembly_id">
                                        @if (isset($booth) && isset($booth->assembly))
                                            <option value="{{ $booth->assembly->id }}" selected>{{ $booth->assembly->name }}</option>
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
                                <div class="mb-3">
                                    <label class="form-label @error('name') is-invalid @enderror">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', isset($booth) ? $booth->name : '') }}">
                                    @error('name')
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
                                        <input type="checkbox" name="status" class="form-check-input" {{ isset($booth) && $booth->status === 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md button-responsive">Submit</button>
                            <a href="{{ route('admin.booth.index') }}" class="btn btn-secondary w-md button-responsive">Cancel</a>
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
                    assembly_id: {
                        required: true
                    },
                    name: {
                        required: true
                    }
                },
                messages: {
                    state_id: {
                        required: 'The state field is required.'
                    },
                    assembly_id: {
                        required: 'The assembly constituency field is required.'
                    },
                    name: {
                        required: 'The name field is required.'
                    }
                }
            });
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
            $('#assembly_id').attr('disabled', true);
            $("#assembly_id").val(null).trigger("change");

            if (this.value) {
                $('#assembly_id').attr('disabled', false);
            }
        });

        $('#assembly_id').select2({
            allowClear: true,
            ajax: {
                url: '{{ route('ajax.get_assembly') }}',
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
            placeholder: 'Select Assembly Constituency',
        });
    </script>
@endsection
