<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motor_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function storeMotorJSONToFile($json){
       // $res= file_put_contents(FCPATH."data/motor_data.json",$json);
        $res= file_put_contents(FCPATH."data/data.json",$json);
        return $res;
    }
    public function getMotorJSONFromFile(){
        //$json= file_get_contents(base_url()."data/motor_data.json");
        $json= file_get_contents(base_url()."data/data.json");
        return $json;
    }
    
    public function storeSensorJSONToFile($json){
        $res= file_put_contents(FCPATH."data/sensor_data.json",$json);
        return $res;
    }
    
    public function storeMoistureJSONToFile($json){
        $res= file_put_contents(FCPATH."data/moisture_data.json",$json);
        //$res= file_put_contents(FCPATH."data/data.json",$json);
        return $res;
    }
    public function storeDeviceStateJSONToFile($json){
        $res= file_put_contents(FCPATH."data/device_state.json",$json);
        return $res;
    }
    public function storeCommandJSONToFile($json){
        $res= file_put_contents(FCPATH."data/commands.json",$json);
        return $res;
    }
}
