<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ChangePasswordController extends Controller {

    public function changePassword(Request $request) {
        $response = [];
        $validator = Validator::make($request->all(), [
                    'current_password' => 'required|string|min:8|max:50',
                    'new_password' => 'required|string|min:8|max:50',
                    'confirm_password' => 'required|string|min:8|max:50|same:new_password',
                        ], [
                        ]
        );

        $validator->validate();

        $userId = \Auth::user()->id;
        $userObject = User::where('id', $userId)->first();

        if (!empty($userObject) && Hash::check($request->current_password, $userObject->password)) {
            $userObject->password = Hash::make($request->new_password);
            $userObject->save();
            $response = ['status' => 'success', 'message' => 'Password has been changed successfully'];
        } else {
            throw ValidationException::withMessages([
                        'current_password' => [trans('auth.password')],
            ]);
        }

        return response()->json($response, 200);
    }

}
