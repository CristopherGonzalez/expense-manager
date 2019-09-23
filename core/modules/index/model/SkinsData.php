<?php
class SkinsData {
	public static $tablename = "skins";


	public function SkinsData(){
		$this->name = "";
		$this->value = "";
	}

	public function add(){
		$sql = "insert into skins (name,value) ";
		$sql .= "value (\"$this->name\",\"$this->value\")";
		return Executor::doit($sql);
	}

	public static function delete($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SkinsData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new SkinsData());

	}

}

?>