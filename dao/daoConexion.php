<?php
	class Conexion extends PDO{
		private $tipo_de_base = 'mysql';
		private $host = 'localhost';
		private $nombre_de_base = 'phplistdb';
		private $usuario = 'root';
		private $contrasena = 'a'; 
		
		public function __construct() {
			//Sobreescribo el método constructor de la clase PDO.
			try{
				parent::__construct(
					$this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena
				);
			}catch(PDOException $e){
				error_log( '[File: ' . $e->getFile() . '] [Line: ' . $e->getLine() . '] [Detalle: ' . $e->getMessage() . ' ]', 0 );
				exit;
			}
		} 
	}