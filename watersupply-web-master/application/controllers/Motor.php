<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Motor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Motor_Model");
    }

    public function index() {
        $data['mid'] = $this->getMotorIds();
        $this->load->view('pages/motor_reg', $data);
    }

    public function getMotorIds() {
        $json = $this->Motor_Model->getMotorJSONFromFile();
        if ($json) {
            $x = 0;
            $flag = false;
            $data = json_decode($json, true);
            foreach ($data['motors'] as $x => $y) {
                $mid[$x] = $data['motors'][$x]['m_id'];
                $flag = true;
            }
            if ($flag) {
                $sd = $mid[$x];
                $mid[$x + 1] = ++$sd;
            } else {
                $mid[$x] = "m1";
            }
            return array_reverse($mid);
        } else {
            return false;
        }
    }

    public function getMotorData() {

        $id = $this->input->post("m_id");
        $json = $this->Motor_Model->getMotorJSONFromFile();
        if ($json) {
            $data = json_decode($json, true);
            $flag = false;
            foreach ($data['motors'] as $x => $y) {
                if ($y['m_id'] == $id) {
                    $postData = $data['motors'][$x]; //check whether m_id exists
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                // var_dump($postData);
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

    public function storeData() {
        //todo confirm JS validation. bcz, directly storing json file

        $jsonx = $this->Motor_Model->getMotorJSONFromFile();
        $jsonArray = json_decode($jsonx, true);
        $postData = $this->input->post();
        $flag = false;
        $m_code = 'a';

        foreach ($jsonArray['motors'] as $x => $y) {

            if ($y['m_id'] == $postData['m_id']) {
                $flag = true;                 //check whether m_id exists
                $postData['m_code'] = $m_code;
                $jsonArray['motors'][$x] = $postData;
                break;
            }
            $m_code++;
        }
        $postData['m_code'] = $m_code;

        if ($flag) {
            echo "UPDATED";
        } else {
            array_push($jsonArray['motors'], $postData); //insert postData to json array
            echo "INSEERTED";
        }

        $json = json_encode($jsonArray);
        echo "<br/>";
        if ($json) {
            $x = $this->Motor_Model->storeMotorJSONToFile($json);
            if ($x) {
                //echo "Data Stored";
                $this->storeCommands("db_update");
                redirect(base_url() . "index.php/motor/");
            } else {
                echo "Not OK";
            }
        }
    }

    public function ck() {
        var_dump($this->getMotorIds());
    }

    public function storeSensorData($data = null) {
        //svl::mois_71::rain_0::temp_-999.00::humi_-999.00
        if ($data) {

            $dx = explode("::", $data);
            $dataArray = [];
            foreach ($dx as $dv) {
                if (trim($dv) == "svl") {
                    continue;
                }
                $dy = explode("_", trim($dv));
                $pfx = trim($dy[0]);
                $val = trim($dy[1]);

                $dataArray[$pfx] = array($val);
            }

            $json = json_encode($dataArray);
            $x = $this->Motor_Model->storeSensorJSONToFile($json);
            if ($x) {
                echo "Data Stored";
            } else {
                echo "Not OK";
            }
        }
    }

    public function getMoistureLevel($y = null) {

        if (preg_match("/=|&/", $y)) {
            foreach ($as = preg_split("/&/", $y) as $a => $b) {
                $ad = preg_split("/=/", $as[$a]);
                $array["moisture"][$a] = array("sensor" => $ad[0], "value" => $ad[1]);
            }
            $json = json_encode($array);
            if ($json) {
                $x = $this->Motor_Model->storeMoistureJSONToFile($json);
                if ($x) {
                    echo "Data Stored";
                } else {
                    echo "Not OK";
                }
            }
        }

        echo "<br/>";

        //exec("start mspaint");
    }

    public function setDeviceOnline($y = null) {

        if (preg_match("/=|&/", $y)) {
            foreach ($as = preg_split("/&/", $y) as $a => $b) {
                $ad = preg_split("/=/", $as[$a]);
                $array["device"] = array("port" => $ad[0], "state" => $ad[1]);
            }
            echo $json = json_encode($array);
            if ($json) {
                $x = $this->Motor_Model->storeDeviceStateJSONToFile($json);
                if ($x) {
                    echo "Data Stored";
                } else {
                    echo "Not OK";
                }
            }
        }

        echo "<br/>";

        //exec("start mspaint");
    }

    public function storeCommands($comm = null) {
        if ($comm == null) {
            $jsonArray['commands'][0] = "";
        } else {
            $jsonArray['commands'][0] = $comm;
        }

        /** COMMAND SET
         * $jsonArray['commands'][1] = "mc_a060"; //database update
         * $jsonArray['commands'][2] = "db_update"; //database update
         * $jsonArray['commands'][3] = "uc_autoSchedule"; //  shedule all
         * $jsonArray['commands'][4] = "uc_removeSchedule"; //remove all shedules
         */
        $json = json_encode($jsonArray);
        if ($json) {
            $x = $this->Motor_Model->storeCommandJSONToFile($json);
            if ($x) {
//                echo "Data Stored";
                return true;
            } else {
//                echo "Not OK";
                return false;
            }
        }
    }

    public function setManual($mode = null) {

        $mid = $this->input->post('m_id');
        $jsonx = $this->Motor_Model->getMotorJSONFromFile();
        $jsonArray = json_decode($jsonx, true);
        foreach ($jsonArray['motors'] as $x => $y) {
            if ($y['m_id'] == $mid) {
                $mcode = $y['m_code'];
                $mangle = $y['m_angle'];
                break;
            }
        }

        if ($mode == null) {
            $comm = "mc_" . $mcode . str_pad($mangle, 3, '0', STR_PAD_LEFT);
        } else {
            $comm = "mc_" . $mcode . "000";
        }
        //echo $comm;
        $this->storeCommands($comm);
    }

    public function ckk() {
        echo "hi";
    }

}
