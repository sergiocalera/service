<?php

require_once 'daoConexion.php';
require_once '../dto/user_user.php';
require_once '../dto/listUser.php';
require_once '../dto/user_blacklist.php';
require_once '../dto/user_blacklist_data.php';

class DAOSuspenderBoletin {

	public static function buscarRegistro( UserUser $userUser, $tipoLista ){
		
		/*
		* Valores por referencia del Objeto User para ser tomados por la clase PDO
		*/
		$strEmail = $userUser->email;

		$conexion = new Conexion();

		$consulta = $conexion->prepare("SELECT A.id, A.email, A.confirmed, A.blacklisted, A.optedin, A.bouncecount, A.entered, A.modified, A.uniqid, A.uuid, A.htmlemail, A.subscribepage, A.disabled FROM phplist_user_user AS A INNER JOIN phplist_user_user_attribute AS B ON A.id = B.userid AND B.attributeid = 1 WHERE A.email = :email");

		$consulta->bindParam( ':email', $strEmail, PDO::PARAM_STR );

		$consulta->execute();

		$registro = $consulta->fetch();

		if($registro){
			$userUser->id = $registro['id'];
			$userUser->email = $registro['email'];
			$userUser->confirmed = $registro['confirmed'];
			$userUser->blacklisted = $registro['blacklisted'];
			$userUser->optedin = $registro['optedin'];
			$userUser->bouncecount = $registro['bouncecount'];
			$userUser->entered = $registro['entered'];
			$userUser->modified = $registro['modified'];
			$userUser->uniqid = $registro['uniqid'];
			$userUser->uuid = $registro['uuid'];
			$userUser->htmlemail = $registro['htmlemail'];
			$userUser->subscribepage = $registro['subscribepage'];
			$userUser->disabled = $registro['disabled'];

		}else{
			$userUser->uuid = null;
		}
		return $userUser;
	}

	public static function actualizarUserUser( UserUser $userUser ){
		/*
		* Valores por referencia del objeto User para ser tomados por la clase PDO
		*/
		$idListUserUser = $userUser->id;
		$confirmed = $userUser->confirmed;
		$blacklisted = $userUser->blacklisted;
		$modified = $userUser->modified;

		$conexion = new Conexion();

		$consulta = $conexion->prepare('UPDATE ' . $userUser::TABLA .' SET confirmed = :confirmed, blacklisted = :blacklisted, modified = :modified WHERE id = :idListUserUser');
		
		try{
			$consulta->bindParam( ':idListUserUser', $idListUserUser );
			$consulta->bindParam( ':confirmed', $confirmed );
			$consulta->bindParam( ':blacklisted', $blacklisted );
			$consulta->bindParam( ':modified', $modified );

			$consulta->execute();
			
		} catch( PDOExecption $e ){
        	echo "=======  Error!: " . $e->getMessage() . "<br/>";
		}


		$conexion = null;

		return $userUser;
	}

	public static function actualizarListUser( ListUser $listUser ){
		$userid = $listUser->userid;
		$listid = $listUser->listid;
		$modified = $listUser->modified;

		$conexion = new Conexion();

		$consulta = $conexion->prepare('UPDATE ' . $listUser::TABLA .' SET modified = :modified WHERE userid = :userid AND listid = :listid');

		try{

			$consulta->bindParam( ':userid', $userid );
			$consulta->bindParam( ':listid', $listid );
			$consulta->bindParam( ':modified', $modified );

			$consulta->execute();

		} catch( PDOExecption $e ){
        	echo "=======  Error!: " . $e->getMessage() . "<br/>"; 
		}

		$conexion = null;

		return $listUser;
	}

	public static function agregarUserABlackList( UserBlackList $userBlackList ){

		$email = $userBlackList->email;
		$added = $userBlackList->added;

		$conexion = new Conexion();
		$consulta = $conexion->prepare('INSERT INTO ' . $userBlackList::TABLA . ' ( email, added ) VALUES ( :email, :added )');

		try{
			$consulta->bindParam( ':email', $email );
			$consulta->bindParam( ':added', $added );

			$consulta->execute();

		} catch( PDOExecption $e ){
			echo "=======  Error!: " . $e->getMessage() . "<br/>";
		}
	}

	public static function agregarUserABlackListData( UserBlackListData $userBlackListData ){

		$email = $userBlackListData->email;
		$name = $userBlackListData->name;
		$data = $userBlackListData->data;

		$conexion = new Conexion();
		$consulta = $conexion->prepare('INSERT INTO ' . $userBlackListData::TABLA . ' ( email, name, data ) VALUES ( :email, :name, :data )');

		try{
			$consulta->bindParam( ':email', $email );
			$consulta->bindParam( ':name', $name );
			$consulta->bindParam( ':data', $data );

			$consulta->execute();

		} catch( PDOExecption $e ){
			echo "=======  Error!: " . $e->getMessage() . "<br/>";
		}
	}
}