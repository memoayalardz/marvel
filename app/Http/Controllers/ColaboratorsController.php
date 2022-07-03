<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ColaboratorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        switch ($id){

            case "ironman":
                $character = "Iron Man";
                $responseCharacter = Http::get("https://gateway.marvel.com/v1/public/characters?name=".$character."&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                $idCharacter = json_decode($responseCharacter,true)['data']['results'][0]['id'];
                $response = Http::get("https://gateway.marvel.com/v1/public/characters/".$idCharacter."/comics?&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                $results = json_decode($response,true)['data']['results'];
                $array = array();
                $editors = array();
                $writers = array();
                foreach($results as $collaborators){
                    foreach($collaborators['creators']['items'] as $collaborator){
                        if($collaborator['role'] == 'editor'){
                            array_push($editors,$collaborator['name']);
                        }elseif($collaborator['role'] == 'writer'){
                            array_push($writers,$collaborator['name']);
                        }
                    }
                }
                $array[] = ["editors"=>array_values(array_unique($editors)),"writers"=>array_values(array_unique($writers))];
                 return $array; 
            break;
            case "capamerica":
                $character = "Captain America";
                $responseCharacter = Http::get("https://gateway.marvel.com/v1/public/characters?name=".$character."&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                $idCharacter = json_decode($responseCharacter,true)['data']['results'][0]['id']; 
                $response = Http::get("https://gateway.marvel.com/v1/public/characters/".$idCharacter."/comics?&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                return json_decode($response,true)['data']['results'];
                $results = json_decode($response,true)['data']['results'];
                $array = array();
                $editors = array();
                $writers = array();
                foreach($results as $collaborators){
                    foreach($collaborators['creators']['items'] as $collaborator){
                        if($collaborator['role'] == 'editor'){
                            array_push($editors,$collaborator['name']);
                        }elseif($collaborator['role'] == 'writer'){
                            array_push($writers,$collaborator['name']);
                        }
                    }
                }
                $array[] = ["editors"=>array_values(array_unique($editors)),"writers"=>array_values(array_unique($writers))];
                 return $array; 
            break;
            default:
                return "We couldn't find the character ".$id;
        }

      

    }
}
