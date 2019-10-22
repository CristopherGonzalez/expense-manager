<?php
class CategoryIncomeData {
	public static $tablename = "category_income";


	public function CategoryIncomeData(){
		$this->name = "";
		$this->user_id = "";
		$this->tipo = "";
		$this->created_at = "NOW()";
		$this->empresa = "";
	}
	public function getTypeIncome(){ return TypeData::getById($this->tipo);}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,user_id,tipo,created_at,empresa) ";
		$sql .= "values (\"$this->name\",\"$this->user_id\",$this->tipo,$this->created_at,$this->empresa)";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\", tipo=$this->tipo where id=$this->id";
		$query = Executor::doit($sql);
		if (isset($query)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryIncomeData());
	}

	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryIncomeData());

	}
	
	public static function getLike($q,$u){
		$sql = "select * from ".self::$tablename." where name like '%$q%' and empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryIncomeData());
	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryIncomeData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryIncomeData());
	}

}

?>