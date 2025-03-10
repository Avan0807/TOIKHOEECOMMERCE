<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $users = User::where(['email' => $userSocial->getEmail()])->first();

        if ($users) {
            Auth::login($users);
            return redirect('/')->with('success', 'Bạn đã đăng nhập từ ' . $provider);
        } else {
            $user = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'image' => $userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
            ]);
            return redirect()->route('home');
        }
    }


    // Define login credentials
    public function credentials(Request $request)
    {
        return [
            'phoneNumber' => $request->phoneNumber,
            'password' => $request->password,
            'status' => 'active',
            'role' => 'admin',
        ];
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'phoneNumber' => 'required|string',
            'password' => 'required|string',
        ]);
    }


        /**
     * Handle user login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apilogin(Request $request)
    {
        try {
            // Step 1: Validate input
            $validator = Validator::make($request->all(), [
                'phoneNumber' => 'required|string',
                'password' => 'required|string',
            ], [
                'phoneNumber.required' => 'Số điện thoại không được để trống',
                'password.required' => 'Mật khẩu không được để trống',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 2: Find user by phoneNumber
            $user = User::where('phoneNumber', $request->phoneNumber)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số điện thoại không tồn tại.',
                ], 404);
            }

            // Step 3: Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu không đúng.',
                    'debug' => [
                        'password_input' => $request->password,
                        'hashed_password' => $user->password,
                        'hash_check_result' => Hash::check($request->password, $user->password),
                    ],
                ], 401);
            }
            // Step 4: Generate Sanctum token
            $token = $user->createToken('authToken')->plainTextToken;

            // Step 5: Return response
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle user logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apilogout(Request $request)
    {
        try {
            // Delete current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng xuất thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function apidoctorLogin(Request $request)
    {
        try {
            // Step 1: Validate input
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'password' => 'required|string',
            ], [
                'phone.required' => 'Số điện thoại không được để trống.',
                'password.required' => 'Mật khẩu không được để trống.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 2: Tìm bác sĩ bằng số điện thoại
            $doctor = Doctor::where('phone', $request->phone)->first();

            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số điện thoại không tồn tại.',
                ], 404);
            }

            // Step 3: Xác minh mật khẩu
            // if (!Hash::check($request->password, $doctor->password)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Mật khẩu không đúng.',
            //     ], 401);
            // }
            if ($request->password !== $doctor->password) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu không đúng.',
                ], 401);
            }

            // Step 4: Tạo token nếu cần (Laravel Sanctum)
            $token = $doctor->createToken('authToken')->plainTextToken;

            // Step 5: Trả về phản hồi
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công.',
                'doctor' => $doctor,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập thất bại.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
