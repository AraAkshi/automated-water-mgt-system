<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Crop_Model");
    }

    public function index()
    {
        $data['cid'] = $this->getCropIds();
        $data['mid'] = $this->getMotorIds();
        $this->load->view('pages/crop_reg',$data);
    }

    public function getCropIds()
    {
        $json = $this->Crop_Model->getCropJSONFromFile();
        if ($json) {
            $x = 0;
            $flag = false;
            $data = json_decode($json, true);
            foreach ($data['crops'] as $x => $y) {
                $mid[$x] = $data['crops'][$x]['cid'];
                $flag = true;
            }
            if ($flag) {
                $sd = $mid[$x];
                $mid[$x + 1] = ++$sd;
            } else {
                $mid[$x] = "c1";
            }
            return array_reverse($mid);
        } else {
            return false;
        }
    }
    public function getMotorIds()
    {
        $this->load->model("Motor_Model");
        $json = $this->Motor_Model->getMotorJSONFromFile();
        if ($json) {
            $x = 0;
            $flag = false;
            $data = json_decode($json, true);
            foreach ($data['motors'] as $x => $y) {
                $mid[$x] = $data['motors'][$x]['m_id'];
                $flag = true;
            }
            if (!$flag) {
                $mid[$x] = "m1";
            }
            return array_reverse($mid);
        } else {
            return false;
        }
    }


    public function getCropData()
    {
        $id = $this->input->post("c_id");
        $json = $this->Crop_Model->getCropJSONFromFile();
        if ($json) {
            $data = json_decode($json, true);
            $flag = false;
            foreach ($data['crops'] as $x => $y) {
                if ($y['cid'] == $id) {
                    $postData = $data['crops'][$x];//check whether c_id exists
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                echo '
                    <script>var jss=' . json_encode($postData) . ';</script>
                ';
                return $postData;
            } else {
                //return false;
            }
        } else {
            return false;
        }
    }


    public function ck()
    {

        $jsonx = $this->Crop_Model->getCropJSONFromFile();
        $jsonArray = json_decode($jsonx, true);

        $postData = array(
            "cid" => $this->input->post('c_id'),
            "crop_name" => $this->input->post('c_name'),
            "area" => $this->input->post('c_area'),
            "area_unit" => $this->input->post('c_area_unit'),
            "desc" => $this->input->post('c_desc'),
            "frequent_days" => $this->input->post('frequent_days'),
            "motor" => $this->input->post('c_motor')
        );
        $n = 0;
        foreach ($this->input->post() as $x => $y) {
            $tx = explode("_", $x, 3);
            if ($tx[0] == 'w') {
//                $postData["water"][$tx[2]][$tx[1]]= $y;
                if ($tx[1] == "time") {
                    $postData["water"][$n][$tx[1]] = $y;
                } else if ($tx[1] == "litre") {
                    $postData["water"][$n++][$tx[1]] = $y;
                }
            }
        }

        $flag = false;
        foreach ($jsonArray['crops'] as $x => $y) {
            if ($y['cid'] == $postData['cid']) {
                $flag = true;                 //check whether m_id exists
                $jsonArray['crops'][$x] = $postData;
                break;
            }
        }

        if ($flag) {
            echo "UPDATED";
        } else {
            if (array_push($jsonArray['crops'], $postData)) //insert postData to json array
                echo "INSEERTED";
            else
                echo "Not INSEERTED";
        }


        $json = json_encode($jsonArray);
        if ($json) {
            $x = $this->Crop_Model->storeCropJSONToFile($json);
            if ($x) {
                //echo "Data Stored";
                include "Motor.php";
                $motor=new Motor();
                $motor->storeCommands("db_update");
                sleep(2);
                $motor->storeCommands("uc_autoSchedule");

                redirect(base_url() . "index.php/crop/");
            } else {
                echo "Not OK";
            }
        }
    }


}
