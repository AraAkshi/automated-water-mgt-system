<!DOCTYPE html>
<html lang="en">
	<head>
		
		<title>Crops</title>
        <?php $this->load->view('header-css');?>
        <style>
            .lpw{

            }
        </style>
	</head>

	<body>
		
		<div class="site-content">

            <?php $this->load->view('nav-bar');?>

            <main class="main-content">
                <div class="container">
                    <div class="breadcrumb">
                        <a href="index.html">Home</a>
                        <span>Crops</span>
                    </div>
                </div>

                <div class="fullwidth-block">
                    <div class="container">
                        <div class="col-md-2">
                            <?php //var_dump($cid);?>
                        </div>
                        <div class="col-md-8">
                            <form action="<?php echo base_url()?>index.php/crop/ck" class="contact-form" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <h2 class="section-title">New Crops</h2>
                                            <h4 class="text-muted">Enter new crop data here.</h4>
                                        </div>
                                        <div class="pull-right">
                                            <span >Crop ID: &nbsp;</span>
                                            <select class="select control" id="c_id" name="c_id">
                                                <?php
                                                foreach ($cid as $x)
                                                    echo '<option value="'.$x.'">'.$x.'</option>';
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-6"><input type="text" id="c_name" name="c_name" required placeholder="Crop name..."></div>
                                    <div class="col-md-4"><input type="text" id="c_area" name="c_area" required placeholder="Crop Area (Acre/Perches)"></div>
                                    <div class="col-md-2">
                                        <select class="select control" id="c_area_unit" name="c_area_unit">
                                            <option value="Perches">Perches</option>
                                            <option value="Acres">Acres</option>
                                        </select>
                                    </div>
                                </div>
                                <textarea name="c_desc" id="c_desc" placeholder="Crop description..."></textarea><br/><br/>
                                <h4 class="text-muted">Watering Information</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-4">
                                            <div class="text-center" style="margin-top: 10px;">
                                                <span >Frequency: &nbsp;</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span><input id="frequent_days" required value="1" min="1" name="frequent_days" type="number" placeholder="Days" ></span>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-center" style="margin-top: 10px;">
                                                Day(s)
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <span >Select Motor: &nbsp;</span>
                                            <select class="select control" id="c_motor" name="c_motor">
                                                <?php
                                                foreach ($mid as $x)
                                                    echo '<option value="'.$x.'">'.$x.'</option>';
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">Watering Time<br/><br/>
                                        <div id="time_1" class="row">
                                            <div class="col-md-4">
                                                <input type="time" step="1" name="w_time_1" id="w_time_1" required value="06:00">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="lpw" name="w_litre_1" id="w_litre_1" required placeholder="Liters per watering">
                                            </div>
                                        </div>

                                        <div id="time_2"></div>
                                        <div class="pull-right"> <button type="button" id="add_time" ><i class="fa fa-plus"></i></button></div>
                                    </div>
                                </div>
                                <br/>
                                <div class="text-right">
                                    <input type="submit" placeholder="Send message" />
                                </div>
                            </form>

                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </div>

            </main> <!-- .main-content -->
            <div id="msg-div"></div>
            <?php $this->load->view('footer');?>
		</div>

        <?php $this->load->view('footer-js');?>
        <script>
            $('document').ready(function () {

            });

            $('#w_frequent').change(function () {
                if (this.value == "every"){
                    $('#frequent_days').show();
                }else{
                    $('#frequent_days').hide();
                }
            });
            var xt=1,xtx=2;
            $('#add_time').click(function () {

                if($('#w_time_'+xt).val()== "" || $('#w_litre_'+xt).val()== ""){
                    alert("Plaese select the time and litre");
                }else{
                    var flag=true;
                    for(var x=0;x<xt;x++){
                        if($('#w_time_'+xt).val()== $('#w_time_'+x).val()){
                            flag=false;
                            break;
                        }
                    }
                    if(flag){
                        xt++;
                        var html='  <div id="time_'+xt+'" class="row">\n' +
                            '           <div class="col-md-4">\n' +
                            '               <input type="time" step="1" name="w_time_'+xt+'" required id="w_time_'+xt+'" value="06:00">\n' +
                            '           </div>\n' +
                            '           <div class="col-md-4">\n' +
                            '               <input type="text" name="w_litre_'+xt+'" required class="lpw" id="w_litre_'+xt+'" placeholder="Liters per watering">\n' +
                            '           </div>\n' +
                            '       </div>\n'+
                            '       <div id="time_'+(xt+1)+'" class="row"></div>';


                        $('#time_'+xt+'').html(html);
                    }else{
                        alert("Time is already selected\nPlease provide different time");
                    }
                }

            });
            $('#w_time_1').change(function(){

            });



            //Todo validation
            $('#c_area,#frequent_days, .lpw').keypress(function(event) {

                if ((event.which != 46 || $(this).val().indexOf('.') != -1)&&(event.which < 48 || event.which > 57)) {
                    //alert('hello');
                    if((event.which != 46 || $(this).val().indexOf('.') != -1)){
                        //alert('Multiple Decimals are not allowed');
                    }
                    event.preventDefault();
                }
                if(this.value.indexOf(".")>-1 && (this.value.split('.')[1].length > 1))		{
                    //alert('Two numbers only allowed after decimal point');
                    event.preventDefault();
                }
                if(Number($(this).val()) < 0 ){
                    event.preventDefault();
                }
            });

            $("#c_id").change(function () {
                $.post("<?php echo base_url();?>index.php/crop/getCropData/",
                    {
                        c_id: $("#c_id").val()
                    },
                    function(data,status) {
                        if(data != ""){
                            //alert(data);
                            $('#msg-div').html(data);
                            $('#c_name').val(jss.crop_name);
                            $('#c_area').val(jss.area);
                            $('#c_area_unit').val(jss.area_unit);
                            $('#c_desc').val(jss.desc);
                            $('#frequent_days').val(jss.frequent_days);
                            $('#c_motor').val(jss.motor);
                            for(i=0;i<jss.water.length;i++){
                                var time=jss.water[i].time;
                                var litre=jss.water[i].litre;
                                $('#w_time_'+(i+1)+'').val(time);
                                $('#w_litre_'+(i+1)+'').val(litre);
                            }
                        }else {
                            $('#c_name').val("");
                            $('#c_area').val("");
                            //$('#c_area_unit').val("Perches");
                            $('#c_desc').val("");
                            $('#frequent_days').val("1");
                            //$('#c_motor').val("");
                            $('#w_time_1').val("06:00");
                            $('#w_litre_1').val("");
                        }
                    }
                );
            });

        </script>

	</body>

</html>