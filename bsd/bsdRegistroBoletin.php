<?php

require_once 'bo/boRegistrarBoletin.php';
require_once 'dao/daoRegistrarBoletin.php';

function registrarBoletin( UserUser $user ){

	/* Se dejara la opcion 3 como id por defecto de la lista de Boletin */
	$idBoletin = 3;

	$user = DAORegistrarBoletin::buscarRegistro( $user, $idBoletin );
	$respuesta = array( 'type' => 'success', 'info' => NULL );

	if( $user->uuid == null ){

		$user->confirmed = 1;
		$user->blacklisted = 0;
		$user->optedin = 0;
		$user->bouncecount = 0;
		$user->entered = BORegistrarBoletin::obtenerFechaUnix();
		$user->modified = BORegistrarBoletin::obtenerFechaUnix();
		$user->uniqid = BORegistrarBoletin::obtenerUniqId();
		$user->uuid = BORegistrarBoletin::obtenerUUID();
		$user->htmlemail = 1;
		$user->subscribepage = 1;
		$user->disabled = 0;

		$user = DAORegistrarBoletin::insertarUserUser( $user );
		
		$listUser = new ListUser();
		$listUser->userid = $user->id;
		$listUser->listid = $idBoletin;
		$listUser->entered = BORegistrarBoletin::obtenerFechaUnix();
		$listUser->modified = BORegistrarBoletin::obtenerFechaUnix();

		$listUser = DAORegistrarBoletin::insertarListUser( $listUser );

		$userUserAttribute = new UserUserAttribute();
		$userUserAttribute->attributeid = 1;
		$userUserAttribute->userid = $user->id;
		$userUserAttribute->value = $user->nombre;

		$userUserAttribute = DAORegistrarBoletin::insertarUserUserAttribute( $userUserAttribute );

	} elseif( $user->uuid != null && $user->blacklisted == 1 ){
		/*
		-- Para volver a dar de alta debemos de cambiar los valores de user_user
		-- confirmed = 1
		-- blacklist = 0
		-- modified = fecha actual

		-- para listuser se debe de agregar y actualizar las fechas y los id correspondientes:

		user_blacklist:
		Se debe de eliminar el correo y fecha de esta tabla

		user_blacklist_data
		Se debe de eliminar el correo name y data

		 */
		
		$user->confirmed = 1;
		$user->blacklisted = 0;
		$user->modified = BORegistrarBoletin::obtenerFechaUnix();
		$user = DAORegistrarBoletin::actualizarUserUser( $user );
		
		$listUser = new ListUser();
		$listUser->userid = $user->id;
		$listUser->listid = $idBoletin;
		$listUser->modified = BORegistrarBoletin::obtenerFechaUnix();

		$listUser = DAORegistrarBoletin::actualizarListUser( $listUser );

		$userBlackList = new UserBlackList();
		$userBlackList->email = $user->email;

		if( DAORegistrarBoletin::quitarUserBlackList( $userBlackList ) ){
			$userBlackListData = new UserBlackListData();
			$userBlackListData->email = $userBlackList->email;
			DAORegistrarBoletin::quitarUserBlackListData( $userBlackListData );
		}

	}else{
		$respuesta['type'] = 'error';
		$respuesta['info'] = 'El correo ya se encuentra registrado';
	}

	return $respuesta;
}


function eliminarBoletin( UserUser $user ){
	require_once 'dao/daoSuspenderBoletin.php';
	/*
	-- Para dar de baja se debe de poner el user como
	-- confirmed = 0
	-- blacklist = 1
	-- modified = fecha actual

	-- para la phplist_listuser se debede quitar la asociacion que puede tener con respecto a las listas creadas

	-- user_blacklist
	-- Se debe de agregar el correo y fecha a esta tabla

	-- user_blacklist_data
	--	Se debe de agregar el correo name y data
	 */
	

	/* Se dejara la opcion 3 como id por defecto de la lista de Boletin */
	$idBoletin = 3;
	$respuesta = array( 'type' => 'success', 'info' => NULL );
	$user = DAOSuspenderBoletin::buscarRegistro( $user, $idBoletin );
	if( $user->uuid != null ){
		$user->confirmed = 0;
		$user->blacklisted = 1;
		$user->modified = BORegistrarBoletin::obtenerFechaUnix();
		$user = DAOSuspenderBoletin::actualizarUserUser( $user );

		$listUser = new ListUser();
		$listUser->userid = $user->id;
		$listUser->listid = $idBoletin;
		$listUser = DAOSuspenderBoletin::actualizarListUser( $listUser );

		$userBlackList = new UserBlackList();
		$userBlackList->email = $user->email;
		$userBlackList->added = BORegistrarBoletin::obtenerFechaUnix();
		$userBlackList = DAOSuspenderBoletin::agregarUserABlackList( $userBlackList );
		
		$userBlackListData = new UserBlackListData();
		$userBlackListData->email = $user->email;
		$userBlackListData->name = 'reason';
		$userBlackListData->data = 'Puesto en lista negra por solicitud web enviado por el usuario';
		$userBlackListData = DAOSuspenderBoletin::agregarUserABlackListData( $userBlackListData );
	}

	return $respuesta;
}


