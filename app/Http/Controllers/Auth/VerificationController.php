<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class VerificationController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Email Verification Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling email verification for any
      | user that recently registered with the application. Emails may also
      | be re-sent if the user didn't receive the original email message.
      |
     */

use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request) {
        $user = User::where('id', $request->route('id'))->first();

        if (empty($user) || !hash_equals((string) $request->route('hash'), sha1($user->email))) {
            throw new AuthorizationException;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse([], 204) : redirect($this->redirectPath())->with('verified', true);
    }

}
