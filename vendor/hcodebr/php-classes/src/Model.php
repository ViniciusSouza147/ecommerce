<?php 

	namespace Hcode;

	class Model{

		private $values = [];
		
		public function __call($name, $args){

			$method = substr($name, 0, 3);
			$fieldname = substr($name, 3, strlen($name));

			var_dump($method, $fieldname);
			exit;

		}

	}

 ?>