<?php

class BORegistrarBoletin{

	public static function obtenerUUID(){
		$uuid = random_bytes(16);
		$cadena = bin2hex(substr($uuid, 0, 4)).'-'.
	            bin2hex(substr($uuid, 4, 2)).'-'.
	            bin2hex(substr($uuid, 6, 2)).'-'.
	            bin2hex(substr($uuid, 8, 2)).'-'.
	            bin2hex(substr($uuid, 10, 6));

	    return $cadena;
	}

	public static function obtenerUniqId(){
		return bin2hex( random_bytes(16) );
	}

	public static function obtenerFechaUnix(){
		date_default_timezone_set('America/Mexico_City');
		$fechaNuevaUnix = mktime();    
    	return date('Y-m-d H:i:s', $fechaNuevaUnix);
	}
}