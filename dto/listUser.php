<?php
	class ListUser{

		private $userid;
		private $listid;
		private $entered;
		private $modified;

		const TABLA = 'phplist_listuser';

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