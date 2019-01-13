<?php 
//include_once "funciones.php";
require_once "bsd/bsdRegistroBoletin.php";


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$respuesta = array();

error_log("serch ... estamos en este archivo", 0);
$_POST['email'] = 'prueba2@correo.com';
$_POST['name'] = 'nombre de prueba 2';
$_POST['portal'] = 'registrar';

if( isset( $_POST['email'] ) && isset( $_POST['name'] ) && isset( $_POST['portal'] ) && $_POST['name'] != '' && $_POST['email'] != '' && $_POST['portal'] != '' ){

	$userUser = new UserUser();
	$userUser->nombre = $_POST['name'];
	$userUser->email = $_POST['email'];
	try{
		if( $_POST['portal'] == 'registrar' ){
			$respuesta = registrarBoletin( $userUser );
		} else if( $_POST['portal'] == 'baja' ){
			$respuesta = eliminarBoletin( $userUser );
		}
	}catch( Exception $e ){
		error_log( '[File: ' . $e->getFile() . '] [Line: ' . $e->getLine() . '] [Detalle: ' . $e->getMessage() . ' ]', 0 );
		if( $respuesta != NULL && $respuesta['type'] != NULL ){
			$respuesta['type'] = 'error';
		}
		if( $respuesta != NULL && $respuesta['info'] != NULL ){
			$respuesta['info'] = 'Por el momento';
		}
	}

	// var_dump($respuesta);echo '<br/><br/>';
	$respuesta = array( 'state' => $respuesta );
	// var_dump($respuesta);echo '<br/><br/>';
	// $respuesta = obtenerDatos( $_POST['name'], $_POST['email'], $_POST['portal'] );
	error_log('serch ... de todos modos pasamos por obtenerDatos', 0);
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