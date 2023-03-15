<?php

function Guardian($sourceName, $data){
    $arrange = $data->response->results;
    $new_data = array();

    for($i = 0; $i < count($arrange); $i++){
        $image_url="";
        if(count($arrange[$i]->blocks->main->elements[0]->assets)>0){
            $image_url = $arrange[$i]->blocks->main->elements[0]->assets[0]->file;
        }

        array_push($new_data, [
            "image"=>$image_url,
            "title"=>$arrange[$i]->webTitle,
            "description"=>$arrange[$i]->webTitle,
            "date"=>$arrange[$i]->webPublicationDate,
            "category"=>$arrange[$i]->pillarName,
            "source"=>$sourceName,
            "author"=>"Admin",
            "sourceLink" => $arrange[$i]->webUrl,
            "apiURL" => $arrange[$i]->apiUrl,
            // "data"=>$arrange[$i]
        ]);
    }

    return $new_data;
}

function BBC($data){
    return $data->response->results;
}

function NYT($data){
    return $data->response->results;
}

if(!function_exists('restructure_data')){
    function restructure_data($sourceName, $data){
        switch($sourceName) {
            case('Guardian'):
                return Guardian($sourceName, $data);
                break;
            case("BBC"):
                return ['sourceName'=>$sourceName, 'data'=>BBC($data)];
                break;
            default:
                return ['sourceName'=>$sourceName, 'data'=>NYT($data)];
            }
    }
}
