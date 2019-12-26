<?php
class TypeData {
	public static $tablename = "tipos";


	public function __construct(){
		$this->tipo = "";
		$this->name = "";
		$this->entidad = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (tipo,name,entidad) ";
		$sql .= "value (\"$this->tipo\",\"$this->name\",\"$this->entidad\")";
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
		return Model::one($query[0],new TypeData());
	}

	public static function getLike($q,$u){
		$sql = "select * from ".self::$tablename." where name like '%$q%' and tipo='$u'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());
	}

	public static function getAllIncome(){
		$sql = "select * from ".self::$tablename." where tipo='Ingreso' ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getAllType(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getAllExpense(){
		$sql = "select * from ".self::$tablename." where tipo='Egreso'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getAllDebts(){
		$sql = "select * from ".self::$tablename." where tipo='Deudas'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getAllStocks(){
		$sql = "select * from ".self::$tablename." where tipo='Valores'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getAllPartner($u){
		$sql = "select * from ".self::$tablename." where tipo='Socio'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}
	public static function getByType($u){
		$sql = "select * from ".self::$tablename." where tipo='$u'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());

	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new TypeData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TypeData());
	}

}

?>