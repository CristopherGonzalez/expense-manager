<?php
class ExpensesData {
	public static $tablename = "expenses";


	public function ExpensesData(){
		$this->description = "";
		$this->amount = "";
		$this->upload_receipt = "";
		$this->user_id = "";
		$this->category_id = "";
		$this->tipo = "";
		$this->entidad = "";
		$this->created_at = "NOW()";
		$this->fecha = "";
		$this->pagado = "0";
		$this->documento = "";
		$this->pago = "";
	}

	public function getCategory(){ return CategoryExpenseData::getById($this->category_id);}
	public function getEntity(){ return EntityData::getById($this->entidad);}
	public function getTypeExpense(){ return TypeData::getById($this->tipo);}

	public function add(){
		$sql = "insert into expenses (description, amount, upload_receipt, user_id, category_id,tipo, entidad, created_at, fecha, pagado, documento, pago) ";
		$sql .= "value (\"$this->description\",$this->amount,\"$this->upload_receipt\",$this->user_id,$this->category_id,$this->tipo,$this->entidad,$this->created_at,\"$this->fecha\",$this->pagado,'$this->documento','$this->pago')";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",category_id=\"$this->category_id\",fecha=\"$this->fecha\", tipo=$this->tipo, entidad=$this->entidad, pagado='$this->pagado', documento='$this->documento', pago='$this->pago' where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function getByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where user_id=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());

	}

	public static function getAllCount($u){
		$sql = "select COUNT(id) as count from ".self::$tablename." where user_id=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());

	}
	
	public static function sumExpenses_Month($month,$u){
		$year=date('Y');
		$sql = "select SUM(amount) as total from ".self::$tablename." where year(created_at) = '$year' and month(created_at)= '$month' and user_id=$u ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function sumExpenses($u){
		$sql = "select sum(amount) as amount from ".self::$tablename." where user_id=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}

}

?>