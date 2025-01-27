<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssemblyConstituency;
use App\Models\District;
use App\Models\Pincode;
use App\Models\Mandal;
use App\Models\State;
use App\Models\Zilla;
use App\Models\Booth;
use App\Models\User;
use App\Models\Village;

class AjaxController extends Controller
{
    public function getStates(Request $request) {
        $query = State::whereStatus(1);

        if ($request->search) {
            $query = $query->where('name', 'like', '%' .$request->search. '%');
        }

        $query = $query->orderBy('name', 'asc')->simplePaginate(50);

        $no = 0;
        $data = array();

        foreach($query as $item) {
            $data[$no]['id'] = $item->id;
            $data[$no]['text'] = $item->name;
            $no++;
        }

        $page = true;

        if (empty($query->nextPageUrl())) {
            $page = false;
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getAllStates() {
        try {
            $states = State::whereStatus(1)->orderBy('name', 'asc')->get();
            $resHtml = '';

            foreach ($states as $state) {
                $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi stateLi" data-li-type="state" tabindex="0" role="option" aria-selected="false" data-id="'.$state->id.'" data-name="'.$state->name.'" data-assembly-url="'.route('ajax.get_districts_and_assemblies').'"> '.$state->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['html'] = $resHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getZilas(Request $request) {
        try {
            $zilas = Zilla::where('state_id', $request->stateId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $resHtml = '';

            foreach ($zilas as $zila) {
                $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi zilaLi" data-li-type="zilla" tabindex="0" role="option" aria-selected="false" data-id="'.$zila->id.'" data-name="'.$zila->name.'"> '.$zila->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['html'] = $resHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getVillages(Request $request) {
        try {
            $villages = Village::whereStatus(1)->where('assembly_id', $request->assemblyId)->orderBy('priority', 'desc')->get();
            $resHtml = '';

            foreach ($villages as $village) {
                $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi villageLi" data-li-type="zilla" tabindex="0" role="option" aria-selected="false" data-id="'.$village->id.'" data-name="'.$village->name.'"> '.$village->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['html'] = $resHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getMandals(Request $request) {
        try {
            $mandals = Mandal::where('zilla_id', $request->zilaId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $resHtml = '';

            foreach ($mandals as $mandal) {
                $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi mandalLi" data-li-type="mandal" tabindex="0" role="option" aria-selected="false" data-id="'.$mandal->id.'" data-name="'.$mandal->name.'"> '.$mandal->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['html'] = $resHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getBooths(Request $request) {
        try {
            $booths = Booth::where('assembly_id', $request->assemblyId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $resHtml = '';

            foreach ($booths as $booth) {
                $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi boothLi" data-li-type="booth" tabindex="0" role="option" aria-selected="false" data-id="'.$booth->id.'" data-name="'.$booth->name.'"> '.$booth->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['html'] = $resHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getDistrictAndAssemblies(Request $request) {
        try {
            $districts = District::where('state_id', $request->stateId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $assemblies = AssemblyConstituency::where('state_id', $request->stateId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $zilas = Zilla::where('state_id', $request->stateId)->whereStatus(1)->orderBy('name', 'asc')->get();
            $districtHtml = '';
            $assemblyHtml = '';
            $zilaHtml = '';

            foreach ($districts as $district) {
                $districtHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi districtLi" data-li-type="district" tabindex="0" role="option" aria-selected="false" data-id="'.$district->id.'" data-name="'.$district->name.'"> '.$district->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            foreach ($assemblies as $assembly) {
                $assemblyHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi assemblyLi" data-li-type="assembly" tabindex="0" role="option" aria-selected="false" data-id="'.$assembly->id.'" data-name="'.$assembly->name.'"> '.$assembly->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            foreach ($zilas as $zila) {
                $zilaHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi zilaLi" data-li-type="zilla" tabindex="0" role="option" aria-selected="false" data-id="'.$zila->id.'" data-name="'.$zila->name.'"> '.$zila->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
            }

            $response['success'] = true;
            $response['districtHtml'] = $districtHtml;
            $response['assemblyHtml'] = $assemblyHtml;
            $response['zilaHtml'] = $zilaHtml;

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getPincodeDetails(Request $request) {
        try {
            $pincode = Pincode::whereStatus(1)->where('pincode', $request->pincode)->first();
            $response['success'] = false;

            if (isset($pincode)) {
                $response['success'] = true;
                $response['stateId'] = null;
                $response['stateName'] = null;
                $response['districtId'] = null;
                $response['districtName'] = null;

                if ($pincode->state) {
                    $response['stateId'] = $pincode->state->id;
                    $response['stateName'] = $pincode->state->name;
                }

                if ($pincode->district) {
                    $response['districtId'] = $pincode->district->id;
                    $response['districtName'] = $pincode->district->name;
                }

                $districts = District::where('state_id', $pincode->state->id)->whereStatus(1)->orderBy('name', 'asc')->get();
                $assemblies = AssemblyConstituency::where('state_id', $pincode->state->id)->whereStatus(1)->orderBy('name', 'asc')->get();
                $districtHtml = '';
                $assemblyHtml = '';

                foreach ($districts as $district) {
                    $districtHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi districtLi" data-li-type="district" tabindex="0" role="option" aria-selected="false" data-id="'.$district->id.'" data-name="'.$district->name.'"> '.$district->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
                }

                foreach ($assemblies as $assembly) {
                    $assemblyHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi assemblyLi" data-li-type="assembly" tabindex="0" role="option" aria-selected="false" data-id="'.$assembly->id.'" data-name="'.$assembly->name.'"> '.$assembly->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
                }

                $response['districtHtml'] = ($districtHtml != '') ? $districtHtml : null;
                $response['assemblyHtml'] = ($assemblyHtml != '') ? $assemblyHtml : null;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function checkReferralCode(Request $request) {
        try {
            if ($request->type == 'referral') {
                $user = User::whereStatus(1)->where('referral_code', $request->value)->first();
            } elseif ($request->type == 'mobile') {
                $user = User::whereStatus(1)->where('mobile_number', $request->value)->first();
            } else {
                $response['success'] = false;
            }

            if ($user) {
                $response['success'] = true;
                $response['user_id'] = $user->id;
                $response['referred_name'] = obfuscateName($user->name);
            } else {
                $response['success'] = false;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getAssemblaies(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('stateId')) {
            $query = AssemblyConstituency::whereStatus(1)->where('state_id', $request->stateId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('name', 'asc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getZilasDD(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('stateId')) {
            $query = Zilla::whereStatus(1)->where('state_id', $request->stateId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('name', 'asc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getDistricts(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('stateId')) {
            $query = District::whereStatus(1)->where('state_id', $request->stateId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('name', 'asc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getBoothDD(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('assemblyId')) {
            $query = Booth::whereStatus(1)->where('assembly_id', $request->assemblyId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('name', 'asc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getVillageDD(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('assemblyId')) {
            $query = Village::whereStatus(1)->where('assembly_id', $request->assemblyId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('priority', 'desc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getMandalDD(Request $request) {
        $no = 0;
        $data = array();
        $page = false;

        if ($request->has('zilaId')) {
            $query = Mandal::whereStatus(1)->where('zilla_id', $request->zilaId);

            if ($request->search) {
                $query = $query->where('name', 'like', '%' .$request->search. '%');
            }

            $query = $query->orderBy('name', 'asc')->simplePaginate(50);

            foreach($query as $item) {
                $data[$no]['id'] = $item->id;
                $data[$no]['text'] = $item->name;
                $no++;
            }

            if (!empty($query->nextPageUrl())) {
                $page = true;
            }
        }

        return ['results' => $data, 'pagination' => ['more' => $page]];
    }

    public function getPincodeData(Request $request) {
        try {
            $pincode = Pincode::whereStatus(1)->where('pincode', $request->pincode)->first();
            $response['success'] = false;

            if ($pincode) {
                $response['success'] = true;
                $response['stateId'] = null;
                $response['districtId'] = null;

                if ($pincode->state) {
                    $response['stateId'] = $pincode->state->id;
                }

                if ($pincode->district) {
                    $response['districtId'] = $pincode->district->id;
                }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }

    public function getSearchOptions(Request $request) {
        try {
            $response['success'] = false;
            $resHtml = '';
            $objects = '';

            if ($request->type == 'state') {
                $objects = State::whereStatus(1)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'district' && $request->stateId != '') {
                $objects = District::whereStatus(1)->where('state_id', $request->stateId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'assembly' && $request->stateId != '') {
                $objects = AssemblyConstituency::whereStatus(1)->where('state_id', $request->stateId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'zila' && $request->stateId != '') {
                $objects = Zilla::whereStatus(1)->where('state_id', $request->stateId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'mandal' && $request->zilaId != '') {
                $objects = Mandal::whereStatus(1)->where('zilla_id', $request->zilaId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'booth' && $request->assemblyId != '') {
                $objects = Booth::whereStatus(1)->where('assembly_id', $request->assemblyId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('name', 'asc')->get();
            } else if ($request->type == 'village' && $request->assemblyId != '') {
                $objects = Village::whereStatus(1)->where('assembly_id', $request->assemblyId)->where('name', 'like', '%'.$request->keyword.'%')->orderBy('priority', 'desc')->get();
            }

            if ($objects != '') {
                foreach ($objects as $value) {
                    $resHtml .= '<li class="MuiButtonBase-root MuiMenuItem-root MuiMenuItem-gutters MuiMenuItem-root MuiMenuItem-gutters css-1dinu7n-MuiButtonBase-root-MuiMenuItem-root optionLi '.$request->type.'Li" data-li-type="'.$request->type.'" tabindex="0" role="option" aria-selected="false" data-id="'.$value->id.'" data-name="'.$value->name.'"> '.$value->name.' <span class="MuiTouchRipple-root css-8je8zh-MuiTouchRipple-root"></span> </li>';
                }

                $response['success'] = true;
                $response['html'] = $resHtml;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return response()->json($response);
        }
    }
}
