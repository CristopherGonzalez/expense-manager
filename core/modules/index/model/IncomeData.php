<?php
/**
 * 
 */
class IncomeData {
	public static $tablename = "income";


	public function __construct(){
		$this->description = "";
		$this->amount = "";
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
		$this->active = 1;
		$this->payment_date = "00/00/0000";
		$this->payment_specific_date = "00/00/0000";
	}

	public function getCategory(){ return CategoryIncomeData::getById($this->category_id);}
	public function getEntity(){ return EntityData::getById($this->entidad);}
	public function getTypeIncome(){ return TypeData::getById($this->tipo);}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, amount, user_id, category_id,tipo, entidad, created_at, fecha, pagado,document_number, documento, pago, pagado_con, empresa, active, payment_date, payment_specific_date) ";
		$sql .= "value (\"$this->description\",$this->amount,$this->user_id,$this->category_id,$this->tipo,$this->entidad,$this->created_at,'$this->fecha',$this->pagado,'$this->document_number','$this->documento','$this->pago','$this->pagado_con',$this->empresa,$this->active,'$this->payment_date', '$this->payment_specific_date')";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",category_id=\"$this->category_id\",fecha=\"$this->fecha\", tipo=$this->tipo, entidad=$this->entidad, pagado='$this->pagado', pagado_con='$this->pagado_con', document_number='$this->document_number', payment_date='$this->payment_date' , payment_specific_date='$this->payment_specific_date'";
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
		return Model::one($query[0],new IncomeData());
	}

	public static function getByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}

	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());

	}
	public static function updateStatusByEntity($status,$entity){
		$sql = "update ".self::$tablename." set active= ".$status." where entidad=$entity";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public static function getAllCount($u){
		$sql = "select COUNT(id) as count from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());

	}
	public static function sumIncomeAnnual($u, $year){
		$sql = " select 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='1' and year(fecha)='$year' and active = 1 and  empresa=$u) as enero,
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='2' and year(fecha)='$year' and active = 1 and  empresa=$u) as febrero, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='3' and year(fecha)='$year' and active = 1 and  empresa=$u) as marzo, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='4' and year(fecha)='$year' and active = 1 and  empresa=$u) as abril, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='5' and year(fecha)='$year' and active = 1 and  empresa=$u) as mayo, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='6' and year(fecha)='$year' and active = 1 and  empresa=$u) as junio,  
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='7' and year(fecha)='$year' and active = 1 and  empresa=$u) as julio,
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='8' and year(fecha)='$year' and active = 1 and  empresa=$u) as agosto, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='9' and year(fecha)='$year' and active = 1 and  empresa=$u) as septiembre,
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='10' and year(fecha)='$year' and active = 1 and  empresa=$u) as octubre, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='11' and year(fecha)='$year' and active = 1 and  empresa=$u) as noviembre, 
		(SELECT sum(amount) as monto FROM ".self::$tablename." WHERE month(fecha)='12' and year(fecha)='$year' and active = 1 and  empresa=$u) as diciembre
		";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public function updateStatus($id,$status){
		$sql = "update ".self::$tablename." set active=$status";
		$sql.=" where id=$id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public static function getByEntityId($id){
		$sql = "select * from ".self::$tablename." where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());
	}
	public static function sumIncomeByDate($month,$u,$year=null){

		if(!isset($year) || $year==null) { $year = date('Y');}
		$sql = "select SUM(amount) as total from ".self::$tablename." where year(fecha) = '$year' and empresa=$u and active=1";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}

	public static function sumIncome($u){
		$year=date('Y');
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and year(fecha)='$year' and active=1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function sumIncomeNotPay($u){
		$year=date('Y');
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and year(fecha)='$year' and active=1 and pagado=0 ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function sumIncomeMonthNotPay($company_id,$month){
		$year=date('Y');
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$company_id and year(fecha)='$year' and active=1 and pagado=0 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function sumIncomeByPaymentStatusByDate($id_company, $paid_out,$month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$id_company and pagado=$paid_out and year(fecha) = '$year' and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function sumIncomeByType($u, $type,$month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and tipo=$type and year(fecha) = '$year' and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function sumIncomeByTypeAndPayment($u, $type,$month,$year, $paid_out){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and tipo=$type and year(fecha) = '$year' and pagado=$paid_out  and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function IncomesByCategoryTypeAndDate($id_company, $type,$month,$year){
		$sql = "select category_id, sum(amount)as amount, (select name from category_income where id = category_id) as description, tipo from ".self::$tablename." WHERE empresa=$id_company and active=1  and tipo=$type and year(fecha) = '".$year."' ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$sql.=' group by category_id';

		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());
	}
	public static function IncomesByCategoryIdAndDate($company_id, $category_id,$month,$year){
		$sql = "select * from ".self::$tablename." where empresa=$company_id and category_id=$category_id and year(fecha) = '$year' and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}
	public static function sumIncomeCategoryByTypeAndPayment($id_company, $type,$category_id,$month,$year, $paid_out){
		$sql = "select category_id, sum(amount)as amount, (select name from category_income where id = category_id) as description, tipo from ".self::$tablename." WHERE empresa=$id_company and active=1  and tipo=$type and year(fecha) = '".$year."' and pagado=$paid_out and category_id=$category_id ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$sql.=' group by category_id';
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new IncomeData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());
	}
	public static function queryExcel($sWhere, $offset, $per_page)
	{
		$sql = "
		SELECT fecha as Fecha, 
		(SELECT name FROM entidades where id = ".self::$tablename. ".entidad ) as Entidad ,
		(SELECT name FROM category_income where id = " . self::$tablename . ".category_id ) as Categoria ,
		(SELECT name FROM tipos where id = " . self::$tablename . ".tipo ) as Tipo ,
		description as Descripcion,
		amount as Importe, 
		document_number as Documento,
		CASE pagado when 1 then 'Pagado' When 0 Then 'Impago' else 'Impago' end as Pago 
		FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc LIMIT $offset,$per_page ";
		// var_dump($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0], new stdClass );
	}
	public static function dinamycQuery($sWhere){
		$sql = "SELECT *, ('Ingreso') as tipo_doc FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());
	}
}

?>