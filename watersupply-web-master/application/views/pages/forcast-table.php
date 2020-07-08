<div class="forecast-table" >
    <div class="container">
        <div class="forecast-container">
            <div class="today forecast">
                <div class="forecast-header">
                    <div class="day"><?php echo Date("l", $cdata[0]['timestamp']); ?></div>
                    <span class="text-center"><?php echo Date("H:i"); ?></span>
                    <div class="date"><?php echo Date("j M", $cdata[0]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="location">
                        <span class="forecast-icon pull-right">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[0]['weather_icon'] ?>"
                             alt="" width=90>
                        </span>
                    </div>
                    <div>
                        <span style="font-size: 20px;font-weight: 500">
                            <?php echo $cdata[0]['weather']; ?>
                        </span>
                        <br/>
                        <input type="hidden" value="<?php echo $cdata[0]['weather_id']; ?>" id="weather_id">
                        <span style="font-size: 14px">
                            <?php echo $cdata[0]['weather_desc']; ?>
                        </span>
                    </div>

                    <div class="degree">
                        <div class="num">
                            <span class="tempr">
                                <?php echo $cdata[0]['temp']; ?>
                            </span><sup>o</sup>C
                        </div>
                    </div>
                    <span title="Humidity">
                        <img width="40" src="<?php echo base_url() ?>weather/images/humidity.png" alt=""/>
                        <span class="humid"><?php echo $cdata[0]['humidity']; ?></span>%
                    </span>
                    <span title="Wind speed">
                        <img src="<?php echo base_url() ?>weather/images/icon-wind.png"alt="">
                        <?php echo $cdata[0]['wind_speed']; ?>km/h
                    </span>
                    <br/>
                    <div class="pull-right"><?php echo $city['name']; ?></div>
                </div>
            </div>

            <!-- forecast area-->

            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[1]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[1]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[1]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[1]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[1]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[1]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[1]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[2]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[2]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[2]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[2]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[2]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[2]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[2]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[3]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[3]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[3]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[3]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[3]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[3]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[3]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[4]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[4]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[4]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[4]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[4]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[4]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[4]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[5]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[5]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[5]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[5]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[5]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[5]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[5]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
            <div class="forecast">
                <div class="forecast-header">
                    <div class="date"><?php echo Date("j M", $cdata[6]['timestamp']); ?></div>
                    <div class="day"
                         style="font-size: 14px;"><?php echo Date("H:i", $cdata[6]['timestamp']); ?></div>
                </div> <!-- .forecast-header -->
                <div class="forecast-content">
                    <div class="forecast-icon">
                        <img src="<?php echo base_url() . 'weather/images/icons/' . $cdata[6]['weather_icon'] ?>"
                             alt="" width=48>
                    </div>
                    <div><?php echo $cdata[6]['weather']; ?></div>
                    <div class="degree"><?php echo $cdata[6]['temp']; ?><sup>o</sup>C</div>
                    <div style="font-size: 12px">
                        <div title="Humidity"><img width="18"
                                                   src="<?php echo base_url() ?>weather/images/humidity.png"
                                                   alt=""> <?php echo $cdata[6]['humidity']; ?>%
                        </div>
                        <div title="Wind speed"><img width="18"
                                                     src="<?php echo base_url() ?>weather/images/icon-wind.png"
                                                     alt=""> <?php echo $cdata[6]['wind_speed']; ?>km/h
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>