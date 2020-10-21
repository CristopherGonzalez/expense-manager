<?php
class CityData {
	public static $tablename = "ciudad";


	public function __construct(){
		$this->name = "";
		$this->id_pais = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,id_pais) ";
		$sql .= "value (\"$this->name\",\"$this->id_pais\")";
		return Executor::doit($sql);
	}

	public static function delete($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}

	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CityData());
	}

	public static function getByIdCountry($id_country){
		$sql = "select * from ".self::$tablename." where id_pais=$id_country";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CityData());

	}

	public static function getLike($q,$u){
		$sql = "select * from ".self::$tablename. " where  (LOWER(name) LIKE LOWER('%" . $q . "%'))  and user_id=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CityData());
	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new CityData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CityData());
	}

}

?>