<?php
class DebtsData
{
	public static $tablename = "deudas";


	public function __construct()
	{
		$this->description = "";
		$this->amount = "";
		$this->upload_receipt = "";
		$this->user_id = "";
		$this->entidad = "";
		$this->created_at = "NOW()";
		$this->fecha = "";
		$this->fecha_pago = "00/00/0000";
		$this->pagado = "0";
		$this->document_number = "";
		$this->documento = "";
		$this->pago = "";
		$this->empresa = "";
		$this->active = 1;
		$this->payment_specific_date = null;
		$this->egreso_id = null;
		$this->socio_id = null;
	}

	public function add()
	{
		$sql = "insert into " . self::$tablename . " (description, amount, upload_receipt, user_id, entidad, created_at, fecha, fecha_pago, pagado, document_number, documento, pago, empresa, active, payment_specific_date,egreso_id,socio_id) ";
		$sql .= "value (\"$this->description\",
		$this->amount,
		\"$this->upload_receipt\",
		$this->user_id,
		$this->entidad,
		$this->created_at,
		\"$this->fecha\",
		\"$this->fecha_pago\",
		$this->pagado,
		\"$this->document_number\",
		'$this->documento',
		'$this->pago',
		$this->empresa,
		$this->active," .
			(isset($this->payment_specific_date) ? "'" . $this->payment_specific_date . "'" : 'null') . "," .
			(isset($this->egreso_id) ? $this->egreso_id : 'null') . "," .
			(isset($this->socio_id) ? $this->socio_id : 'null') . ")";
		return Executor::doit($sql);
	}

	public static function delete($id)
	{
		$sql = "delete from " . self::$tablename . " where id=$id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where id=$this->id";
		Executor::doit($sql);
	}
	public function updateStatus($id, $status)
	{
		$sql = "update " . self::$tablename . " set active=$status";
		$sql .= " where id=$id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public function updateExpense($id, $expense_id)
	{
		$sql = "update " . self::$tablename . " set egreso_id=$expense_id";
		$sql .= " where id=$id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public function updatePartner($id, $partner_id)
	{
		$sql = "update " . self::$tablename . " set socio_id=$partner_id";
		$sql .= " where id=$id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public function update()
	{
		$sql = "update " . self::$tablename . " set description=\"$this->description\",amount=\"$this->amount\",fecha=\"$this->fecha\", entidad=$this->entidad, pagado='$this->pagado', document_number='$this->document_number', fecha_pago='$this->fecha_pago'";
		if (isset($this->documento) && !empty($this->documento)) {
			$sql .= ", documento = '$this->documento' ";
		}
		if (isset($this->pago) && !empty($this->pago)) {
			$sql .= ", pago = '$this->pago' ";
		}
		$sql .= " where id=$this->id";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}

	public static function getByEntityId($id)
	{
		$sql = "select * from " . self::$tablename . " where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DebtsData());
	}
	public static function getAll($u)
	{
		$sql = "select * from " . self::$tablename . " where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DebtsData());
	}
	public static function updateStatusByEntity($status, $entity)
	{
		$sql = "update " . self::$tablename . " set active= " . $status . " where entidad=$entity";
		if (Executor::doit($sql)) {
			return true;
		} else {
			return false;
		}
	}
	public static function getAllCount($u)
	{
		$sql = "select COUNT(id) as count from " . self::$tablename . " where empresa=$u";
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}

	public static function countQuery($where)
	{
		$sql = "SELECT count(*) AS numrows FROM " . self::$tablename . " where " . $where;
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}

	public static function query($sWhere, $offset, $per_page)
	{
		$sql = "SELECT * FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DebtsData());
	}

	public static function dynamicQuery($sWhere)
	{
		$sql = "SELECT *, ('Deuda') as tipo_doc FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DebtsData());
	}
	public static function dynamicQueryArray($sWhere)
	{
		$sql = "SELECT * FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new DebtsData());
	}
	public static function sumDebts($u)
	{
		$year = date('Y');
		$sql = "select sum(amount) as amount from " . self::$tablename . " where empresa=$u and year(fecha)='$year' and pagado=0 and active=1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}
	public static function sumDebtsByPay($empresa, $year = null, $month, $pay)
	{
		if (!isset($year) || $year == null) {
			$year = date('Y');
		}
		$sql = "select *, sum(amount) as total from " . self::$tablename . " where empresa=$empresa and year(fecha)='$year' and pagado=$pay and active=1";
		if (isset($month) && !empty($month) && $month != 0) {
			$sql .= " and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}
	public static function sumDebtsAnnual($u, $year)
	{
		$sql = " select 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='1' and year(fecha)='$year' and active = 1 and  empresa=$u) as enero,
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='2' and year(fecha)='$year' and active = 1 and  empresa=$u) as febrero, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='3' and year(fecha)='$year' and active = 1 and  empresa=$u) as marzo, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='4' and year(fecha)='$year' and active = 1 and  empresa=$u) as abril, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='5' and year(fecha)='$year' and active = 1 and  empresa=$u) as mayo, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='6' and year(fecha)='$year' and active = 1 and  empresa=$u) as junio,  
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='7' and year(fecha)='$year' and active = 1 and  empresa=$u) as julio,
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='8' and year(fecha)='$year' and active = 1 and  empresa=$u) as agosto, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='9' and year(fecha)='$year' and active = 1 and  empresa=$u) as septiembre,
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='10' and year(fecha)='$year' and active = 1 and  empresa=$u) as octubre, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='11' and year(fecha)='$year' and active = 1 and  empresa=$u) as noviembre, 
		(SELECT sum(amount) as monto FROM " . self::$tablename . " WHERE month(fecha)='12' and year(fecha)='$year' and active = 1 and  empresa=$u) as diciembre
		";
		$query = Executor::doit($sql);
		return Model::one($query[0], new DebtsData());
	}
	public static function queryExcel($sWhere, $offset, $per_page)
	{
		$sql = "
		SELECT fecha as Fecha, 
		(SELECT name FROM entidades where id = " . self::$tablename . ".entidad ) as Entidad ,
		description as Descripcion,
		amount as Importe, 
		document_number as Documento,
		CASE pagado when 1 then 'Pagado' When 0 Then 'Impago' else 'Impago' end as Pago 
		FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new stdClass);
	}
	public static function queryExcelReports($sSelect, $sWhere, $offset, $per_page)
	{
		$sql = $sSelect . ", document_number as Documento,(SELECT name FROM entidades where id = " . self::$tablename . ".entidad ) as Entidad
		FROM " . self::$tablename . " where " . $sWhere . " order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new stdClass);
	}
}
