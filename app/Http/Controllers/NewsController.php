<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;


class NewsController extends Controller
{
    public function news(Request $request){

        $json = Storage::disk('local')->get('sources.json');
        $sources_data = json_decode($json, true);

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

    public function search(Request $request){
        $q = "";
        $date = "";
        if($request->q){
            $q = $request->q;
        };
        if($request->date){
            $date = $request->date;
        };

        $json = Storage::disk('local')->get('sources_for_search.json');
        $sources_data = json_decode($json, true);

        $data = array();

        for($i = 0; $i < count($sources_data); $i++){
            $url = "";

            if($sources_data[$i]["keyBase"] == true){
                if($sources_data[$i]["sourceName"] == "Guardian"){
                    $url = $sources_data[$i]["URL"]."?q=".$q."&date=".$date."&show-blocks=main&".$sources_data[$i]["apiKey"];
                }

                elseif($sources_data[$i]["sourceName"] == "Newsapi.org"){
                    $url = $sources_data[$i]["URL"]."?q=".$q."&from=".$date."&sortBy=popularity&".$sources_data[$i]["apiKey"];
                }

                elseif($sources_data[$i]["sourceName"] == "NYT"){
                    $dateToNum = explode("-", $date);
                    $finalDate = join("",$dateToNum);
                    if($date){
                        $url = $sources_data[$i]["URL"]."?q=".$q."&begin_date=".$finalDate."&".$sources_data[$i]["apiKey"];
                    }else{
                        $url = $sources_data[$i]["URL"]."?q=".$q."&".$sources_data[$i]["apiKey"];
                    }
                    // echo $url;
                }
            }

            $response = Http::get($url);
            // dd($response);
            $restructured = restructure_data($sources_data[$i]["sourceName"], $response->object());
            array_push($data, ...$restructured);
        };

        return response()->json($data);

    }
}
