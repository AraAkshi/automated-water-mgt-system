<!DOCTYPE html>
<html lang="en">
	<head>
		
		<title>Motors</title>
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
                        <span>Motors</span>
                    </div>
                </div>

                <div class="fullwidth-block">
                    <div class="container">
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-8">
                            <h2 class="section-title">New Motors/Pipe taps</h2>
                            <h4 class="text-muted">Enter new motor information here.</h4>
                            <form id="form_submit" action="<?php echo base_url()?>index.php/motor/storeData" class="contact-form" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <span >Motor ID: &nbsp;</span>
                                            <select class="select control" id="m_id" name="m_id">
                                                <?php
                                                    foreach ($mid as $x)
                                                        echo '<option value="'.$x.'">'.$x.'</option>';
                                                ?>
                                            </select>
                                        </div>
                                        <div class="pull-left">
                                            <input type="text" id="m_name" name="m_name" required placeholder="Motor name...">
                                        </div>
                                    </div>
                                </div>
                                <textarea name="m_desc" id="m_desc" placeholder="Motor description..."></textarea><br/><br/>

                                <div class="row">
                                    <div class="col-md-4">
                                        <h4 class="text-muted">Flowrate</h4>
                                        <div>
                                            <input type="text" id="flowrate" name="flowrate" required placeholder="Flowrate (litre/seconds)">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="text-muted">Turning Angle</h4>
                                        <div>
                                            <input type="number" id="m_angle" name="m_angle" min="0" max="180" value="90" required placeholder="Turning angle (0-180) degrees">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="msg-div" class="text-danger">

                                        </div>
                                    </div>

                                </div>
                                <br/>
                                <div class="text-right">
                                    <input type="submit" placeholder="Send message" />
                                </div>
                            </form>

                        </div>
                        <div class="col-md-2">
                            <div id="aa"></div>
                        </div>
                    </div>
                </div>

            </main> <!-- .main-content -->

            <?php $this->load->view('footer');?>
		</div>

        <?php $this->load->view('footer-js');?>
        <script>
            $('document').ready(function () {
                $('#frequent_days').hide();

            });

            //Todo validation
            $('#m_angle,#flowrate').keypress(function(event) {
                if ((event.which != 46 || $(this).val().indexOf('.') != -1)&&(event.which < 48 || event.which > 57)) {
                    //alert('hello');
                    if((event.which != 46 || $(this).val().indexOf('.') != -1)){
                        //alert('Multiple Decimals are not allowed');
                    }
                    event.preventDefault();
                }
                if(this.value.indexOf(".")>-1 && (this.value.split('.')[1].length > 3))		{
                    //alert('Two numbers only allowed after decimal point');
                    event.preventDefault();
                }
                if(Number($(this).val()) < 0 ){
                    event.preventDefault();
                }
            });
            $("#m_angle").keydown(function () {
                if(Number($('#m_angle').val()) > 180 || Number($('#m_angle').val()) < 0 ){
                    $("#msg-div").html("Please enter a value between 0-180");
                }else{
                    $("#msg-div").html("");
                }
            });


            $("#m_id").change(function () {
                $.post("<?php echo base_url();?>index.php/motor/getMotorData/",
                    {
                        m_id: $("#m_id").val()
                    },
                    function(data,status) {
                        if(data != ""){
                            $('#msg-div').html(data);
                            $('#m_name').val(jss.m_name);
                            $('#m_desc').val(jss.m_desc);
                            $('#flowrate').val(jss.flowrate);
                            $('#m_angle').val(jss.m_angle);
                        }else {
                            $('#m_name').val("");
                            $('#m_desc').val("");
                            $('#flowrate').val("");
                            $('#m_angle').val("");
                        }
                    }
                );
            });
        </script>

	</body>

</html>