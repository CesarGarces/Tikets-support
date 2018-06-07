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


$objCon = new Connection();
$objCon->get_connected();

$sol = isset($_REQUEST['sol']) ? $_REQUEST['sol'] : '0' ;
$estado = '';

if($sol == '0'){
	$estado = $_REQUEST['estado'];
}else{
	$estado = 'Concluido';
}

$query = "UPDATE  sys_tickets SET estado = '".$estado."' WHERE id_ticket = '".$_REQUEST['id_ticket']."'  ";
$result = mysql_query($query);
$query2 = "UPDATE  sys_tickets_resp SET contesta = '".$_REQUEST['respuesta']."', id_contesta = '".$_REQUEST['id_usuario_responde']."', fecha_contesta = now() WHERE id_ticket = '".$_REQUEST['id_ticket']."'  ";
$result = mysql_query($query2);


 
header('Location: ticket_list.php');

?>