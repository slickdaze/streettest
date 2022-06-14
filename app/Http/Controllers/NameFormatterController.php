<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\NameHelper;

class NameFormatterController extends BaseController
{

    public function index(){

        $peopleFromCSV = [];

        if (($open = fopen(storage_path() . "/examples__284_29.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $peopleFromCSV[] = $data;
            }

            fclose($open);
        }

        array_shift($peopleFromCSV);

        $people = [];

        foreach($peopleFromCSV as $csvLine){
            $formattedNameArray = NameHelper::formatName($csvLine[0]);
            foreach($formattedNameArray as $person){
                array_push($people, $person);
            }
        }

        //dd($people);

        // do something with resulting array,
        // .. but for convenience here, throw into a view.
        return view('welcome', ['people' => $people]);
    }
}
