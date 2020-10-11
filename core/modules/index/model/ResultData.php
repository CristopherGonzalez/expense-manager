<?php
class ResultData {
	public static $tablename = "resultado";


	public function __construct(){
		$this->description = "";
		$this->amount = "";
		$this->user_id = "";
		$this->entidad = "";
		$this->created_at = "NOW()";
		$this->fecha = "";
		$this->pagado = "";
		$this->documento = "";
		$this->pago = "";
		$this->pagado_con = "";
		$this->empresa = "";
		$this->active = 1;
		$this->payment_date = "00/00/0000";
		$this->payment_specific_date = null;
		$this->deuda_id = null;
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, amount, user_id, entidad, created_at, fecha, pagado, documento, pago, pagado_con, empresa, active, payment_date, payment_specific_date) ";
		$sql .= "value (\"$this->description\",\"$this->amount\",\"$this->user_id\",\"$this->entidad\",$this->created_at,\"$this->fecha\",\"$this->pagado\",\"$this->documento\",\"$this->pago\",\"$this->pagado_con\",$this->empresa,$this->active,'$this->payment_date', '$this->payment_specific_date')";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",entidad=\"$this->entidad\",created_at=\"$this->created_at\",fecha=\"$this->fecha\",pagado=\"$this->pagado\",pagado_con=\"$this->pagado_con\",documento=\"$this->documento\",pago=\"$this->pago\", payment_date='$this->payment_date' , payment_specific_date='$this->payment_specific_date' where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public static function updateStatusByEntity($status,$entity){
		$sql = "update ".self::$tablename." set active= ".$status." where entidad=$entity";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
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
	public function updateDebt($id, $debt_id)
	{
		$sql = "update " . self::$tablename . " set deuda_id=$debt_id";
		$sql .= " where id=$id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}

	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ResultData());

	}
	public static function getAllCount($u){
		$sql = "select COUNT(id) as count from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());

	}
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ResultData());
	}
	public static function getByEntityId($id){
		$sql = "select * from ".self::$tablename." where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ResultData());
	}
	public static function dinamycQuery($sWhere){
		$sql = "SELECT *, ('Socio') as tipo_doc FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ResultData());
	}
	public static function sumPartner_Month($month,$u,$year=null){
		if(!isset($year) || $year==null) { $year = date('Y');}
		$sql = "select SUM(amount) as total from ".self::$tablename." where year(fecha) = '$year' and empresa=$u  and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}
	public static function sumPartnerByIdAndDate($id,$id_company,$month,$year=null){
		if(!isset($year) || $year==null) { $year = date('Y');}
		$sql = "select SUM(amount) as total from ".self::$tablename." where  year(fecha) = '$year' and empresa=$id_company  and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}
	public static function sumPartnerByPaymentStatusByDate($id_company, $paid_out,$month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$id_company and pagado=$paid_out and year(fecha) = '$year' and active=1 ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}
	public static function sumPartnerByPaymenStatusAndEntity($id_company, $paid_out,$id_entity, $month,$year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where entidad = $id_entity and empresa=$id_company and pagado=$paid_out and year(fecha) = '$year' and active=1  ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0],new ResultData());
	}
	public static function partnersByEntityGroup($id_company, $month,$year){
		$sql = "select entidad, sum(amount)as amount, (select name from entidades where id = entidad  and active=1 ) as description, entidad from resultado WHERE empresa=$id_company and active=1  and  year(fecha) = '".$year."' ";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$sql.=' group by entidad';
		$query = Executor::doit($sql);
		return Model::many($query[0],new ExpensesData());
	}
	public static function queryExcel($sWhere, $offset, $per_page)
	{
		$sql = "
		SELECT fecha as Fecha, 
		(SELECT name FROM entidades where id = " . self::$tablename . ".entidad ) as Entidad ,
		description as Descripcion,
		amount as Importe, 
		CASE amount when amount<=0 then 'Retiro' When amount>0 Then 'Aporte' else 'Indefinido' end as Tipo_Importe,
		CASE pagado when 1 then 'Pagado' When 0 Then 'Impago' else 'Impago' end as Pago 
		FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc LIMIT $offset,$per_page ";
		// var_dump($sql);
		$query = Executor::doit($sql);
		return Model::many($query[0], new stdClass);
	}
}

?>