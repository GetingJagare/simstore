<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request)
    {
        $Request = json_decode(file_get_contents('http://lk.gsmcorp.tarifer.ru/get_access_token.json?mobile=false&phone=' .
            urlencode($request->phone) . '&password=' . urlencode($request->password)), true);

        if ($Request['success']) {
            header('Location: http://lk.gsmcorp.tarifer.ru/login_with_token?token=' . $Request['access_token']);
            return response()->json(['success' => 1, 'redirect_url' => 'http://lk.gsmcorp.tarifer.ru/login_with_token?token=' . $Request['access_token']]);
        }

        return response()->json(['success' => 0]);
    }

    public function lostpass(Request $request)
    {
        $curl = curl_init('http://lk.gsmcorp.tarifer.ru/restore_password/' . urlencode($request->username) . '.json');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Accept: application/json"]);

        $Request = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (isset($Request['success'])) {
            $success = $Request['success'];
            $message = $Request['message'];
        } else if (isset($Request['status']) && $Request['status'] == '404') {
            $success = false;
            $message = 'Такой пользователь не зарегистрирован';
        }

        return response()->json(['success' => $success, 'message' => $message]);
    }
}
