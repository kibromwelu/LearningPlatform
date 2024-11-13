<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\LoggedinDevices;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\PasswordResetLink;
use App\Services\FileService;
use Exception;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        # By default we are using here auth:api middleware
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function forgotPassword(Request $request)
    {
        return User::forgotPassword($request);
    }
    public function sendResetPasswordForm(Request $request, $token)
    {
        $tokenData = PasswordResetLink::where('token', $token)->first();
        if (!$tokenData || $tokenData->expires_at < now()) {
            throw new Exception('invalid link');
        }
        return view('PasswordReset', ['token' => $token]);
    }

    public function resetApprovedResetRequest(Request $request, $identityId)
    {
        $response = User::updateUser($request->all(), $identityId);
        return response()->json(['data' => $response]);
    }
    public function resetPassword(Request $request, $token)
    {
        PasswordResetLink::resetPassword($request->all(), $token);
        $url = url('/api/auth/login');
        return view('SuccessMessage', ['link' => $url]);
    }
    public function index(Request $request)
    {
        $users = User::getUsers($request->numberOfItems);
        return response()->json($users, 200);
    }
    public function update(Request $request, $id)
    {
        $user = User::updateUser($request->all(), $id);
        return response()->json($user, 203);
    }
    public function store(UserRequest $request)
    {
        try {
            $input = $request->only(['name', 'email', 'password', 'role']);
            $user = User::registerUser($input);
            return response()->json(['error' => false, 'message' => "user created successfuly", "data" => $user], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => 'error found' . $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // dd($id);
        $user = User::deleteUser($id);
        return response()->json($user, 202);
    }
    public function getFile($filename)
    {
        return FileService::getFile('/signatures/', $filename);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $result = User::login($credentials);
        if (isset($result['error'])) {
            return response()->json($result, 401);
        }
        $logDevice = LoggedinDevices::register($result['user']->id, $request->ip());
        return response()->json(['error' => false, 'data' => $result, 'device' => $logDevice]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        # Here we just get information about current user
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout($loggedInDevice)
    {
        // dd($loggedInDevice);
        auth()->logout();
        Loggedindevices::logoutDevice($loggedInDevice);
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function logoutFromAllOtherDevices(Request $request, $loggedInDevice)
    {
        $response = Loggedindevices::logoutFromAllOtherDevices($request->user_id, $loggedInDevice);

        return response()->json(['error' => false, 'message' => 'Logged out from all other devices successfully'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        # When access token will be expired, we are going to generate a new one wit this function 
        # and return it here in response
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        # This function is used to make JSON response with new
        # access token of current user
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {

        $address = Address::where('email', $request->email)->first();
        // dd($address);
        if (!$address) {
            return back()->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        $user = User::where('identity_id', $address->identity_id);

        if (!$user) {
            dd('fusfjd');
            return back()->withErrors(['email' => 'We could not find a user associated with that email address.']);
        }

        $response = Password::sendResetLink($address);
        dd($response);
        if ($response === Password::RESET_LINK_SENT) {
            return back()->with('status', 'We have emailed you a password reset link.');
        }

        return back()->withErrors(['email' => 'We could not send the password reset link.']);
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($response === Password::PASSWORD_RESET) {
            return redirect('/login')->with('status', 'Password reset successful.');
        }

        return back()->withErrors(['email' => 'Invalid token or email.']);
    }
}
