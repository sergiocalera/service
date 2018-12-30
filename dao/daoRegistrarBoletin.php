<?php

require_once 'daoConexion.php';
require_once '../dto/user_user.php';
require_once '../dto/listUser.php';
require_once '../dto/user_user_attribute.php';
require_once '../dto/user_blacklist.php';

class DAORegistrarBoletin {

	public static function buscarRegistro( UserUser $userUser, $tipoLista ){
		
		/*
		* Valores por referencia del Objeto User para ser tomados por la clase PDO
		*/
		$strEmail = $userUser->email;

		$conexion = new Conexion();

		// $consulta = $conexion->prepare("SELECT A.id, A.email, A.confirmed, A.blacklisted, A.optedin, A.bouncecount, A.entered, A.modified, A.uniqid, A.uuid, A.htmlemail, A.subscribepage, A.disabled FROM phplist_user_user AS A INNER JOIN phplist_listuser AS B ON A.id = B.userid INNER JOIN phplist_user_user_attribute AS C ON A.id = C.userid AND C.attributeid = 1 WHERE B.listid = :tipoLista AND A.email = :email");

		$consulta = $conexion->prepare("SELECT A.id, A.email, A.confirmed, A.blacklisted, A.optedin, A.bouncecount, A.entered, A.modified, A.uniqid, A.uuid, A.htmlemail, A.subscribepage, A.disabled FROM phplist_user_user AS A INNER JOIN phplist_user_user_attribute AS B ON A.id = B.userid AND B.attributeid = 1 WHERE A.email = :email");

		//$consulta->bindParam( ':tipoLista', $tipoLista, PDO::PARAM_INT );
		$consulta->bindParam( ':email', $strEmail, PDO::PARAM_STR );
		//$consulta->bindParam( ':email', $strVariable, PDO::PARAM_STR );

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

	public static function insertarUserUser( UserUser $userUser ){
		
		/*
		* Valores por referencia del objeto User para ser tomados por la clase PDO
		*/
		$email = $userUser->email; 
		$confirmed = $userUser->confirmed; 
		$blacklisted = $userUser->blacklisted; 
		$optedin = $userUser->optedin; 
		$bouncecount = $userUser->bouncecount; 
		$entered = $userUser->entered; 
		$modified = $userUser->modified; 
		$uniqid = $userUser->uniqid; 
		$uuid = $userUser->uuid; 
		$htmlemail = $userUser->htmlemail; 
		$subscribepage = $userUser->subscribepage; 
		$disabled = $userUser->disabled;

		$conexion = new Conexion();

		$consulta = $conexion->prepare('INSERT INTO ' . $userUser::TABLA .' ( email, confirmed, blacklisted, optedin, bouncecount, entered, modified, uniqid, uuid, htmlemail, subscribepage, disabled ) VALUES( :email, :confirmed, :blacklisted, :optedin, :bouncecount, :entered, :modified, :uniqid, :uuid, :htmlemail, :subscribepage, :disabled )');
		
		try{
			$consulta->bindParam( ':email', $email );
			$consulta->bindParam( ':confirmed', $confirmed );
			$consulta->bindParam( ':blacklisted', $blacklisted );
			$consulta->bindParam( ':optedin', $optedin );
			$consulta->bindParam( ':bouncecount', $bouncecount );
			$consulta->bindParam( ':entered', $entered );
			$consulta->bindParam( ':modified', $modified );
			$consulta->bindParam( ':uniqid', $uniqid );
			$consulta->bindParam( ':uuid', $uuid );
			$consulta->bindParam( ':htmlemail', $htmlemail );
			$consulta->bindParam( ':subscribepage', $subscribepage );
			$consulta->bindParam( ':disabled', $disabled );

			$consulta->execute();
			
			$userUser->id = $conexion->lastInsertId() + 0;
		} catch( PDOExecption $e ){
        	echo "=======  Error!: " . $e->getMessage() . "<br/>"; 
		}


		$conexion = null;

		echo "<br/><br/>serch ... terminamos con el metodo de: insertarUserUser()<br/><br/>";
		return $userUser;
	}

	public static function insertarListUser( ListUser $listUser ){

		$userid = $listUser->userid;
		$listid = $listUser->listid;
		$entered = $listUser->entered;
		$modified = $listUser->modified;

		$conexion = new Conexion();
		echo "<br/><br/>";
		var_dump( $listUser );
		echo "<br/><br/>";

		$consulta = $conexion->prepare('INSERT INTO ' . $listUser::TABLA .' ( userid, listid, entered, modified ) VALUES( :userid, :listid, :entered, :modified )');

		try{

			//$conexion->beginTransaction();

			$consulta->bindParam( ':userid', $userid );
			$consulta->bindParam( ':listid', $listid );
			$consulta->bindParam( ':entered', $entered );
			$consulta->bindParam( ':modified', $modified );

			$consulta->execute();

		} catch( PDOExecption $e ){
			//$conexion->rollback(); 
        	echo "=======  Error!: " . $e->getMessage() . "<br/>"; 
		}

		$conexion = null;

		echo "serch ... terminamos con el metodo de: insertarListUser()<br/><br/>";
		return $listUser;
	}

	public static function insertarUserUserAttribute( UserUserAttribute $userUserAttribute ){

		$attributeid = $userUserAttribute->attributeid;
		$userid = $userUserAttribute->userid;
		$value = $userUserAttribute->value;

		$conexion = new Conexion();

		$consulta = $conexion->prepare('INSERT INTO ' . $userUserAttribute::TABLA .' ( attributeid, userid, value ) VALUES( :attributeid, :userid, :value )');

		try{

			//$conexion->beginTransaction();

			$consulta->bindParam( 'attributeid', $attributeid );
			$consulta->bindParam( 'userid', $userid );
			$consulta->bindParam( 'value', $value );

			$consulta->execute();

		} catch( PDOExecption $e ){
			//$conexion->rollback(); 
        	echo "=======  Error!: " . $e->getMessage() . "<br/>"; 
		}

		$conexion = null;

		echo "serch ... terminamos con el metodo de: insertarUserUserAttribute()<br/><br/>";
		return $userUserAttribute;
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

		echo "<br/><br/>serch ... terminamos con el metodo de: insertarUserUser()<br/><br/>";
		return $userUser;
	}

}



