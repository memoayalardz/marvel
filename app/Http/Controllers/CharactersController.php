<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CharactersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $response = Http::get("https://gateway.marvel.com/v1/public/characters?ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
        $results = json_decode($response,true)['data']['results'];
        $array = array();
        foreach($results as $hero => $value){
            $array[] = ['id'=>$value['id'],'name'=>$value['name'],'comics'=>$value['comics']['items']];
        }
        return    $array;
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData($characterFind)
    {
        $responseCharacter = Http::get("https://gateway.marvel.com/v1/public/characters?name=".$characterFind."&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
        if($responseCharacter){
            $idCharacter = json_decode($responseCharacter,true)['data']['results'][0]['id'];
            $response = Http::get("https://gateway.marvel.com/v1/public/characters/".$idCharacter."/comics?&ts=".$_ENV['TS']."&apikey=".$_ENV['MARVEL_PUBLIC_KEY']."&hash=".md5($_ENV['HASH_KEY']));
            $results = json_decode($response,true)['data']['results'];
            $array = array();
            $titles = array();
            $charactersData = array();
            foreach($results as $characters){
                array_push($titles,$characters['title']);
                foreach($characters['characters']['items'] as $character){
                   
                    if($character['name'] != $characterFind){
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
                        $thirdCharactersData[] = $valor2['Comics'][0];
                    }
                }
                $secondCharactersData[$cont]['Comics'][0] = $thirdCharactersData;
                $cont++;
            }
             
             return [ 'characters'=>$secondCharactersData ];
        }else{
         return "We couldn't find the character ".$characterFind;
        }
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
                $characterFind = "Iron Man";
                return getData($characterFind);
            break;
            case "capamerica":
                $characterFind = "Captain America";
              return getData($characterFind);
            break;
            default:
                return "We couldn't find the character ".$id;
        }

      

        
    }

   


}

    

