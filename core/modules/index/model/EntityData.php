<?php
class EntityData {
	public static $tablename = "entidades";


	public function __construct(){
		$this->name = "";
		$this->user_id = "";
		$this->created_at = "NOW()";
		$this->tipo = "";
		$this->category_id = "";
		$this->empresa = "";
		$this->active = 1;

	}
	public function getCategoryExpense(){ return CategoryExpenseData::getById($this->category_id);}
	public function getCategoryIncome(){ return CategoryIncomeData::getById($this->category_id);}
	public function getExpensesByIdEntity($id){ return ExpensesData::getByEntityId($id);}
	public function getIncomeByIdEntity($id){ return IncomeData::getByEntityId($id);}
	public function getPartnerByIdEntity($id){ return ResultData::getByEntityId($id);}
	public function getType($id){ return TypeData::getById($id);}

	public function add(){
		$sql = "insert into entidades (name,user_id,created_at,tipo,category_id,empresa, active) ";
		$sql .= "value (\"$this->name\",$this->user_id,$this->created_at,$this->tipo,$this->category_id,$this->empresa, $this->active)";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\", tipo=$this->tipo, category_id=$this->category_id, active=$this->active where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new EntityData());
	}
	
	public static function getByCategoryId($id, $type, $company){
		$sql = "SELECT * FROM ".self::$tablename." WHERE tipo in (SELECT id FROM tipos where tipo = '".$type."') and empresa = ".$company." and category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new EntityData());
	}
	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new EntityData());
	}
	public static function getByType($type, $company){
		$sql = "SELECT * FROM ".self::$tablename." WHERE tipo in (SELECT id FROM tipos where tipo = '".$type."') and empresa = ".$company;
		$query = Executor::doit($sql);
		return Model::many($query[0],new EntityData());
	}
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new EntityData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new EntityData());
	}

}

?>