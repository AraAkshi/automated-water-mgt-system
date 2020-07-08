<!DOCTYPE html>
<html lang="en">
    <head>

        <title>WaterSupply</title>
        <?php $this->load->view('header-css'); ?>
        <style type="text/css" media="all">
            .text-block {
                position: absolute;
                bottom: 10px;
                left: 12px;
                width: 45%;
                background-color: rgba(24, 24, 24, 0.61);
                color: white;
                padding: 10px;
                border-radius: 5px 5px;
            }

            #info-box {
                display: none;
            }

            .water-progress {
                position: absolute;
                top: 0px;
                left: 10px;
                color: rgba(75, 75, 75, 0.95);
                padding: 5px;
                font-weight: 600;
            }

        </style>
    </head>

    <body>

        <div class="site-content">

            <?php $this->load->view('nav-bar'); ?>
            <div id="msgx" class="text-center"></div>
            <div class="hero" data-bg-image="<?php echo base_url() ?>weather/images/banner.png">
                <!--<div class="container">
                    <form action="#" class="find-location">
                        <input type="text" placeholder="Find your location...">
                        <input type="submit" value="Find">
                    </form>

                </div>-->
            </div>

            <div id="forcast_table">
                <?php $this->load->view('pages/forcast-table'); ?>
            </div>


            <main class="main-content">
                <div class="fullwidth-block">
                    <div class="container">
                        <h2 class="section-title">Crop Lands</h2>
                        <div class="row">
                            <?php
                            foreach ($crops as $c => $d) {
                                echo '    <div class="col-md-6">
                                                <div class="photo">
                                                    <div class="photo-preview photo-detail" data-bg-image="' . base_url() . 'weather/images/photo-1.jpg"></div>
                                                    <div class="text-block">                                                       
                                                        <div id="div-moisture-' . $c . '"></div>
                                                    </div>
                                                    <div id="" class="progress-block water-progress" data-type="fill">
                                                        <div class="wrapper">
                                                            <div class="green">
                                                                <div id="progress_' . $c . '" class="progress">
                                                                    <div class="inner">
                                                                        <div id="percent_' . $c . '" class="percent"><span>80</span>%</div>
                                                                        <div class="water"></div>
                                                                        <div class="glare"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="photo-details">
                                                        <h3 class="photo-title">' . $d['crop_name'] . '<span class="pull-right"><small>' . $d['area'] . ' ' . $d['area_unit'] . '</small></span></h3>
                                                        Description <i id="info_box_ic_' . $c . '" class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <div id="info-box-' . $c . '">
                                                            <small>' . $d['desc'] . '</small>
                                                        </div>
                                                        <div>Schedule:
                                                            <small>(frequent - ' . $d['frequent_days'] . ' day)</small>
                                                            <ul style="margin-left: 20px;"> ';
                                foreach ($d['water'] as $x => $y) {
                                    echo "<li>" . $y['time'] . " - " . $y['litre'] . "<q>L</q></li>";
                                    echo "";
                                }
                                echo '</ul>
                                                        </div>
                                                        <br><input type="hidden" id="mid_' . $c . '" value="' . $d['motor'] . '">
                                                        <div class="pull-right" style="position: absolute;bottom: 10px;right: 10px;">
                                                            <input type="checkbox" id="toggle_water_' . $c . '" data-toggle="toggle" data-onstyle="danger"
                                                                   data-offstyle="success" data-off="<i class=\'fa fa-play\'></i> Water "
                                                                   data-on="<i class=\'fa fa-stop\'></i> Stop "/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                            }
                            ?>

                        </div>
                    </div>
                </div>


            </main> <!-- .main-content -->
            <div id="json_holder"></div>
            <?php $this->load->view('footer'); ?>
        </div>

        <?php $this->load->view('footer-js'); ?>
        <script>
            $("document").ready(function () {
<?php
for ($i = 0; $i <= $c; $i++) {
    echo '  
                    $("#info-box-' . $i . '").hide();';
}
?>
                int_set_moisture = setInterval(function () {
                    setSensorData();
                }, 1000);

//                int_set_moisture= setInterval(function () {
//                    for(i=0;i<=<?php echo $c ?>;i++){
//                        setMoisture(i);
//                    }
//                },1000);
                checkWeather();
                int_weather = setInterval(function () {
                    checkWeather();
                }, (30 * 60 * 1000)); //Automatically update weather and rain contoling
//                },(5000)); //Automatically update weather and rain contoling
            });

<?php
for ($i = 0; $i <= $c; $i++) {
    echo '$("#toggle_water_' . $i . '").change(function () {
                        if ($(this).prop("checked")) { 
                            $("#prg-' . $i . '").show();
                            $("#div-moisture-' . $i . '").html("Watering manually");
                            $.post("' . base_url() . 'index.php/motor/setManual",
                                {
                                    m_id: $("#mid_' . $i . '").val()
                                },
                                function(data,status) {
                                    //alert(data);
                                }
                            );
                                    
                        } else {
                            $("#div-moisture-' . $i . '").html("");
                            $("#prg-' . $i . '").hide();
                            $.post("' . base_url() . 'index.php/motor/setManual/off",
                                {
                                    m_id: $("#mid_' . $i . '").val()
                                },
                                function(data,status) {
                                   //alert(data);
                                }
                            );
                        }
                    });';
}
?>

