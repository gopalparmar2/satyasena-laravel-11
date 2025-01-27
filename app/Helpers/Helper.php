<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

if (!function_exists('sendResponse')) {
    function sendResponse($result = [], $message="") {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }
}

if (!function_exists('sendError')) {
    function sendError($error, $errorMessages = [], $code = 200) {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin() {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('super-admin')) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('generateOtp')) {
    function generateOtp() {
        return rand(100000, 999999);
    }
}

if (!function_exists('generateMyReferralCode')) {
    function generateMyReferralCode() {
        do {
            $referralCode = Str::upper(Str::random(6));
            $codeExists = User::where('referral_code', $referralCode)->exists();
        } while ($codeExists);

        return $referralCode;
    }
}

if (!function_exists('generateMembershipNumber')) {
    function generateMembershipNumber() {
        do {
            $membershipNumber = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (DB::table('users')->where('referral_code', $membershipNumber)->exists());

        return $membershipNumber;
    }
}

if (!function_exists('formatMembershipNumber')) {
    function formatMembershipNumber($membershipNumber) {
        if (strlen($membershipNumber) === 10) {
            return substr($membershipNumber, 0, 2) . '****' . substr($membershipNumber, -4);
        }

        return $membershipNumber;
    }
}

if (!function_exists('obfuscateName')) {
    function obfuscateName($name) {
        $parts = explode(' ', $name);
        $obfuscated = [];

        foreach ($parts as $part) {
            $obfuscated[] = substr($part, 0, 1) . str_repeat('*', strlen($part) - 2) . substr($part, -1);
        }

        return implode(' ', $obfuscated);
    }
}
