<?php
	class UserUser{

		private $id;
		private $email;
		private $confirmed;
		private $blacklisted;
		private $optedin;
		private $bouncecount;
		private $entered;
		private $modified;
		private $uniqid;
		private $uuid;
		private $htmlemail;
		private $subscribepage;
		private $disabled;

		private $nombre;

		const TABLA = 'phplist_user_user';

		public function __construct(){}

		public function __set( $property, $value ){
			if( property_exists( $this , $property ) ){
				$this->$property = $value;
			}
		}

		public function __get( $property ){
			if( property_exists( $this , $property ) ){
				return $this->$property;
			}
			return null;
		}
	}