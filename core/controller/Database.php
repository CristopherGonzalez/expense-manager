<?php
class Database {
	public static $db;
	public static $con;
	function __construct(){
		$this->user="root";$this->pass="password";$this->host="localhost";$this->ddbb="minegocio";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
