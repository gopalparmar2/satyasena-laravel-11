@extends('admin.layouts.app')

@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif

@section('styles')
    @parent
    <link href="{{ asset('assets/libs/dataTables/dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3 controls">
                                <label for="state_id" class="form-label">State</label>
                                <select name="state_id" id="state_id" class="select2 form-select">
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3 controls">
                                <label for="assembly_id" class="form-label">Assembly Constituency (AC)</label>
                                <select name="assembly_id" id="assembly_id" class="select2 form-select">
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3 controls">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-control select2" name="priority" id="priority">
                                    <option value="">Select Status</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3 controls">
                                <label for="fltStatus" class="form-label">Status</label>
                                <select class="form-control select2" name="fltStatus" id="fltStatus">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" class="form-control date" name="date" id="date"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary w-md button-responsive"
                                onclick="createDataTable()">Filter</button>
                            <button type="submit" class="btn btn-secondary w-md button-responsive"
                                onclick="resetFilter()">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>STATE</th>
                                        <th>ASSEMBLY CONSTITUENCY</th>
                                        <th>VILLAGE</th>
                                        <th>PRIORITY</th>
                                        <th>STATUS</th>
                                        <th>CREATED AT</th>
                                        @if (auth()->user()->can('village-edit') || auth()->user()->can('village-delete'))
                                            <th>ACTION</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('assets/libs/dataTables/dataTables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table;
            let url = "{!! route('admin.village.datatable') !!}";

            customDateRangePicker('#date');

            let columns = [
                { data: 'id', name: 'id' },
                { data: 'state', name: 'state' },
                { data: 'assembly', name: 'assembly' },
                { data: 'name', name: 'name' },
                { data: 'priority', name: 'priority' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                @if (auth()->user()->can('village-edit') || auth()->user()->can('village-delete'))
                    { data: 'action', name: 'action' },
                @endif
            ];

            let sortingFalse = [];
            @if (auth()->user()->can('village-edit') || auth()->user()->can('village-delete'))
                sortingFalse = [7];
            @endif

            createDataTable(url, columns, ['state_id', 'assembly_id', 'priority', 'fltStatus', 'date'], sortingFalse);
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
                url: "{!! route('ajax.get_assembly') !!}",
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
            placeholder: 'Select Assembly Constituency (AC)',
        });
    </script>
@endsection
