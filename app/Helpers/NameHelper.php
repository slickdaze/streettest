<?php

namespace App\Helpers;
use Illuminate\Support\Arr;

class NameHelper {

    public static $detectionArray = [];
    public static $formattedArray = [];

    public static function formatName($nameString) {

        $nameArray = Self::prepareInput($nameString);
        Self::$detectionArray = []; Self::$formattedArray =[];

        Self::detectCouple($nameArray);

        foreach(Self::$detectionArray as $array){
            array_push(Self::$formattedArray, Self::formatNameArray($array));
        }

        return Self::$formattedArray;
    }


    private static function prepareInput($string){
        $string = str_replace(array('.', ','), ' ' , $string);
        return explode(' ', preg_replace('/\s\s+/', ' ', trim($string)));
    }


    private static function detectCouple($nameArray){

        $surname = end($nameArray);

        $firstPersonArray = $nameArray;
        $secondPersonArray = [];

        foreach($nameArray as $key => $item){
            if ($item === '&' || $item === 'and' || $item === '+'){
                $secondPersonArray = array_slice($nameArray, $key+1);
                $firstPersonArray = array_splice($nameArray, 0, $key);
                break;
            }
        }

        // This is seemingly the thorniest part of the algo ..
        // Must assume gender-specific naming conventions, and their permutations ..
        // e.g. 'Dr and Mrs Sam Jones', then Dr is called Sam Jones and we don't knwo anything about Mrs Jones's first name.
        if (count($secondPersonArray) > 0){

            if (count($firstPersonArray) == 1){

                if(count($secondPersonArray) > 2){
                    array_push($firstPersonArray, Self::getMiddleItemsFromSecondaryName($secondPersonArray));
                    $firstPersonArray = Arr::flatten($firstPersonArray);
                    $secondPersonArray = Self::shortenSecondaryName($secondPersonArray);
                }

                array_push($firstPersonArray, $surname);
            }

            elseif (count($firstPersonArray) == 2){
                str_replace(array('.', ','), '' , $firstPersonArray[1]);
                if(strlen($firstPersonArray[1]) == 1){
                    array_push($firstPersonArray, $surname);
                }
                $secondPersonArray = Self::shortenSecondaryName($secondPersonArray);
            }
        }

        array_push(Self::$detectionArray, $firstPersonArray);

        if (count($secondPersonArray) > 0){
            array_push(Self::$detectionArray, $secondPersonArray);
        }
    }

    public static function formatNameArray($inputArray){

        $person = ['title' => null, 'first_name' => null, 'initial' => null, 'last_name' => null ];

        // could do something along these lines, to enforce accepted titles
        // $titleArray = ['mr', 'mrs', 'dr', 'prof', 'sir']; // examples
        // if(!in_array(strtolower($inputArray[0]), $titleArray)){
        //     // throw warning (or whatever relevant) - can't identify title.
        // }

        $person['title'] = ucfirst($inputArray[0]);

        if(count($inputArray) > 2){
            if(strlen($inputArray[1]) == 1){
                $person['first_name'] = null;
                $person['initial'] = ucfirst($inputArray[1]);
            }
            else {
                $person['first_name'] = ucfirst($inputArray[1]);
                $person['initial'] = null;
            }
        }

        $person['last_name'] = ucfirst(end($inputArray));

        return $person;

    }

    public static function shortenSecondaryName($inputArray){
        if(count($inputArray) > 2){
            $title = array_shift($inputArray);
            $surname = array_pop($inputArray);
            return [$title, $surname];
        }
        else{
            return $inputArray;
        }
    }

    public static function getMiddleItemsFromSecondaryName($inputArray){
        if(count($inputArray) > 2){
            array_shift($inputArray);
            array_pop($inputArray);
            return $inputArray;
        }
    }



}
