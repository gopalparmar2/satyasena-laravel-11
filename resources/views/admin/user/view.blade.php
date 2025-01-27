@extends('admin.layouts.app')
@if (isset($page_title) && $page_title != '')
    @section('title', $page_title . ' | ' . config('app.name'))
@else
    @section('title', config('app.name'))
@endif
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <th class="text-center" colspan="2" style="font-weight: bold;">Personal Details</th>
                                    <tr>
                                        <th class="th-width">User Image</th>
                                        <td>
                                            @if ($user->image != '' && \File::exists(public_path('uploads/users/' . $user->image)))
                                                <img src="{{ asset('uploads/users/' . $user->image) }}" alt="User Image" style="height: 100px; width: 100px;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">User Name</th>
                                        <td>{{ $user->name ? ucfirst($user->salutation).'. '.$user->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Email</th>
                                        <td>{{ $user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Mobile Number</th>
                                        <td>{{ $user->mobile_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">D.O.B. | Age</th>
                                        <td>{{ $user->dob ? date('d/m/Y', strtotime($user->dob)) : '-' }} | {{ $user->age ?  $user->age.' Yrs' : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Gender</th>
                                        @php
                                            $gender = '-';

                                            if ($user->gender == 1) {
                                                $gender = 'Female';
                                            } elseif ($user->gender == 2) {
                                                $gender = 'Male';
                                            } elseif ($user->gender == 3) {
                                                $gender = 'Other';
                                            }
                                        @endphp

                                        <td>{{ $gender }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Blood Group</th>
                                        <td>{{ $user->bloodGroup ? $user->bloodGroup->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Religion</th>
                                        <td>{{ $user->religion ? $user->religion->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Category</th>
                                        <td>{{ $user->category ? $user->category->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Caste</th>
                                        <td>{{ $user->caste ? $user->caste->name : '-' }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th class="th-width">Education</th>
                                        <td>{{ $user->education ? $user->education->name : '-' }}</td>
                                    </tr> --}}
                                    <tr>
                                        <th class="th-width">Profession</th>
                                        <td>{{ $user->profession ? $user->profession->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">WhatsApp / Alternative Namber</th>
                                        <td>{{ $user->whatsapp_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Father / Spouse name</th>
                                        <td>{{ $user->relationship_name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <th class="text-center" colspan="2" style="font-weight: bold;">Address Details</th>
                                    <tr>
                                        <th class="th-width">Address</th>
                                        <td>{{ $user->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Pincode</th>
                                        <td>{{ $user->pincode ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">State</th>
                                        <td>{{ $user->state ? $user->state->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">District</th>
                                        <td>{{ $user->district ? $user->district->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Assembly constituency (AC)</th>
                                        <td>{{ $user->assemblyConstituency ? $user->assemblyConstituency->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Village</th>
                                        <td>{{ $user->village ? $user->village->name : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-6 mt-5">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <th class="text-center" colspan="2" style="font-weight: bold;">Karyakarta Details</th>
                                    <tr>
                                        <th class="th-width">Landline Number</th>
                                        <td>{{ $user->landline_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Zila</th>
                                        <td>{{ $user->zila ? $user->zila->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Mandal</th>
                                        <td>{{ $user->mandal ? $user->mandal->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Ward Name</th>
                                        <td>{{ $user->ward_id ? 'Ward '.$user->ward_id : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="th-width">Booth</th>
                                        <td>{{ $user->booth ? $user->booth->name : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($user->familyMembers->count() > 0)
                            @foreach ($user->familyMembers as $key => $familyMember)
                                <div class="col-6 mt-5">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-striped">
                                            <th class="text-center" colspan="2" style="font-weight: bold;">Family Member Details {{ $key + 1 }}</th>
                                            <tr>
                                                <th class="th-width">Full Name</th>
                                                <td>{{ $familyMember->first_name.' '.$familyMember->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="th-width">Relationship</th>
                                                <td>{{ $familyMember->relationship ? $familyMember->relationship->name : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="th-width">D.O.B. | Age</th>
                                                <td>{{ $familyMember->dob ? date('d/m/Y', strtotime($familyMember->dob)) : '-' }} | {{ $familyMember->age ?  $familyMember->age.' Yrs' : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="th-width">Mobile Number</th>
                                                <td>{{ $familyMember->mobile_number ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
