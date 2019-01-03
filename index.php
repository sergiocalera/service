<?php 
//include_once "funciones.php";
include_once "bsd/bsdRegistroBoletin.php";


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$respuesta = array();

if( isset( $_POST['email'] ) && isset( $_POST['name'] ) && isset( $_POST['portal'] ) && $_POST['name'] != '' && $_POST['email'] != '' && $_POST['portal'] != '' ){

	//$respuesta = array( 'state' => array( 'type' => 'success' ) );
	$userUser = new UserUser();
	$userUser->nombre = $_POST['name'];
	$userUser->email = $_POST['email'];
	if( $_POST['portal'] == 'registrar' ){
		registrarBoletin( $userUser );
	} else if( $_POST['portal'] == 'baja' ){
		eliminarBoletin( $userUser );
	}

	$respuesta = obtenerDatos( $_POST['name'], $_POST['email'], $_POST['portal'] );
}
elseif ( !isset( $_POST['email'] ) || $_POST['email'] == '' ) {
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El correo es un dato requerido' ) );
 } 
elseif ( !isset( $_POST['name'] ) || $_POST['name'] == '' ) {
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El nombre es un dato requerido' ) );
 } elseif( !isset( $_POST['portal'] ) || $_POST['portal'] == '' ){
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El portal es un dato requerido' ) );
 }
//$respuesta = array( "correo" => "sol@correo.com" );
echo json_encode($respuesta);
 ?>