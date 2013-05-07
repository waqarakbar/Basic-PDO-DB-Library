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
	  const HOST	= 'localhost';
	  const USER	= 'root';
	  const PASS	= '';
	  const DRIVER	= 'mysql';
	  const DB_NAME	= 'blog';

	  /**
	  * class constructor, will connect to the database
	  * using connect() method
	  * @access public
	  */
	  public function __construct(){
		  $this->connect();
	  }
	  
	  
	  
	  
/***----------------------------------------***/
/***---------main CRUD methods start--------***/
/***----------------------------------------***/

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
	  * update the record
	  * @param string $table name of the table
	  * @param integer $id id of the record to be updated
	  * @param array $fields array of fields and values
	  * @return bollean
	  */
	  public function update($table, $id, $fields){
		  $this->isArray($fields);
		  $this->isInt($id);
		  $sql = "UPDATE ".$table;
		  $sql .= " SET ".$this->arrayToFields($fields);
		  $sql .= " WHERE id = ".$id;
		  $prep_sql = $this->connection->prepare($sql);
		  $prep_sql->execute();
		  if($prep_sql->rowcount() == 1){
			  return true;
		  }
		  return false;
	  }
	  
	  
	  
	  /**
	  * fetch all records from a database table
	  * @param string $table table name
	  * @param array $fields optional, fields to be selected
	  * @param array $condition condition if any
	  * @return array $data array of objects
	  */
	  public function get($table, $fields = NULL, $condition = NULL){
		  $sql = "SELECT ";
		  //add fields or select *
		  if($fields == NULL){
			  $sql .= "* ";
		  }else{
			  $sql .= implode(', ', $fields);
		  }
		  //from which table
		  $sql .= " FROM ".$table;
		  //add condition if any
		  if($condition != NULL){
			  $sql .= " WHERE ".$this->arrayToCondition($condition);
		  }
		  $prep_sql = $this->connection->prepare($sql);
		  $prep_sql->execute();
		  if($prep_sql->rowCount() >= 1){
			  $data = array();
			  while($row = $prep_sql->fetch(PDO::FETCH_OBJ)){
				  $data[] = $row;
			  }
			  return $data;
		  }
		  return FALSE;
	  }
	  
	  
	  /**
	  * function to delete the record
	  * @param string $table table name
	  * @param array $condition condition for record
	  * @return boolean
	  */
	  public function delete($table, $condition){
		  $this->isArray($condition);
		  $sql = "DELETE FROM ".$table;
		  $sql .= " WHERE ".$this->arrayToCondition($condition);
		  $prep_sql = $this->connection->prepare($sql);
		  $prep_sql->execute();
		  if($prep_sql->rowCount() >= 1){
			  return TRUE;
		  }else{
			  return FALSE;
		  }
	  }
	  
/***----------------------------------------***/
/***---------main CRUD methods end----------***/
/***----------------------------------------***/




/***----------------------------------------***/
/***--------assisting methods start---------***/
/***----------------------------------------***/

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
	  * validate integer
	  * @param integer $int
	  */
	  private function isInt($int){
		  if(!is_int($int)){
			  die("the argument must be integer");
		  }
	  }
	  
/***----------------------------------------***/
/***---------assisting methods end----------***/
/***----------------------------------------***/
	  



/***----------------------------------------***/
/***--------connection methods start--------***/
/***----------------------------------------***/

	  /**
	  * function to connect to the database using PDO
	  * @access private
	  */
	  private function connect(){
		  try{
			  $this->connection = new PDO(self::DRIVER.':host='.self::HOST.';dbname='.self::DB_NAME, self::USER, self::PASS);
		  }catch(PDOException $e){
			  echo $e->getMessage();
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

/***----------------------------------------***/
/***---------connection methods end---------***/
/***----------------------------------------***/


	  /**
	  * class desctruct, will close connection
	  * @access public
	  */
	  public function __desctruct(){
		  $this->disconnect;
	  }

  }
  
//instantiate database class
$db = new Database();
?>
