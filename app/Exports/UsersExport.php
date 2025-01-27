<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\User;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request) {
        $this->request = $request->all();
    }

    public function headings():array {
        return [
            'Full Name',
            'Email',
            'Mobile Number',
            'D.O.B',
            'Age',
            'Gender',
            'Education',
            'Blood Group',
            'Religion',
            'Category',
            'Caste',
            'Profession',
            'WhatsApp / Alternative Namber',
            'Father / Spouse name',
            'Pincode',
            'State',
            'District',
            'Assembly Constituency',
            'Village',
            'Landline Number',
            'Zila',
            'Mandal',
            'Ward Name',
            'Booth',
        ];
    }

    public function map($row): array {
        $fullName = $row->name;
        if ($row->salutation != '') {
            $fullName .= ucfirst($row->salutation).'. '.$row->name;
        }

        $gender = '';
        if ($row->gender == 1) {
            $gender = 'Female';
        } elseif ($row->gender == 2) {
            $gender = 'Male';
        } elseif ($row->gender == 3) {
            $gender = 'Other';
        }

        $fields = [
            $fullName,
            $row->email,
            $row->mobile_number,
            ($row->dob != '') ? date('d-m-Y', strtotime($row->dob)) : '',
            $row->age,
            $gender,
            $row->education ? $row->education->name : '',
            $row->bloodGroup ? $row->bloodGroup->name : '',
            $row->religion ? $row->religion->name : '',
            $row->category ? $row->category->name : '',
            $row->caste ? $row->caste->name : '',
            $row->profession ? $row->profession->name : '',
            $row->whatsapp_number,
            $row->relationship_name,
            $row->pincode,
            $row->state ? $row->state->name : '',
            $row->district ? $row->district->name : '',
            $row->assemblyConstituency ? $row->assemblyConstituency->name : '',
            $row->village ? $row->village->name : '',
            $row->landline_number,
            $row->zila ? $row->zila->name : '',
            $row->mandal ? $row->mandal->name : '',
            $row->ward_id ? 'Ward '.$row->ward_id : '',
            $row->booth ? $row->booth->name : '',
        ];

        return $fields;
    }

    public function styles(Worksheet $sheet) {
        return [
           1    => ['font' => ['bold' => true]],
        ];
    }

    public function collection() {
        $user = User::where('id', '!=', 1);
        $request = $this->request;

        if ($request['name'] != '') {
            $name = $request['name'];
            $user->where('name', 'like', '%'.$name.'%');
        }

        if ($request['village_id'] != '') {
            $village_id = $request['village_id'];
            $user->where('village_id', $village_id);
        }

        if ($request['caste_id'] != '') {
            $caste_id = $request['caste_id'];
            $user->where('caste_id', $caste_id);
        }

        if ($request['last_name'] != '') {
            $last_name = $request['last_name'];
            $user->where('last_name', 'like', '%'.$last_name.'%');
        }

        if ($request['business_name'] != '') {
            $business_name = $request['business_name'];
            $user->whereHas('profession', function ($query) use ($business_name) {
                $query->where('name', 'like', '%'.$business_name.'%');
            });
        }

        if ($request['mobile_number'] != '') {
            $mobile_number = $request['mobile_number'];

            $user->where('mobile_number', $mobile_number);
        }

        if ($request['fltStatus'] != '') {
            $user->where('status', $request['fltStatus']);
        }

        if ($request['date'] != '') {
            $date = explode(' - ', $request['date']);
            $from_date = date('Y-m-d', strtotime($date[0]));
            $to_date = date('Y-m-d', strtotime($date[1]));

            if ($from_date == $to_date) {
                $user->whereDate('created_at', $from_date);
            } else {
                $user->whereBetween('created_at', [$from_date, $to_date]);
            }
        }

        if ($request['is_mobile_number'] != '') {
            if ($request['is_mobile_number'] == 1) {
                $user->whereNotNull('mobile_number');
            } else {
                $user->whereNull('mobile_number');
            }
        }

        return $user->get();
    }
}
