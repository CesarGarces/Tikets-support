<?php
require'../users/class/sessions.php';
$objses = new Sessions();
$objses->init();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

if($user == ''){
   header('Location: ../index.php?error=2');
}

require'../users/class/config.php';
require'../users/class/users.php';
require'../users/class/dbactions.php';
require'../users/class/panel.php';
require'../users/class/encuestas.php';

$objCon = new Connection();
$objCon->get_connected();
$objUse = new Users();
$objEnc = new Encuesta();
$img_users = $objUse->img_users();
$img_roles = $objUse->img_users();


$row_rol=mysql_fetch_array($img_roles);
$id_rol = $row_rol['id_rol'];

$id_ticket = $_REQUEST['id_ticket'];


date_default_timezone_set('America/Bogota');
$fecha=date('Y-m-d');
$fecha_a=date('Y');
/*************** Parametros ***************/
$agencia = isset($_REQUEST['agencia']) ? $_REQUEST['agencia'] : null ;
$region = isset($_REQUEST['region']) ? $_REQUEST['region'] : null ;
$area = isset($_REQUEST['area']) ? $_REQUEST['area'] : null ;
$mes = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : null ;
$ano = isset($_REQUEST['ano']) ? $_REQUEST['ano'] : $fecha_a ;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IZO | Tickets</title>

   <link href="../lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/css/animate.css" rel="stylesheet">
    <link href="../lib/css/style.css" rel="stylesheet">
    <link href="../lib/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="../lib/css/sweetalert.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="../lib/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="../lib/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <!-- c3 Charts -->
    <link href="../lib/css/plugins/c3/c3.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../lib/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="../lib/css/plugins/iCheck/custom.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>


<body>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <?php
                                $numrows = mysql_num_rows($img_users);
                                if($numrows > 0){
                                    while($row=mysql_fetch_array($img_users)){
                                        $id_usuario = $row['id_usuario'];
                    
                                ?>

               <span>
                    <img alt="image" width="48" height="48" class="img-circle" src="<?php echo $row['imagen'];?>">
                </span>
                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION['user'];?></strong>

                </span> <span class="text-muted text-xs block"><?php echo $row['nombre'];?> <b class="caret"></b></span> </span> </a>

                <?php
                    }

                }
                ?>

                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="modify_pass.php">Cambiar Contraseña</a></li>
                                <li><a href="log_out.php">Salir</a></li>
                            </ul>
                    </div>

                    <div class="logo-element">

                       <img src="../favicon.ico" width="25" height="25">
                    </div>
                </li>

               <li>
                    <a href="dashboard.php"><i class="fa fa-desktop"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li>
                    <a href="dashboard_cx.php?mes=<?php echo $mes; ?>&ano=<?php echo $ano; ?>"><i class="fa fa-tachometer"></i> <span class="nav-label">Customer Experience</span></a>
                </li>
                <li>
                    <a <?php if($id_rol <= 6){ ?> href="#" <?php } ?>><i class="fa fa-users"></i> <span class="nav-label">Employee Experience</span></a>
                </li>
				<li class="active">
                    <a <?php if($id_rol <= 6){ ?> href="ticket_list.php" <?php } ?>><i class="fa fa-ticket"></i> <span class="nav-label">CCX Tickets</span></a>
                </li>
				<li>
                    <a <?php if($id_rol <= 6){ ?> href="dashboardv3.php" <?php } ?>><i class="fa fa-gear"></i> <span class="nav-label">Configuraciones</span></a>
                </li>
            </ul>
        </div>
    </nav>


        
