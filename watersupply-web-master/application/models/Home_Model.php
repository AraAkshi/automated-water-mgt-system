<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getSensorDataFromFile(){
        //$json= file_get_contents(base_url()."data/crop_data.json");
        $json= file_get_contents(base_url()."data/sensor_data.json");
        return $json;
    }

    public function getMoistureJSONFromFile(){
        //$json= file_get_contents(base_url()."data/crop_data.json");
        $json= file_get_contents(base_url()."data/moisture_data.json");
        return $json;
    }
    public function storeCommandJSONToFile($json){
        $res= file_put_contents(FCPATH."data/commands.json",$json);
        return $res;
    }
    public function getCommandJSONFromFile(){
        //$json= file_get_contents(base_url()."data/crop_data.json");
        $json= file_get_contents(base_url()."data/commands.json");
        return $json;
    }
}
