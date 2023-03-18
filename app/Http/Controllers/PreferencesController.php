<?php

namespace App\Http\Controllers;

use App\Models\Preferences;
use Illuminate\Http\Request;
use Storage;

class PreferencesController extends Controller
{
    public function preferences(Request $request)
    {
        $user = $request->user();

        $preferences = Preferences::orderBy('id','desc')->where('user_id', '=', $user->id)->first();

        $json = Storage::disk('local')->get('sources.json');
        $sources_data = json_decode($json, true);

        return $preferences;
    }
}
