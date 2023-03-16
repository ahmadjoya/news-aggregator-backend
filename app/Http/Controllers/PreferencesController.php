<?php

namespace App\Http\Controllers;

use App\Models\Preferences;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function preferences(Request $request)
    {
        $user = $request->user();

        $preferences = Preferences::orderBy('id','desc')->where('user_id', '=', $user->id)->first();
        return $preferences;
    }
}