<div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-success " href="#"><i class="fa fa-bars"></i> </a>
                </div>

               <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="log_out.php">
                            <i class="fa fa-sign-out"></i> Salir
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        

 <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Tickets</h2>

                    <ol class="breadcrumb">

                        <li>

                            <a href="dashboard.php">Inicio</a>

                        </li>

                        

                        <li class="active">

                            <strong>Tickets</strong>

                        </li>

                    </ol>

                </div>

            

    <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="ibox">  
                    
                    <form name="form1" method="post" >
                                      <div class="col-lg-12">

                                        <div class="ibox-content text-left">
                                                <div class="ibox-content text-left">                                               
                                                <div class="text-left">
                                                                                                           
                                                        <label label-default="" class="control-label"><h2><b>Califique de 1 a 5 lo siguiente: siendo 1 muy bajo y 5 muy alto</h2></b></label>
                                                        <input type="hidden"name="id_ticket" value="<?php echo $id_ticket; ?>">
                                                        <input type="hidden"name="usuario" value="<?php echo $_SESSION['user']; ?>">
                                                </div>
                                              </div>   

                                            
                                              

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">
                                                                                          
                                                        <label label-default="" class="control-label">1. La agilidad con la que le solucionarón</label>
                                                        <div class="controls">
                                                           <div class="i-checks">
                                                              <label> <input type="radio" name="enc1" value="1" required> <i></i> 1 </label>
                                                              <label> <input type="radio" name="enc1" value="2"> <i></i> 2 </label>
                                                              <label> <input type="radio" name="enc1" value="3"> <i></i> 3 </label>
                                                              <label> <input type="radio" name="enc1" value="4"> <i></i> 4 </label>
                                                              <label> <input type="radio" name="enc1" value="5"> <i></i> 5 </label>
                                                           </div>
                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">    
                                                                                                                                       
                                                        <label label-default="" class="control-label">2. La amabilidad con la que fue atendido el ticket.</label>
                                                        <div class="controls">
                                                          
                                                          <div class="i-checks">
                                                              <label> <input type="radio" name="enc2" value="1" required> <i></i> 1 </label>
                                                              <label> <input type="radio" name="enc2" value="2"> <i></i> 2 </label>
                                                              <label> <input type="radio" name="enc2" value="3"> <i></i> 3 </label>
                                                              <label> <input type="radio" name="enc2" value="4"> <i></i> 4 </label>
                                                              <label> <input type="radio" name="enc2" value="5"> <i></i> 5 </label>

                                                         </div>

                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">    
                                                                                                                                       
                                                        <label label-default="" class="control-label">3. El conocimiento de la persona que atendio el ticket</label>
                                                        <div class="controls">
                                                          
                                                          <div class="i-checks">
                                                              <label> <input type="radio" name="enc3" value="1" required> <i></i> 1 </label>
                                                              <label> <input type="radio" name="enc3" value="2"> <i></i> 2 </label>
                                                              <label> <input type="radio" name="enc3" value="3"> <i></i> 3 </label>
                                                              <label> <input type="radio" name="enc3" value="4"> <i></i> 4 </label>
                                                              <label> <input type="radio" name="enc3" value="5"> <i></i> 5 </label>
                                                              
                                                         </div>

                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">    
                                                                                                                                       
                                                        <label label-default="" class="control-label">4. Su satisfacción general con la solución de su ticket fue</label>
                                                        <div class="controls">
                                                          
                                                          <div class="i-checks">
                                                              <label> <input type="radio" name="enc4" value="1" required> <i></i> 1 </label>
                                                              <label> <input type="radio" name="enc4" value="2"> <i></i> 2 </label>
                                                              <label> <input type="radio" name="enc4" value="3"> <i></i> 3 </label>
                                                              <label> <input type="radio" name="enc4" value="4"> <i></i> 4 </label>
                                                              <label> <input type="radio" name="enc4" value="5"> <i></i> 5 </label>
                                                         </div>

                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">    
                                                                                                                                       
                                                        <label label-default="" class="control-label">5. Que tanto esfuerzo personal tubo que emplear para la solución de este</label>
                                                        <div class="controls">
                                                          
                                                          <div class="i-checks">
                                                              <label> <input type="radio" name="enc5" value="1" required> <i></i> 1 </label>
                                                              <label> <input type="radio" name="enc5" value="2"> <i></i> 2 </label>
                                                              <label> <input type="radio" name="enc5" value="3"> <i></i> 3 </label>
                                                              <label> <input type="radio" name="enc5" value="4"> <i></i> 4 </label>
                                                              <label> <input type="radio" name="enc5" value="5"> <i></i> 5 </label>
                                                         </div>

                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              <div class="ibox-content text-left">                                               
                                                <div class="text-left">    
                                                                                                                                       
                                                        <label label-default="" class="control-label">Observaciones</label>
                                                        <div class="controls">
                                                          
                                                          <textarea style="margin-left: 0px; margin-right: 0px; width: 100%;" name="voc"></textarea>

                                                       </div>
                                                   
                                                </div>
                                              </div>

                                              
                                                
                                                <input type="submit" class="btn btn-success btn-rounded btn-block" onclick="this.form.action = 'califi_ticket_exe.php'" value="Enviar">
                                              
                                                
                                                </div>                              
                                        </form>
                                </div>
                            </div>
                        </div>  
                    </div>  
                </div> 

                   

    <!-- Mainly scripts -->
    <script src="../lib/js/jquery-2.1.1.js"></script>
    <script src="../lib/js/bootstrap.min.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="../lib/js/inspinia.js"></script>
    <script src="../lib/js/plugins/pace/pace.min.js"></script>

    <script src="../lib/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="../lib/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../lib/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="../lib/js/plugins/flot/jquery.flot.js"></script>
    <script src="../lib/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../lib/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="../lib/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../lib/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="../lib/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="../lib/js/demo/peity-demo.js"></script>

   <!-- Data picker -->
   <script src="../lib/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="../lib/js/plugins/fullcalendar/moment.min.js"></script>

    <!-- Date range picker -->
    <script src="../lib/js/plugins/daterangepicker/daterangepicker.js"></script>

    <!-- GITTER -->
    <script src="../lib/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="../lib/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="../lib/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="../lib/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="../lib/js/plugins/toastr/toastr.min.js"></script>

    <!-- d3 and c3 charts -->
    <script src="../lib/js/plugins/d3/d3.min.js"></script>
    <script src="../lib/js/plugins/c3/c3.min.js"></script>
    <script src="../lib/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
