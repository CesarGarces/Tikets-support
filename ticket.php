<?php
require'../users/class/sessions.php';
$objses = new Sessions();
$objses->init();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

if($user == ''){
 
   header('Location: ../index.php?error=2');
}

?>
<?php

require'../users/class/config.php';
require'../users/class/users.php';
require'../users/class/dbactions.php';

$objCon = new Connection();
$objCon->get_connected();
$objUse = new Users();
$list_users = $objUse->list_users();
$img_users = $objUse->img_users();
$img_roles = $objUse->img_users();


$row_rol=mysql_fetch_array($img_roles);
$id_rol = $row_rol['id_rol'];

date_default_timezone_set('America/Bogota');
$fecha=date('Y-m-d');
$fecha_a=date('Y');
/*************** Parametros ***************/
$agencia = isset($_REQUEST['agencia']) ? $_REQUEST['agencia'] : null ;
$region = isset($_REQUEST['region']) ? $_REQUEST['region'] : null ;
$area = isset($_REQUEST['area']) ? $_REQUEST['area'] : null ;
$mes = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : null ;
$ano = isset($_REQUEST['ano']) ? $_REQUEST['ano'] : $fecha_a ;

$id_ticket = $_REQUEST['id_ticket'];
$ticket_query = mysql_query("SELECT * FROM sys_tickets where id_ticket = '".$id_ticket."'  ");
$ticket_fet = mysql_fetch_row($ticket_query);

$ticket_resp_sql = mysql_query("SELECT * FROM sys_tickets_resp where id_ticket = '".$id_ticket."'  ");
$count_resp = mysql_num_rows($ticket_resp_sql);


?>

<!DOCTYPE html>

<html>



