<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserImpression;
use Auth;

class ImpressionController extends Controller {

    public function store(Request $request) {
        $userId = Auth::user()->id;

        $request->validate([
            'key_name' => 'required|min:3|max:100',
            'key_value' => 'required|max:100'
        ]);
        $keyName = $request->input('key_name', '');
        $keyValue = $request->input('key_value', '');

        UserImpression::updateOrCreate(
                ['user_id' => $userId, 'key_name' => $keyName],
                ['key_value' => $keyValue]
        );

        $response = ['status' => 'success', 'message' => 'successfully saved'];
        return response()->json($response, 200);
    }

    public function destroy($keyName) {
        $userId = Auth::user()->id;
        UserImpression::where('user_id', $userId)->where('key_name', $keyName)->delete();
        
        $response = ['status' => 'success', 'message' => 'successfully removed'];
        return response()->json($response, 200);
    }

}
