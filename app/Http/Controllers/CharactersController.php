<?php

namespace App\Http\Controllers;
include "CharactersNames.php";
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CharactersController extends Controller
{
 
    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        
        $secondCharactersData = array();
        if(substr($name,0,3) == "cap"){
            $name = str_replace('cap', 'captain',$name);
        }
        foreach($_SESSION['charactersNames'][0][strtolower($name)[0]] as $itemLetter){           
            if(strtolower(str_replace(' ', '', $itemLetter)) == $name || strpos(strtolower(str_replace(' ', '', $itemLetter)), $name) !== false){
                $characterToFind = $itemLetter;
                $responseCharacter = Http::get("https://gateway.marvel.com/v1/public/characters?name=".$characterToFind."&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                    $nameCharacter = json_decode($responseCharacter,true)['data']['results'][0]['id'];
                    $response = Http::get("https://gateway.marvel.com/v1/public/characters/".$nameCharacter."/comics?&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
                    $results = json_decode($response,true)['data']['results'];
                    $array = array();
                    $titles = array();
                    $charactersData = array();
                    foreach($results as $characters){
                        array_push($titles,$characters['title']);
                        foreach($characters['characters']['items'] as $character){
                           
                            if($character['name'] != $characterToFind){
                                $charactersData[] = ['character'=>$character['name'],'Comics'=>[$characters['title'] ]];
                                
                            }
                        }
                    }
                    $unique_array = [];
                    foreach($charactersData as $element) {
                        $hash = $element['character'];
                        $unique_array[$hash] = $element;
                    }
                    $resultado = array_values($unique_array);
            
                    $secondCharactersData = $resultado;
                    $cont=0;
                    foreach($resultado as $valor){ 
                        $thirdCharactersData = array();
                        foreach($charactersData as $valor2){
                            if($valor['character'] == $valor2['character']){
                                array_push($thirdCharactersData, $valor2['Comics'][0]);
                            }
                        }
                        $secondCharactersData[$cont]['Comics'] = $thirdCharactersData;
                        $cont++;
                    }
                     
                     return  response()->json([ 'characters'=>$secondCharactersData ]);
                    }
                    
                }
                if(!$secondCharactersData){
                    return response(['succes'=>'false', 'message'=>"We couldn't find the character ".$name],200);
                }
    }

   


}

    

