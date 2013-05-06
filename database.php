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
		const DB_NAME	= 'cp_blog';

		/**
		* class constructor, will connect to the database using connect()
		* @access public
		*/
		public function __construct(){
			//$this->connect();
			try{
				$this->connection = new PDO('mysql:host=localhost;dbname=cp_blog', self::USER, self::PASS);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			//var_dump($this->connection);
		}


		/**
		* function to add record to database
		* @param string $table table name of database
		* @param array $data field value pairs of data
		* @access public
		*/
		public function insert($table, $data){
			$sql = "INSERT INTO ".$table;
			$sql .= " SET ".$this->arrayToFields($data);
			//echo $sql;
			$prep_sql = $this->connection->prepare($sql);
			$prep_sql->execute();
			if($prep_sql->rowcount() == 1){
				return true;
			}
			return false;
		}




		/**
		* function to convert array to fields value pair for query
		* @param array $array associative array of fields and values
		* @access private
		* @return string $fields fields and value pairs
		*/
		private function arrayToFields($array){
			$this->isArray($array);
			$num_elements = count($array);
			$loop_count = 1;
			$fields = "";
			foreach ($array as $field => $value) {
				$fields .= $field." = '".$value."'";
				if ($loop_count < $num_elements) {
					$fields .= ", ";
				}
				$loop_count++;
			}
			return $fields;
		}








		/**
		* function to convert array to conditions
		* @param array $array
		* @access private
		* @return string a condition to be used in query
		*/
		private function arrayToCondition($array){
			$this->isArray($array);
			$num_elements = count($array);
			$loop_count = 1;
			$condition = "";
			foreach ($array as $field => $value) {
				$condition .= $field." = ".$value;
				if($loop_count < $num_elements){
					$condition .= " AND ";
				}
				$loop_count++;
			}
			return $condition;
			
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
			//$this->disconnect;
		}

	}



//testing database class
$db = new Database();
$data = array(
			'title'=>'testing',
			'body'=>'alskdfjaskdfj',
			'created'=>'2013-02-05'
		);
if($db->insert('posts', $data) == true){
	echo 'record inserted';
}else{
	echo "failed";
}
?>
