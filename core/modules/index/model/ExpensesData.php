<?php
class ExpensesData {
	public static $tablename = "expenses";


	public function __construct(){
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
		$this->document_number = "";
		$this->documento = "";
		$this->pago = "";
		$this->pagado_con = "";
		$this->empresa = "";
	}

	public function getCategory(){ return CategoryExpenseData::getById($this->category_id);}
	public function getEntity(){ return EntityData::getById($this->entidad);}
	public function getTypeExpense(){ return TypeData::getById($this->tipo);}

	public function add(){
		$sql = "insert into expenses (description, amount, upload_receipt, user_id, category_id,tipo, entidad, created_at, fecha, pagado,document_number, documento, pago,pagado_con, empresa) ";
		$sql .= "value (\"$this->description\",$this->amount,\"$this->upload_receipt\",$this->user_id,$this->category_id,$this->tipo,$this->entidad,$this->created_at,\"$this->fecha\",$this->pagado,'$this->document_number','$this->documento','$this->pago','$this->pagado_con',$this->empresa)";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",category_id=\"$this->category_id\",fecha=\"$this->fecha\", tipo=$this->tipo, entidad=$this->entidad, pagado='$this->pagado',pagado_con='$this->pagado_con', document_number='$this->document_number'";
		if(isset($this->documento) && !empty($this->documento)){
			$sql.=", documento = '$this->documento' ";
		}
		if(isset($this->pago) && !empty($this->pago)){
			$sql.=", pago = '$this->pago' ";
		}
		$sql.=" where id=$this->id";
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

	public static function getByEntityId($id){
		$sql = "select * from ".self::$tablename." where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}
	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());

	}

	public static function getAllCount($u){
		$sql = "select COUNT(id) as count from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());

	}
	public static function sumExpenses_Month($month,$u,$year=null){
		if(!isset($year) || $year==null) { $year = date('Y');}
		$sql = "select SUM(amount) as total from ".self::$tablename." where year(fecha) = '$year' and month(fecha)= '$month' and empresa=$u ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}

	public static function sumExpenses($u){
		$year=date('Y');
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}
	public static function sumExpensesByPaymentStatusByDate($id_company, $paid_out,$month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$id_company and pagado=$paid_out and year(fecha) = '$year' and month(fecha)= '$month'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}
	public static function sumExpensesByType($u, $type,$month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and tipo=$type and year(fecha) = '$year' and month(fecha)= '$month'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}
	public static function sumExpensesByTypeAndPayment($u, $type,$month,$year, $paid_out){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and tipo=$type and year(fecha) = '$year' and month(fecha)= '$month' and pagado=$paid_out ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}
	public static function ExpensesByTypeAndDate($u, $type,$month,$year){
		$sql = "select * from ".self::$tablename." where empresa=$u and tipo=$type and year(fecha) = '$year' and month(fecha)= '$month'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
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
	
	public static function dinamycQuery($sWhere){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}
	public static function expensesByCategoryTypeAndDate($id_company, $type,$month,$year){
		$sql = "select category_id, sum(amount)as amount, (select name from category_expense where id = category_id) as description, tipo from ".self::$tablename." WHERE empresa=$id_company and tipo=$type and year(fecha) = '".$year."' and month(fecha) = '".$month."' group by category_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}
	public static function expensesCategoryByTypeAndPayment($id_company, $type,$category_id,$month,$year, $paid_out){
		$sql = "select category_id, sum(amount)as amount, (select name from category_expense where id = category_id) as description, tipo from ".self::$tablename." WHERE empresa=$id_company and tipo=$type and year(fecha) = '".$year."' and month(fecha) = '".$month."' and pagado=$paid_out and category_id=$category_id group by category_id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ExpensesData());
	}
}

?>