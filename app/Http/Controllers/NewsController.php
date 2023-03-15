<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function news(Request $request){
        $sources_data = [[
            "sourceName" => "Guardian",
            "keyBase" => true,
            "apiKey" => "api-key=079eac4f-1841-4663-90d7-fc7c1facc430",
            "URL" => "https://content.guardianapis.com/search",
        ]];

        $data = array();

        for($i = 0; $i < count($sources_data); $i++){
            $url = "";
            if($sources_data[$i]["keyBase"] == true){
                $url = $sources_data[$i]["URL"]."?"."show-blocks=main&".$sources_data[$i]["apiKey"];
            }else{
                $url = $sources_data[$i].["URL"];
            }

            $response = Http::get($url);
            $restructured = restructure_data($sources_data[$i]["sourceName"], $response->object());
            array_push($data, ...$restructured);
        };

        return response()->json($data);
    }
}
