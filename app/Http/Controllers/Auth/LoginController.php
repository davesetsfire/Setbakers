<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use App\Models\OrderDetail;

class LoginController extends Controller {
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        return redirect()->route('index');
    }

    protected function sendLoginResponse(Request $request) {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        if (Auth::user()->account_type == 'complete' &&
                Auth::user()->projectDetail['is_subscription_paused'] == 0 &&
                Auth::user()->projectDetail['subscription_end_date'] < date('Y-m-d H:i:s')) {
            $isLastBankPaymentPending = false;

            $lastOrder = OrderDetail::where('user_id', Auth::user()->id)->with('subscription')->latest()->first();
            if (!empty($lastOrder) && $lastOrder->payment_mode == 'bank' && $lastOrder->status == 'pending' && $lastOrder->subscription['account_type'] == 'project') {
                $isLastBankPaymentPending = true;
            }

            if ($isLastBankPaymentPending == false) {
                $request->session()->flash('showPaymentPopup', 'true');
            }
        }

        return $request->wantsJson() ? new JsonResponse(
                        ['status' => 'success', 'redirectTo' => $this->redirectPath()], 200) : redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user) {
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
    }

    protected function credentials($request) {
        return ['email' => $request->email, 'password' => $request->password, 'is_active' => 1];
    }

}
