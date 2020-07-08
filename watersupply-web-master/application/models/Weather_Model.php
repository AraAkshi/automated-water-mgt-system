<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weather_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    public function getWeatherJSON(){ //TODO Set Lat,Long as a variable for Dark-Sky API & Set CityID as a variable for OWM API,
        //$json= file_get_contents("https://api.darksky.net/forecast/d51d04aaccdbda09a872dba0bd7e7dac/6.935,79.8538"); //?exclude=minutely,hourly,daily,alerts,flags"); //DARK_SKY API
        $json= @file_get_contents("http://api.openweathermap.org/data/2.5/forecast?id=1248991&APPID=644a6cceec1bf53ba36942362cbc009d");  //OpenWeatherMap API
        return $json;
    }
    public function storeWeatherJSON($json){
        $myfile = fopen(FCPATH."data/current_weather_data.json", "w") or die("Unable to open file!");
        $res = fwrite($myfile, $json);
        fclose($myfile);
        return $res;
    }
    public function getWeatherJSONFromFile(){
        $json= file_get_contents(base_url()."data/current_weather_data.json");
        return $json;
    }
}
