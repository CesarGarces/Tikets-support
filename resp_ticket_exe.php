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


$query2 = "INSERT INTO sys_tickets_resp (`id_tickets_resp`,`id_ticket`, `id_responde`, `respuesta`,`contesta`, `id_contesta`, `fecha_respuesta`, `fecha_contesta`) VALUES (NULL, '".$_POST['id_ticket']."', '".$_POST['id_usuario_responde']."', '".$_POST['respuesta']."', null, null, now(), null ) ";
$result = mysql_query($query2);

$id_usuario_crea = $_POST['id_usuario_crea'];
$usuario_mail = mysql_query("SELECT * FROM sys_usuarios where id_usuario = '".$id_usuario_crea."'  ");
$usuario_mail_fet = mysql_fetch_array($usuario_mail);

$email = $usuario_mail_fet['email'];
$detalle = $_POST['respuesta'];
$id_ticket = $_POST['id_ticket'];

$link = 'http://izoboard.net:88/sesa/private/ticket.php?id_ticket='.$id_ticket.' ';


require_once('../lib/phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer;
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 587; // or 465
$mail->IsHTML(true);
$mail->Username = "izoboard.mail@gmail.com";
$mail->Password = "izoboard123*";
$mail->SetFrom('izoboard.mail@gmail.com', 'IZO - Tickets');
$mail->CharSet = 'UTF-8';
$mail->AddReplyTo('izoboard.mail@gmail.com', 'IZO - Tickets');
$mail->Subject = "Respuesta de el ticket ".$id_ticket;

$mail->AddAddress($email);

$mail->Body = ('
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/custom.css" rel="stylesheet">
    <link href="http://izoboard.net:88/izo/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <title></title>
    
	<style>
  .myClass{ font-family: Carta; }
</style>

</head>

<body>

<table style="background-color: #f6f6f6; width: 100%;">
    <tr>
        <td></td>
        <td style="display: block !important; max-width: 600px !important; margin: 0 auto !important; /* makes it centered */ clear: both !important;" width="600">
            <div style="max-width: 600px; margin: 0 auto; display: block; padding: 20px;">
                <table style="background: #fff; border: 1px solid #e9e9e9; border-radius: 3px;" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 20px;">
                            <table align="center" cellpadding="0" cellspacing="0">
                             <tr>
                               <table width="100%" align="center">
                                <tr align="center">
                                    <td align="left">
                                        <img  src="http://izoboard.net:88/izo/lib/img/portada.png"/> 
                                                                            
                                    </td>

                                </tr>
                                </table>
                                   
                                </tr>
                                <tr>
                                    <td style="padding: 0 0 20px;">
									<br>
										<font style="font-family: Sans-Serif;">
                                        <h3><b>
                                        Estimado/a, 
                                        </b></h3>
                                        <p>
                                        El ticket '.$id_ticket.', tiene la siguiente respuesta: </p>
                                        <p style="font-size:1.5em">
                                        <b>
                                        '.$detalle.'
                                        </b>
                                        <br>
                                        </p>
										
										<p style="font-size:1.3em">
                                        <img src="http://izoboard.net:88/izo/lib/img/like.png"> No te olvides de Calificar nuestro servicio. Nuestra meta es mejorar día a día.
                                        </p>
                                        <p style="font-size:1.3em">
                                        <img src="http://izoboard.net:88/izo/lib/img/like.png"> Revisar y dar por concluido o no, ingresando en el siguiente link.
                                        </p>
                                      
                                        
                                        </p>
                                        
                                </tr>
                                <tr align="center">
                                    <td style="padding: 0 0 20px; align:"center"; background-color:"1A5276";">
                                        <a href="'.$link.'" style="text-decoration: none; color: #FFF; background-color: #1A5276; border: solid #1A5276; border-width: 5px 10px; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize;">Revisar respuesta</a>
                                        <font style="font-family: carta;">
                                            <p> Gracias por elegirnos.</p>
                                            <br>
                                            <img src="http://izoboard.net:88/izo/lib/img/logo-izo.png"/>
                                            <p style="font-size:0.9em;">Cualquier inquietud adicional por favor comunicarse con
                                            <p style="font-size:0.9em;">Cesar Garces - <font color="blue">cesar.garces@izo.com.co</font> - Alejandro Fernandez - <font color="blue">alejandro.fernandez@izo.com.co</font></p>
                                        </font>
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div style="width: 100%; clear: both; color: #999; padding: 20px;">
                    <table width="100%">
                        <tr align="center">
                            
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
 ');

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }




header('Location: ticket_list.php');

?>