<head>



    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <title>CCX | Tickets</title>



    <link href="../lib/css/bootstrap.min.css" rel="stylesheet">

    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">



    <link href="../lib/css/animate.css" rel="stylesheet">

    <link href="../lib/css/style.css" rel="stylesheet">

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

        

        <div class="wrapper wrapper-content  animated fadeInRight">           
			<div class="row">
			<div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      
                        
                       <h5><b>Mensaje Original:</b></h5><span  class="pull-right"><?php echo $ticket_fet[1]; ?></span>
                    </div>
                    <div class="ibox-content">
                        <h4 class="no-margins"> <?php echo $ticket_fet[4]; ?></h4>
						<?php
							if($count_resp == 0)
							{
						?>
							<div class="pull-right"><a class="btn btn-success btn-rounded btn-xs" href="resp_ticket.php?resp=0&id_ticket=<?php echo $ticket_fet[0]; ?>"><i class="fa fa-reply"></i> Responder</a> </div> 
						<?php
							}
						?>
					  </br>
                    </div>
                </div>
            </div>

            <?php 
            $ticket_resp_query2 = mysql_query("SELECT * FROM sys_tickets_resp where id_ticket = '".$id_ticket."' ORDER BY id_tickets_resp DESC");
			$estado_respuesta = mysql_query("SELECT DISTINCT contesta FROM sys_tickets_resp WHERE id_ticket = '".$id_ticket."'");
			$count_estado_respuesta = mysql_num_rows($estado_respuesta);
			if($count_estado_respuesta == 1)
			{
				$fetch_estado_respuesta = mysql_fetch_row($estado_respuesta);
			}
			
			if($count_estado_respuesta == 1 && $fetch_estado_respuesta[0] != '')
			{
			?>
            
			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      
                        
                       <h5>Apreciación del cliente: <?php echo $fetch_estado_respuesta[2]; ?></h5>
                    </div>
                    <div class="ibox-content">
                        <h3 class="no-margins"><?php echo $fetch_estado_respuesta[0]; ?></h3>
                        
                    </div>

                </div>
            </div>  
            <?php
			}
			?>
			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       <h5>Feed del Ticket.</h5>
                    </div>
                    <?php
					$counter = 0;
					while($resp = mysql_fetch_row($ticket_resp_query2)) 
					{ 
						$counter = $counter + 1;
						$usuario_resp_1 = mysql_query("SELECT * FROM sys_usuarios where id_usuario = '".$resp[2]."'  ");
						$usuario_resp_fet_1 = mysql_fetch_row($usuario_resp_1); 
					?>
					<div class="ibox-content">
						<?php
							if($count_resp == $counter)
							{
						?>
							<h4 class="no-margins"><b>Usuario: </b><?php echo $ticket_fet[1]; ?></h4>
						<?php
							}
							else
							{
						?>
						<h4 class="no-margins"><b>Usuario: </b><?php echo $usuario_resp_fet_1[7]; ?></h4>
						<?php
							}
						?>
                        <h4 class="no-margins"><b>Respuesta: </b><?php echo $resp[3]; ?></h4>
						<h4 class="no-margins"><b>Fecha: </b><?php echo $resp[6]; ?></h4>
						<?php
						if($counter == 1 && $ticket_fet[7] != 'Concluido')
						{
						?>
                        <div class="pull-right"><a class="btn btn-success btn-rounded btn-xs" href="resp_ticket.php?resp=1&id_ticket=<?php echo $resp[1]; ?>"><i class="fa fa-reply"></i> Responder</a> </div> 
						<br>
						<?php
						}
						?>
                    </div>
					<?php
					}
					?>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      
                        
                       <h5>Documento Adjunto</h5>
                    </div>
                    <div class="ibox-content">
                      <?php 
                      $doc = substr($ticket_fet[5], -3); 
                      
                      if($doc == 'xls'){  ?>
                     
                        <h4><i class="fa fa-file-excel-o"></i> <a href="http://izoboard.net:88/sesa/archivos/<?php echo $ticket_fet[5]; ?>" target="_blank"><?php echo $ticket_fet[5]; ?></a></h4>
                      <?php }elseif($doc == 'pdf'){  ?>
                        <h4><i class="fa fa-file-pdf-o"></i> <a href="http://izoboard.net:88/sesa/archivos/<?php echo $ticket_fet[5]; ?>" target="_blank"><?php echo $ticket_fet[5]; ?></a></h4>
                      <?php }elseif($doc == 'doc'){  ?>
                        <h4><i class="fa fa-file-word-o"></i> <a href="http://izoboard.net:88/sesa/archivos/<?php echo $ticket_fet[5]; ?>" target="_blank"><?php echo $ticket_fet[5]; ?></a></h4>
                      <?php }else{  ?>
                      <h4><i class="fa fa-file-o"></i> <a href="http://izoboard.net:88/sesa/archivos/<?php echo $ticket_fet[5]; ?>" target="_blank"><?php echo $ticket_fet[5]; ?></a></h4>
                      <?php }  ?>
                    </div>
                </div>
            </div>


                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox float-e-margins">

                          <div class="col-lg-12">
                         <div class="ibox float-e-margins">
                              <div class="ibox-title">
								<?php if($ticket_fet[8] == 'Alta'){ ?>
									<span class="label label-danger pull-right"><?php echo $ticket_fet[8]; ?></span><span class="label label-info pull-right">Ticket # <?php echo $ticket_fet[0]; ?></span>
								<?php }elseif($ticket_fet[8] == 'Media'){ ?>
									<span class="label label-warning pull-right"><?php echo $ticket_fet[8]; ?></span><span class="label label-info pull-right">Ticket # <?php echo $ticket_fet[0]; ?></span>
								<?php }elseif($ticket_fet[8] == 'Baja'){ ?>
									<span class="label label-primary pull-right"><?php echo $ticket_fet[8]; ?></span><span class="label label-info pull-right">Ticket # <?php echo $ticket_fet[0]; ?></span>
								<?php } ?>	
                                 <h5>Area Responsable</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><i class="fa fa-paper-plane-o"></i> <?php echo $ticket_fet[6]; ?> </h1>                       
                              </div>
                          </div>
                      </div>

					<div class="col-lg-12">
						<?php
						if($ticket_fet[7] == 'Concluido')
						{
						?>
							<div class="col-lg-6"><a class="btn btn-primary btn-rounded"><i class="fa fa-thumbs-o-up"></i>  Sí fue Solucionado</a> </div> 
							<div class="col-lg-6"><a class="btn btn-default btn-rounded"><i class="fa fa-thumbs-o-down"></i>  No fue Solucionado</a> </div> 
						<?php
						}
						else
						{
						?>	
							<div class="col-lg-6"><a class="btn btn-success btn-rounded" href="cont_ticket_exe.php?sol=si&id_ticket=<?php echo $id_ticket; ?>"><i class="fa fa-thumbs-o-up"></i>  Sí fue Solucionado</a> </div> 
							<div class="col-lg-6"><a class="btn btn-success btn-rounded" href="cont_ticket.php?sol=no&id_ticket=<?php echo $ticket_fet[0]; ?>"><i class="fa fa-thumbs-o-down"></i>  No fue Solucionado</a> </div> 
						<?php
						}
						?>
					</div>
                      <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <!--<span class="label label-warning pull-right">Data has changed</span>-->
                        <h5>Actividad</h5>
                    </div>

                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-6">
                                <small class="stats-label">Publica</small>
                                <h5><?php echo $ticket_fet[1]; ?></h5>
                            </div>
                            <div class="col-xs-6">
                                <small class="stats-label">Fecha</small>
                                <h5><?php echo $ticket_fet[9]; ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php 
					$ticket_resp_query_act = mysql_query("SELECT * FROM sys_tickets_resp where id_ticket = '".$id_ticket."'  ");

                    while($act = mysql_fetch_array($ticket_resp_query_act)) 
					{ 
						$usuario_resp_2 = mysql_query("SELECT * FROM sys_usuarios where id_usuario = '".$act["id_responde"]."'  ");
						$usuario_resp_fet_2 = mysql_fetch_row($usuario_resp_2);
                   ?>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Publica</small>
                                <h5><?php echo $ticket_fet[1]; ?></h5>
                            </div>

                            <div class="col-xs-4">
                                <small class="stats-label">Responde</small>
                                <h5><?php echo $usuario_resp_fet_2[7]; ?></h5>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Fecha</small>
                                <h5><?php echo $act["fecha_respuesta"]; ?></h5>
                            </div>
                        </div>
                    </div>
                   <?php 
				   } 
				   ?>
                </div>
            </div>

                </div>
            </div>

			</div>
        </div>

		<div class="footer">

			<div class="pull-right">

				IZO.

			</div>

            <div>

				<strong>Powered By</strong> Synergy Contact &copy; 2015-2017

            </div>

        </div>				
          
     

         
                
<!-- Mainly scripts -->

<script src="../lib/js/jquery-2.1.1.js"></script>

<script src="../lib/js/bootstrap.min.js"></script>

<script src="../lib/js/plugins/metisMenu/jquery.metisMenu.js"></script>

<script src="../lib/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>



<!-- Custom and plugin javascript -->

<script src="../lib/js/inspinia.js"></script>

<script src="../lib/js/plugins/pace/pace.min.js"></script>





</body>



</html>

