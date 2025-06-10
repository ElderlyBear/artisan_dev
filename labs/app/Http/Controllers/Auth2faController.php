<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class Auth2faController extends Controller
{
    protected function verifyCode(User $user, $code): bool
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($user->two_factor_secret, $code);
    }

    public function toggleTwoFactorAuth(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
            'enableTwoFactorAuth' => 'required|boolean',
            'code' => 'nullable|string',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password',
            ], 401);
        }

        if ($request->enableTwoFactorAuth) {
            if (!$user->is_two_factor_auth) {
                $user->generateTwoFactorSecret();

                $google2fa = new Google2FA();
                $qrUrl = $google2fa->getQRCodeUrl(
                    config('app.name'),
                    $user->email,
                    $user->two_factor_secret
                );

                return response()->json([
                    'message' => 'Two factor authentication enabled',
                    'secret' => $user->two_factor_secret,
                    'qr_code_url' => $qrUrl,
                ]);
            }

            return response()->json([
                'message' => 'Two factor authentication is already enabled',
            ]);
        } else {
            if (!$request->filled('code')) {
                return response()->json([
                    'message' => 'Verification code required to disable 2FA',
                ], 422);
            }

            try {
                if (!$this->verifyCode($user, $request->code)) {
                    return response()->json([
                        'message' => 'Wrong verification code',
                    ], 401);
                }

                $user->update([
                    'two_factor_secret' => null,
                    'is_two_factor_auth' => false,
                ]);

                return response()->json([
                    'message' => 'Two factor authentication disabled',
                ]);
            } catch (\PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }
        }
    }

    public function twoFactorStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'is_two_factor_auth' => $user->is_two_factor_auth,
        ]);
    }
}
