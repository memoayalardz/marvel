<?php

namespace App\Http\Controllers;
include "CharactersNames.php";
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ColaboratorsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        if(substr($name,0,3) == "cap"){
         $name = str_replace('cap', 'captain',$name);
        }
        $array = array();
        foreach($_SESSION['charactersNames'][0][strtolower($name)[0]] as $itemLetter){           
            if(strtolower(str_replace(' ', '', $itemLetter)) == $name || strpos(strtolower(str_replace(' ', '', $itemLetter)), $name) !== false){
                $characterToFind = $itemLetter;
                $responseCharacter = Http::get("https://gateway.marvel.com/v1/public/characters?name=".$characterToFind."&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                $nameCharacter = json_decode($responseCharacter,true)['data']['results'][0]['id'];
                $response = Http::get("https://gateway.marvel.com/v1/public/characters/".$nameCharacter."/comics?&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
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
                $array = ["editors"=>array_values(array_unique($editors)),"writers"=>array_values(array_unique($writers))];
                return $array; 
            }
        }
        if(!$array){
            return response(['succes'=>'false', 'message'=>"We couldn't find the character ".$name],200);
        }
    }
}
