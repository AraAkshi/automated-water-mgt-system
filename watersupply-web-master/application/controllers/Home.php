<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Weather_Model");
        $this->load->model("Home_Model");
        $this->load->model("Crop_Model");
    }

    public function index() {
        if ($arr = $this->getCurrentData()) {
            $this->load->view('pages/index', $arr);
        } else {
            $xx['heading'] = "Database Error";
            $xx['message'] = "No Data Available";
            $this->load->view('errors/html/error_404', $xx);
        }
    }

    public function storeWeatherDataFromAPI() {
        $json = $this->Weather_Model->getWeatherJSON();
        if ($json) {
            $x = $this->Weather_Model->storeWeatherJSON($json);
            if ($x) {
                echo "Weather data updated";
            } else {
                echo "Weather is not updated";
            }
        } else {
            echo "No Internet connection. Please connect to the internet";
        }
    }

    public function getCurrentData() {
        $json = $this->Weather_Model->getWeatherJSONFromFile();

        if ($json) { //OWM API for Colombo
            $data = json_decode($json, true);
            $arrIndex = 0;
            if (date_default_timezone_set("Asia/Colombo")) {
                $t = time();
                foreach ($data['list'] as $x => $y) {
                    if ($t >= $data['list'][$x]['dt']) {
                        $arrIndex = $x;
                    } else {
                        // echo "doesnt Match<br/>";
                        break;
                    }
                }
            }

            //    var_dump($arrIndex);
            // die;
            $cdata['city'] = array(
                "id" => $data['city']['id'],
                "name" => $data['city']['name'],
                "lat" => $data['city']['coord']['lat'],
                "lon" => $data['city']['coord']['lon'],
                "country" => $data['city']['country']
            );
            for ($i = 0; $i < 7; $i++) {
                if(!isset($data['list'][$arrIndex])){
                    $arrIndex--;
                }
                $date = Date("Y-m-d H:i:s", $data['list'][$arrIndex]['dt']);
                $temp = $this->tempConvert($data['list'][$arrIndex]['main']['temp'], 'k', 'c');
                $cdata['cdata'][$i] = array(
                    "temp" => $temp,
                    "humidity" => $data['list'][$arrIndex]['main']['humidity'],
                    "weather_icon" => $this->selectWeatherIcon($data['list'][$arrIndex]['weather'][0]['id']),
                    "weather" => $data['list'][$arrIndex]['weather'][0]['main'],
                    "weather_desc" => $data['list'][$arrIndex]['weather'][0]['description'],
                    "weather_id" => $data['list'][$arrIndex]['weather'][0]['id'],
                    "wind_speed" => $data['list'][$arrIndex]['wind']['speed'],
                    "time" => $date,
                    "timestamp" => $data['list'][$arrIndex]['dt']
                );
                $arrIndex++;
            }

            $cdata['crops'] = $this->getCropData();
            foreach ($cdata['crops'] as $x => $y) {
                $cdata['crops'][$x]['moisture'] = "0";
            }

            foreach ($this->getMoistureLevel() as $x => $y) {
                $cdata['crops'][$x]['moisture'] = $y;
            }
            //$cdata['cdata'][0]['weather_id']=789;
            return $cdata;
        } else {
            return false;
        }
    }

    function getCurrentWeatherFromSensors() {
        
    }

    public function tempConvert($temp, $typeFrom = 'k', $typeTo = 'c') {
        if ($typeFrom == 'k' && $typeTo == 'c') {
            $tmp = $temp - 273.15;
        } else if ($typeFrom == 'k' && $typeTo == 'f') {
            $tmp = ($temp - 273.15) * 9 / 5.0 + 32;
        } else if ($typeFrom == 'c' && $typeTo == 'f') {
            $tmp = ($temp * 9 / 5.0) + 32;
        } else if ($typeFrom == 'c' && $typeTo == 'k') {
            $tmp = $temp + 273.15;
        } else if ($typeFrom == 'f' && $typeTo == 'c') {
            $tmp = ($temp - 32) * 5 / 9.0;
        } else if ($typeFrom == 'f' && $typeTo == 'k') {
            $tmp = ($temp - 32) * 5 / 9.0 + 273.15;
        } else {
            return "--";
        }
        return round($tmp, 1, PHP_ROUND_HALF_UP);
    }

    public function selectWeatherIcon($weatherId) {
        if ($weatherId >= 200 && $weatherId <= 299) {
            //Thunderstorm
            if ($weatherId >= 210 && $weatherId <= 221) {
                $icon = "icon-12.svg"; //Thunderstorm
            } else {
                $icon = "icon-11.svg"; //Thunderstorm with rain
            }
        } else if ($weatherId >= 300 && $weatherId <= 399) {
            //Drizzle
            $icon = "icon-9.svg";
        } else if ($weatherId >= 500 && $weatherId <= 599) {
            //Rain
            $icon = "icon-10.svg";
        } else if ($weatherId >= 600 && $weatherId <= 699) {
            //Snow
            $icon = "icon-14.svg";
        } else if ($weatherId >= 700 && $weatherId <= 799) {
            //Atmosphere
            $icon = "icon-8.svg";
        } else if ($weatherId == 800) {
            //Clear
            $icon = "icon-2.svg";
        } else if ($weatherId >= 801 && $weatherId <= 809) {
            //Clouds
            if ($weatherId == 801) {
                $icon = "icon-3.svg";
                ; //few clouds
            } else if ($weatherId == 802) {
                $icon = "icon-5.svg"; //scattered clouds
            } else {
                $icon = "icon-6.svg"; //broken,overcasted clouds
            }
        } else if ($weatherId >= 900 && $weatherId <= 909) {
            //Extreme => Tornado,hurricane....
            $icon = "icon-8.svg";
        } else if ($weatherId >= 910 && $weatherId <= 999) {
            //Additional
            $icon = "icon-7.svg";
        } else {
            $icon = "icon-1.svg";
        }
        return $icon;
    }

    public function ck() {
        echo $this->tempConvert(300, 'k', 'c');
    }

    public function crops() {
        $this->load->view('pages/crop_reg');
    }

    public function getSensorData($mode = null) {
        $json = $this->Home_Model->getSensorDataFromFile();
        if ($json) {
            if ($mode == 'json') {
                header("Content-type: application/json");
                echo $json;
                die;
            } else {
                $data = json_decode($json, true);
                return $data;
            }
        }
    }

    public function getMoistureLevel($mode = null) {
        $json = $this->Home_Model->getMoistureJSONFromFile();
        if ($json) {
            $data = json_decode($json, true);
            foreach ($data['moisture'] as $s => $d) {
                $postData[$s] = $data['moisture'][$s]['value'];
            }
            //add as an array for more sensor values
            //$postData[1]=457;

            if ($mode == 'json') {
                //echo ' <div id="mx"></div>  ';
                echo ' 
                    <script>var jss=' . json_encode($postData) . ';</script>                    
                 ';
                //echo '<script>document.getElementById("mx").innerHTML=moisture[0];</script>';
            } else {
                return $postData;
            }
        } else {
            return false;
        }
    }

    public function getCropData() {
        $json = $this->Crop_Model->getCropJSONFromFile();
        if ($json) {
            $data = json_decode($json, true);
            $postData = $data['crops'];
            return $postData;
        } else {
            return false;
        }
    }

    function removeSchedules() {
        include "Motor.php";
        $motor = new Motor();
        $motor->storeCommands("uc_removeSchedule");
    }

    function autoSchedules() {
        include "Motor.php";
        $motor = new Motor();
        //$motor->storeCommands("uc_removeSchedule");
        //sleep(3);
        $motor->storeCommands("uc_autoSchedule");
    }

    function handleRainStatus() {
        $status = $this->input->post("rain_status");
        header("Content-type: application/json");
        if ($status == 0) {
            $this->removeSchedules();
            echo json_encode(array(
                "auto_shedule" => false 
            ));
            
        } else {
            $this->autoSchedules();
            echo json_encode(array(
                "auto_shedule" => true 
            ));
        }
    }

    public function checkRainingStatus($comm = null) {

        $wid = $this->input->post("weather_id");
        $this->storeWeatherDataFromAPI();
        return;
        
        $json = $this->Weather_Model->getWeatherJSONFromFile();
        if ($json) { //OWM API for Colombo
            $data = json_decode($json, true);
            $arrIndex = 0;
            if (date_default_timezone_set("Asia/Colombo")) {
                $t = time();
                foreach ($data['list'] as $x => $y) {
                    if ($t >= $data['list'][$x]['dt']) {
                        $arrIndex = $x;
                    } else {
                        // echo "doesnt Match<br/>";
                        break;
                    }
                }
            }
            $newId = $data['list'][$arrIndex]['weather'][0]['id'];

            if ($wid >= 500 && $wid <= 599) {
                if ($newId < 500 || $newId >= 600) {
                    $this->autoSchedules();
                    echo "<br/>Scheduled all tasks. System will work automatically";
                }
            } else {
                if ($newId >= 500 && $newId <= 599) {
                    $this->removeSchedules();
                    echo "<br/>Removed all schdeules due to Rain. System won't work automatically. Control motors manually if needed";
                }
            }
        }
    }

    public function loadForecastTable() {
        if ($arr = $this->getCurrentData()) {
            $this->load->view('pages/forcast-table', $arr);
        }
    }

}
