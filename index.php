<?php 
//include_once "funciones.php";
require_once "bsd/bsdRegistroBoletin.php";


header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$respuesta = array();

error_log("serch ... estamos en este archivo", 0);
/*
$_POST['email'] = 'prueba2@correo.com';
$_POST['name'] = 'nombre de prueba 2';
$_POST['portal'] = 'registrar';
*/
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
		if( $_POST['portal'] == 'registrar' ){
			$respuesta = array( 'type' => 'error' , 'info' => 'Por el momento no contamos con sistema de registro' );
		} else if( $_POST['portal'] == 'baja' ){
			$respuesta = array( 'type' => 'error' , 'info' => 'Por el momento no contamos con sistema para dar de baja' );
		} else{
			$respuesta = array( 'type' => 'error' , 'info' => 'Por el momento no contamos con sistema' );
		}
	}

	$respuesta = array( 'state' => $respuesta );
}
elseif ( !isset( $_POST['email'] ) || $_POST['email'] == '' ) {
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El correo es un dato requerido' ) );
 } 
elseif ( !isset( $_POST['name'] ) || $_POST['name'] == '' ) {
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El nombre es un dato requerido' ) );
 } elseif( !isset( $_POST['portal'] ) || $_POST['portal'] == '' ){
 	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El portal es un dato requerido' ) );
 }
echo json_encode($respuesta);
 ?>