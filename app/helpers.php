<?php

function Guardian($sourceName, $data){
    $arrange = $data->response->results;
    $new_data = array();

    for($i = 0; $i < count($arrange); $i++){
        $image_url="";
        $pillarName = "";
        if(property_exists($arrange[$i], "blocks")){
            if(count($arrange[$i]->blocks->main->elements[0]->assets)>0){
                $image_url = $arrange[$i]->blocks->main->elements[0]->assets[0]->file;
            }
        }

        if(property_exists($arrange[$i], "pillarName")){
            $pillarName = $arrange[$i]->pillarName;
        }
        array_push($new_data, [
            "image"=>$image_url,
            "title"=>$arrange[$i]->webTitle,
            "description"=>$arrange[$i]->webTitle,
            "date"=>$arrange[$i]->webPublicationDate,
            "category"=>$pillarName,
            "source"=>$sourceName,
            "author"=>"Admin",
            "sourceLink" => $arrange[$i]->webUrl,
            "apiURL" => $arrange[$i]->apiUrl,
            // "data"=>$arrange[$i]
        ]);
    }

    return $new_data;
}

function NewsApi($data){
    $arrange = $data->articles;
    $new_data = array();

    for($i = 0; $i < count($arrange); $i++){

        array_push($new_data, [
            "image"=>$arrange[$i]->urlToImage,
            "title"=>$arrange[$i]->title,
            "description"=>$arrange[$i]->description,
            "date"=>$arrange[$i]->publishedAt,
            "category"=>"unknown",
            "source"=>$arrange[$i]->source->name,
            "author"=>$arrange[$i]->author,
            "sourceLink" => $arrange[$i]->url,
            // "apiURL" => $arrange[$i]->apiUrl,
            // "data"=>$arrange[$i]
        ]);
    }

    return $new_data;
}

function NYT($data){
    $arrange = $data->response->docs;
    $new_data = array();

    for($i = 0; $i < count($arrange); $i++){
        $image_url="";
        $category = "";
        $author = "";
        if(property_exists($arrange[$i], "multimedia")){
            if(count($arrange[$i]->multimedia)>0){
                $image_url = $arrange[$i]->multimedia[0]->url;
            }
        }

        if(property_exists($arrange[$i], "section_name")){
            $category = $arrange[$i]->section_name;
        }
        if(property_exists($arrange[$i], "byline")){
            $author = $arrange[$i]->byline->original;
        }

        array_push($new_data, [
            "image"=>$image_url,
            "title"=>$arrange[$i]->headline->main,
            "description"=>$arrange[$i]->lead_paragraph,
            "date"=> $arrange[$i]->pub_date,
            "category"=>$category,
            "source"=>$arrange[$i]->source,
            "author"=> $author,
            "sourceLink" => $arrange[$i]->web_url,
            "apiURL" => false,
            // "data"=>$arrange[$i]
        ]);
    }

    return $new_data;
}

if(!function_exists('restructure_data')){
    function restructure_data($sourceName, $data){
        switch($sourceName) {
            case('Guardian'):
                return Guardian($sourceName, $data);
                break;
            case("Newsapi.org"):
                return NewsApi($data);
                break;
            case("NYT"):
                return NYT($data);
                break;
            }
    }
}
