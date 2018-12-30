<?php

define( "RUTDB", "localhost" );
define( "USERDB", "root" );
define( "PASSWORD", "" );
define( "DATABASE", "phplistdb" );

function obtenerDatos( $name, $email, $portal ){
	
	date_default_timezone_set('America/Mexico_City');

	$respuesta = array();

	$uniqid = bin2hex( random_bytes(16) );

	$uuid = random_bytes(16);
	$cadena = bin2hex(substr($uuid, 0, 4)).'-'.
            bin2hex(substr($uuid, 4, 2)).'-'.
            bin2hex(substr($uuid, 6, 2)).'-'.
            bin2hex(substr($uuid, 8, 2)).'-'.
            bin2hex(substr($uuid, 10, 6));

    $fechaNuevaUnix = mktime();    
    $fechaUnix = date('Y-m-d H:i:s', $fechaNuevaUnix);

    if( is_valid_email( $email ) ){
	    if( !findEmail( $email ) ){
		    registrarCorreo( $name, $email, $uniqid, $cadena, $fechaUnix );
		    $id = getId( $email );
		    insertarListUser( $id, $fechaUnix, $portal );
		    insertarUserAttribute( $id, $name );
		    $respuesta = array( 'state' => array( 'type' => 'success' ) );
	    } else {
	    	$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'El correo ya se encuentra registrado' ) );
	    }
	} else{
		$respuesta = array( 'state' => array( 'type' => 'error', 'info' => 'No es un correo valido' ) );
	}
	return $respuesta;
	//return $respuesta = array( 'state' => array( 'type' => 'success' ) );
	//return $respuesta = array( 'state' => array( 'type' => 'success', 'uniqid' => $uniqid , 'uuid' => $cadena, 'fechaUnix' => $fechaUnix ) );
}

function registrarCorreo( $name, $email, $uniqid, $uuid, $fechaUnix ){

	$conexion = mysqli_connect( RUTDB, USERDB, PASSWORD, DATABASE );
//	$db = mysql_select_db("phplistdb",$conexion);

	$vCero = 0;
	$vUno = 1;
	$vDos = 2;

	$stmt = mysqli_prepare( $conexion, "INSERT INTO phplist_user_user(email, confirmed, blacklisted, optedin, bouncecount, entered, modified, uniqid, uuid, htmlemail, subscribepage, disabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );

	mysqli_stmt_bind_param($stmt, 'siiiissssiii', $email, $vUno, $vCero, $vCero, $vCero, $fechaUnix, $fechaUnix, $uniqid, $uuid, $vUno, $vUno, $vCero);

	mysqli_stmt_execute($stmt);

	mysqli_stmt_close($stmt);
	mysqli_close($conexion);

	
}

function getId( $email ){

	$conexion = mysqli_connect( RUTDB, USERDB, PASSWORD, DATABASE );

	$stmtNewValue = mysqli_prepare( $conexion, "SELECT id FROM phplist_user_user WHERE email = ?");

	mysqli_stmt_bind_param($stmtNewValue, 's', $email );

	mysqli_stmt_execute($stmtNewValue);

	mysqli_stmt_bind_result($stmtNewValue, $id );

	mysqli_stmt_fetch($stmtNewValue);

	mysqli_stmt_close($stmtNewValue);
	mysqli_close($conexion);

	return $id;
}

function findEmail( $email ){

	$respuesta = false;
	$conexion = mysqli_connect( RUTDB, USERDB, PASSWORD, DATABASE );

	$stmtFindEmail = mysqli_prepare( $conexion, "SELECT uuid FROM phplist_user_user WHERE email = ?");

	mysqli_stmt_bind_param($stmtFindEmail, 's', $email );

	mysqli_stmt_execute($stmtFindEmail);

	mysqli_stmt_bind_result($stmtFindEmail, $uuid );

	mysqli_stmt_fetch($stmtFindEmail);

	mysqli_stmt_close($stmtFindEmail);
	mysqli_close($conexion);

	if( strlen( $uuid ) >= 36 ){
		$respuesta = true;
	}
	return $respuesta;
}

function insertarListUser( $id, $fechaUnix, $portal ){

	$listaPortal = 0;
	if( $portal === 'boletin' ){
		$listaPortal = 3;
	} elseif( $portal === 'cete' ) {
		$listaPortal = 4;
	}

	$conexion = mysqli_connect( RUTDB, USERDB, PASSWORD, DATABASE );

	$stmtPhplistListuser = mysqli_prepare( $conexion, "INSERT INTO phplist_listuser(userid, listid, entered, modified) VALUES( ?, ?, ?, ?)" );

	mysqli_stmt_bind_param($stmtPhplistListuser, 'iiss', $id, $listaPortal, $fechaUnix, $fechaUnix );

	mysqli_stmt_execute($stmtPhplistListuser);

	mysqli_stmt_close($stmtPhplistListuser);
	mysqli_close($conexion);
}

function insertarUserAttribute( $idUser, $nombre ){

	$vUno = 1;
	$vDos = 2;

	$conexion = mysqli_connect( RUTDB, USERDB, PASSWORD, DATABASE );
	$stmtPhplistUserAttribute = mysqli_prepare( $conexion, "INSERT INTO phplist_user_user_attribute(attributeid, userid, value) VALUES( ?, ?, ? )" );

	mysqli_stmt_bind_param($stmtPhplistUserAttribute, 'iis', $vUno, $idUser, $nombre );

	mysqli_stmt_execute($stmtPhplistUserAttribute);

	mysqli_stmt_close($stmtPhplistUserAttribute);
	mysqli_close($conexion);
}

function is_valid_email( $email ){
	return ( false !== filter_var( $email, FILTER_VALIDATE_EMAIL ) );
}