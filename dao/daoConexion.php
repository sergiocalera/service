<?php
	class Conexion extends PDO{
		private $tipo_de_base = 'mysql';
		private $host = 'localhost';
		private $nombre_de_base = 'phplistdb';
		private $usuario = 'root';
		private $contrasena = ''; 
		
		public function __construct() {
			//Sobreescribo el método constructor de la clase PDO.
			try{
				parent::__construct(
					$this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena
				);
			}catch(PDOException $e){
				throw $e;
				exit;
			}
		} 
	}