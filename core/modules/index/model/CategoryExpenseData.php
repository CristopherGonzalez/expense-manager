<?php
class CategoryExpenseData {
	public static $tablename = "category_expence";


	public function CategoryExpenseData(){
		$this->name = "";
		$this->user_id = "";
		$this->created_at = "NOW()";
		$this->tipo = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,user_id,created_at,tipo) ";
		$sql .= "value (\"$this->name\",$this->user_id,$this->created_at,$this->tipo)";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\",tipo=\"$this->tipo\", where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryExpenseData());
	}

	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where user_id=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());

	}

	public static function getLike($q,$u){
		$sql = "select * from ".self::$tablename." where name like '%$q%' and user_id=$u" ;
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());
	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryExpenseData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());
	}

}

?>