//encuesta  /////////////
   $("#sat0").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat1").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat2").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat3").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat4").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat5").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });
   $("#sat6").click(function(evento){
         $("#0_6").css("display", "block");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "none");
   });

   $("#sat7").click(function(evento){
        $("#0_6").css("display", "none");
         $("#7_8").css("display", "block");
         $("#9_10").css("display", "none");
   });
   $("#sat8").click(function(evento){
        $("#0_6").css("display", "none");
         $("#7_8").css("display", "block");
         $("#9_10").css("display", "none");
   });
   $("#sat9").click(function(evento){
         $("#0_6").css("display", "none");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "block");
   });
   $("#sat10").click(function(evento){
         $("#0_6").css("display", "none");
         $("#7_8").css("display", "none");
         $("#9_10").css("display", "block");
   });
   //fin encuesta  ///////////

   $("#sat_can1").click(function(evento){
         $("#enc6_1_5").css("display", "block");
         
   });
   $("#sat_can2").click(function(evento){
         $("#enc6_1_5").css("display", "block");
         
   });
   $("#sat_can3").click(function(evento){
         $("#enc6_1_5").css("display", "block");
         
   });
   $("#sat_can4").click(function(evento){
         $("#enc6_1_5").css("display", "block");
         
   });
   $("#sat_can5").click(function(evento){
         $("#enc6_1_5").css("display", "block");
         
   });
   $("#sat_can6").click(function(evento){
         $("#enc6_1_5").css("display", "none");
         
   });
   $("#sat_can7").click(function(evento){
         $("#enc6_1_5").css("display", "none");
         
   });
   $("#sat_can8").click(function(evento){
         $("#enc6_1_5").css("display", "none");
         
   });
   $("#sat_can9").click(function(evento){
         $("#enc6_1_5").css("display", "none");
         
   });
   $("#sat_can10").click(function(evento){
         $("#enc6_1_5").css("display", "none");
         
   });



   $("#sat_equ1").click(function(evento){
         $("#enc7_1_5").css("display", "block");
         
   });
   $("#sat_equ2").click(function(evento){
         $("#enc7_1_5").css("display", "block");
         
   });
   $("#sat_equ3").click(function(evento){
         $("#enc7_1_5").css("display", "block");
         
   });
   $("#sat_equ4").click(function(evento){
         $("#enc7_1_5").css("display", "block");
         
   });
   $("#sat_equ5").click(function(evento){
         $("#enc7_1_5").css("display", "block");
         
   });
   $("#sat_equ6").click(function(evento){
         $("#enc7_1_5").css("display", "none");
         
   });
   $("#sat_equ7").click(function(evento){
         $("#enc7_1_5").css("display", "none");
         
   });
   $("#sat_equ8").click(function(evento){
         $("#enc7_1_5").css("display", "none");
         
   });
   $("#sat_equ9").click(function(evento){
         $("#enc7_1_5").css("display", "none");
         
   });
   $("#sat_equ10").click(function(evento){
         $("#enc7_1_5").css("display", "none");
         
   });



   $("#esf1").click(function(evento){
         $("#enc9_3_5").css("display", "block");
         
   });
   $("#esf2").click(function(evento){
         $("#enc9_3_5").css("display", "block");
         
   });
   $("#esf3").click(function(evento){
         $("#enc9_3_5").css("display", "block");
         
   });
   $("#esf4").click(function(evento){
         $("#enc9_3_5").css("display", "none");
         
   });
   $("#esf5").click(function(evento){
         $("#enc9_3_5").css("display", "none");
         
   });


   $("#eco1").click(function(evento){
         $("#econo_f_g").css("display", "none");
         
   });
   $("#eco2").click(function(evento){
         $("#econo_f_g").css("display", "none");
         
   });
   $("#eco3").click(function(evento){
         $("#econo_f_g").css("display", "none");
         
   });
   $("#eco4").click(function(evento){
         $("#econo_f_g").css("display", "none");
         
   });
   $("#eco5").click(function(evento){
         $("#econo_f_g").css("display", "none");
         
   });
   $("#eco6").click(function(evento){
         $("#econo_f_g").css("display", "block");
         
   });
   $("#eco7").click(function(evento){
         $("#econo_f_g").css("display", "block");
         
   });

   $("#enc3-1").click(function(evento){
         $("#enc3_1_3").css("display", "block");
         
   });
   $("#enc3-2").click(function(evento){
         $("#enc3_1_3").css("display", "block");
         
   });
   $("#enc3-3").click(function(evento){
         $("#enc3_1_3").css("display", "block");
         
   });
   $("#enc3-4").click(function(evento){
         $("#enc3_1_3").css("display", "none");
         
   });
   $("#enc3-5").click(function(evento){
         $("#enc3_1_3").css("display", "none");
         
   });
   $("#enc3-6").click(function(evento){
         $("#enc3_1_3").css("display", "none");
         
   });
   $("#enc3-7").click(function(evento){
         $("#enc3_1_3").css("display", "none");
         
   });
            });
        </script>
    

</body>

</html>
