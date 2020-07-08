<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crop_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function storeCropJSONToFile($json){
        $res= file_put_contents(FCPATH."data/data.json",$json);
        return $res;
    }
    public function getCropJSONFromFile(){
        //$json= file_get_contents(base_url()."data/crop_data.json");
        $json= file_get_contents(base_url()."data/data.json");
        return $json;
    }
}
