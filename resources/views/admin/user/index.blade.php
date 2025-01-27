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
                    <form action="{{ route('admin.user.export') }}" method="POST" id="frmFilter">
                        @csrf

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="village_id" class="form-label">Village</label>
                                    <select class="form-control select2" name="village_id" id="village_id">
                                        <option value="">Select village</option>
                                        @foreach ($villages as $village)
                                            <option value="{{ $village->id }}">{{ $village->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="caste_id" class="form-label">Caste</label>
                                    <select class="form-control select2" name="caste_id" id="caste_id">
                                        <option value="">Select Caste</option>
                                        @foreach ($castes as $caste)
                                            <option value="{{ $caste->id }}">{{ $caste->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="last_name" class="form-label">Surname</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" name="business_name" id="business_name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3 controls">
                                    <label for="mobile_number" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control numbers_only" name="mobile_number" id="mobile_number" maxlength="10" autocomplete="off">
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
                                <div class="mb-3 controls">
                                    <label for="is_mobile_number" class="form-label">Is mobile number</label>
                                    <select class="form-control select2" name="is_mobile_number" id="is_mobile_number">
                                        <option value="">Select Status</option>
                                        <option value="1">With Mobile Number</option>
                                        <option value="0">Without Mobile Number</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" class="form-control date" name="date" id="date" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-primary w-md button-responsive" onclick="createDataTable()">Filter</button>
                                <button type="button" class="btn btn-secondary w-md button-responsive" onclick="resetFilter()">Clear</button>
                                <button type="submit" class="btn btn-success w-md button-responsive">Export</button>
                            </div>
                        </div>
                    </form>
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
                                        <th>IMAGE</th>
                                        <th>FULL NAME</th>
                                        <th>EMAIL</th>
                                        <th>MOBILE NUMBER</th>
                                        <th>D.O.B</th>
                                        <th>AGE</th>
                                        <th>GENDER</th>
                                        <th>CASTE</th>
                                        <th>BUSINESS NAME</th>
                                        <th>STATUS</th>
                                        <th>CREATED AT</th>
                                        @if (auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
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
            let url = '{!! route('admin.user.datatable') !!}';

            customDateRangePicker('#date');

            let columns = [
                { data: 'id', name: 'id' },
                { data: 'image', name: 'image' },
                { data: 'full_name', name: 'full_name' },
                { data: 'email', name: 'email' },
                { data: 'mobile_number', name: 'mobile_number' },
                { data: 'dob', name: 'dob' },
                { data: 'age', name: 'age' },
                { data: 'gender', name: 'gender' },
                { data: 'caste', name: 'caste' },
                { data: 'business_name', name: 'business_name' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                @if (auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                    { data: 'action', name: 'action' },
                @endif
            ];

            let sortingFalse = [1];
            @if (auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                sortingFalse = [1, 11];
            @endif

            createDataTable(url, columns, ['name', 'village_id', 'caste_id', 'last_name', 'business_name', 'mobile_number', 'fltStatus', 'is_mobile_number', 'date'], sortingFalse);
        });
    </script>
@endsection
