<?php
  
	/**
	* database class using PDO
	* @author Waqar Akbar
	* @license Fuck Coppy Rights
	*/
	class Database {

		/**
		* holds the database connection
		* @var resource $connection
		* @access private
		*/
		private $connection;

		/**
		* constants for PDO connectio
		*/
		const HOST 		= 'localhost';
		const USER 		= 'root';
		CONST PASS 		= '';
		const DRIVER	= 'mysql';
		const DB_NAME	= '';

		/**
		* class constructor, will connect to the database using connect()
		* @access public
		*/
		public function __construct(){
			$this->connect();
		}








		/**
		* function to convert array to conditions
		* @param array $array
		* @access private
		* @return string a condition to be used in query
		*/
		private function arrayToCondition($array){
			$this->isArray($array);
			
		}





		/**
		* function to check whether provided argument is array
		* @param array $array
		* @return array will return array if the provided argument is array
		* other wise it will die
		* @access private
		*/
		private function isArray($array){
			if(!is_array($array)){
				die("The provided argument is not an array");
			}
		}


		/**
		* function to connect to the database using PDO
		* @access private
		*/
		private function connect(){
			try{
				$this->connection = new PDO(self::DRIVER.':host='.self::HOST.';dbname='.self::DB_NAME, self::USER, self::PASS);
			}catch(PDOException $e){
				echo $e->getMessage();
				die();
			}
		}

		/**
		* function to disconnect from database
		* @access private
		*/
		private function disconnect(){
			if($this->connection != NULL){
				$this->connection = NULL;
			}
		}


		/**
		* class desctruct, will close connection
		* @access public
		*/
		public function __desctruct(){
			$this->disconnect;
		}

	}

?>
