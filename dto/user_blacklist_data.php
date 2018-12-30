<?php
	class UserBlackListData{

		private $email;
		private $name;
		private $data;

		const TABLA = 'phplist_user_blacklist_data';

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