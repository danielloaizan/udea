#!/usr/bin/php -q
<?php
// Ya tenemos la variable $cedula con el valor a consultar
set_time_limit(30);
$param_error_log = '/tmp/script1.log';
$param_debug_on = 1;
require('phpagi.php');
$agi = new AGI();
$agi->answer();
sleep(1);
$agi->exec_agi("googletts.agi,\"Bienvenido al sistemas de pago de facturas\",es");

//Conexión a la base de datos
require("definiciones.inc"); //Variables de entorno MariaDB
$link = mysql_connect(MAQUINA, USUARIO,CLAVE); 
mysql_select_db(DB, $link);

$agi->exec_agi("googletts.agi,\"Por favor ingrese el número de cédula\",es");
$result = $agi->get_data('beep', 5000, 15, '#'); //Ingresando cada dígito de la cédula
$cedula = $result['result'];
$query = 'SELECT saldo FROM facturas WHERE cedula='.$cedula;
//Consulta a la base de datos
$result = mysql_query($query, $link); 
$row = mysql_fetch_array($result);

if (!$row['saldo']){
	$agi->exec_agi("googletts.agi,\"No se encontró la cédula u ocurrió algún error\",es");
}else{
	$agi->exec_agi("googletts.agi,\"El saldo de su factura es ".$row['saldo']." pesos\",es");
	// $agi->exec_agi("googletts.agi,\"El saldo de su factura es ".$result." pesos\",es");
}
// while ($row = mysql_fetch_array($result)){ 
// 	$agi->exec_agi("googletts.agi,\"".$row['nombre']."\",es");
// 	sleep(1);
// }
sleep(2);
$agi->exec_agi("googletts.agi,\"Gracias por utilizar el sitema de audiorespuesta, hasta pronto\",es");
sleep(1);
$agi->exec_agi("googletts.agi,\"Hasta pronto\",es");
sleep(2);
$agi->hangup();
?>