<?php
for ($i = 0; $i <= $c; $i++) {
    echo ' 
                        $("#info_box_ic_' . $i . '").mouseover(function () {
                            $("#info-box-' . $i . '").show();
                        });
                        $("#info_box_ic_' . $i . '").mouseleave(function () {
                            $("#info-box-' . $i . '").hide();
                        });
                    ';
}
?>

            function setMoisture(id) {
                $.post("<?php echo base_url(); ?>index.php/home/getMoistureLevel/json",
                        {
                            //m_id: $("#m_id").val()
                        },
                        function (data, status) {
                            if (data != "") {
                                $('#json_holder').html(data);
                                vals = jss[id] == undefined ? "0" : jss[id];
                                $('#moisture_' + id + '').html(vals);
                                //alert(id +" : "+vals);
                                setProgressValue(id, vals);
                            } else {
                                $('#moisture_' + id + '').html("");
                            }
                        }
                );
            }

            function setSensorData() {
                $.post("<?php echo base_url(); ?>index.php/home/getSensorData/json", {}, function (data, status) {
                    if (typeof data == "object") {
                        setMoistureData(data);
                        setTemperatureData(data);
                        setHumidityData(data);
                        checkRainStatus(data);
                    } else {
                        $('#moisture_' + id + '').html("");
                    }
                });
            }


            function setTemperatureData(data) {
                data = data.temp;
                $(data).each(function (index, item) {
                    $("#forcast_table .today").find(".tempr").html(item);
                });
            }

            function setHumidityData(data) {
                data = data.humi;
                $(data).each(function (index, item) {
                    $("#forcast_table .today").find(".humid").html(item);
                });
            }

            function setMoistureData(data) {
                data = data.mois;
                for (var i = 0; i <= <?php echo $c ?>; i++) {
                    var val = data[i] == undefined ? "0" : data[i];
                    $('#moisture_' + i + '').html(val);
                    setProgressValue(i, val);
                }
            }

            var rainFlag = false;
            var postFlag = false;
            function checkRainStatus(data) {
                var status = data.rain[0];
                if (status == 0 && rainFlag === false) {
                    postFlag = true;
                } else if (status > 0 && rainFlag === true) {
                    postFlag = true;
                }
                if (postFlag) {
                    postFlag = false;
                    $.post("<?php echo base_url(); ?>index.php/home/handleRainStatus",
                            {
                                rain_status: status
                            },
                            function (data, status) {
                                var txt = "";
                                if (data.auto_shedule) {
                                    txt = "<br/>Scheduled all tasks. System will work automatically";
                                    rainFlag = false;
                                } else {
                                    txt = "<br/>Removed all schdeules due to Rain. System won't work automatically. Control motors manually if needed";
                                    rainFlag = true;
                                }
                                $("#msgx").html(txt);
                            }
                    );


                }


            }

            function checkWeather() {
                $.post("<?php echo base_url(); ?>index.php/home/checkRainingStatus",
                        {
                            weather_id: $('#weather_id').val()
                        },
                        function (data, status) {
                            //$("#msgx").html(data);
                            $('#forcast_table').load("<?php echo base_url(); ?>index.php/home/loadForecastTable");
                        }
                );
                if ($('#weather_id').val() >= 500 && $('#weather_id').val() <= 599) {
                    //alert('Rainy day');
                }
            }

        </script>
        <script>

            function setProgressValue(id, value) {
                var val = value;
                var colorInc = 100 / 3;

                if (val != ""
                        && !isNaN(val)
                        && val <= 100
                        && val >= 0)
                {
                    var valOrig = val;
                    val = 100 - val;

                    if (valOrig == 0)
                    {
                        $("#progress_" + id + " .percent").text(0 + "%");
                    } else
                        $("#progress_" + id + " .percent").text(valOrig + "%");

                    $("#progress_" + id + "").parent().removeClass();
                    $("#progress_" + id + " .water").css("top", val + "%");

                    if (valOrig < colorInc * 1)
                        $("#progress_" + id + "").parent().addClass("red");
                    else if (valOrig < colorInc * 2)
                        $("#progress_" + id + "").parent().addClass("orange");
                    else
                        $("#progress_" + id + "").parent().addClass("green");
                } else
                {
                    $("#progress_" + id + "").parent().removeClass();
                    $("#progress_" + id + "").parent().addClass("green");
                    $("#progress_" + id + " .water").css("top", 100 - 67 + "%");
                    $("#progress_" + id + " .percent").text(67 + "%");
                }
            }
        </script>
    </body>
</html>