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
$objUse = new Users();
$id_user_ob = $objUse->img_users();
$id_user_fet =mysql_fetch_array($id_user_ob);
$id_user = $id_user_fet['id_usuario'];
$usuario_evalua = $_REQUEST['usuario'];
$id_ticket =  $_REQUEST['id_ticket'];

$id_usuario_evaluado =mysql_query("SELECT DISTINCT id_responde FROM sys_tickets_resp WHERE id_responde <>  '".$usuario_evalua."' ORDER BY id_responde DESC LIMIT 1");
$row_usuario=mysql_fetch_row($id_usuario_evaluado);

$sql = "INSERT  INTO  sys_evaluacion_tickets VALUES (
    NULL,
    '".$id_user."',
    '".$row_usuario[0]."',
    '".$_REQUEST['id_ticket']."',
    '".$_REQUEST['enc1']."',
    '".$_REQUEST['enc2']."',
    '".$_REQUEST['enc3']."',
    '".$_REQUEST['enc4']."',
    '".$_REQUEST['enc5']."',
    '".$_REQUEST['voc']."',
    now()
    )";


  $result = mysql_query($sql);

  $query2 = "UPDATE sys_tickets set estado = 'S' where id_ticket = '".$_REQUEST['id_ticket']."' ";
  $result2 = mysql_query($query2);

	header('Location: ticket_list.php');

?>