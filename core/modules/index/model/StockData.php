<?php
class StockData {
	public static $tablename = "valores";


	public function __construct(){
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
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, amount, upload_receipt, user_id, entidad, created_at, fecha, fecha_pago, pagado, document_number, documento, pago, empresa, active,payment_specific_date) ";
		$sql .= "value (\"$this->description\",$this->amount,\"$this->upload_receipt\",$this->user_id,$this->entidad,$this->created_at,\"$this->fecha\",\"$this->fecha_pago\",$this->pagado,\"$this->document_number\",'$this->documento','$this->pago',$this->empresa,$this->active, '$this->payment_specific_date')";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",fecha=\"$this->fecha\",fecha_pago=\"$this->fecha_pago\", entidad=$this->entidad, pagado='$this->pagado', document_number='$this->document_number', fecha_pago='$this->fecha_pago', active=$this->active, payment_specific_date='$this->payment_specific_date' ";
		if(isset($this->documento) && !empty($this->documento)){
			$sql.=", documento = '$this->documento' ";
		}
		if(isset($this->pago) && !empty($this->pago)){
			$sql.=", pago = '$this->pago' ";
		}
		$sql.=" where id=$this->id";
		return Executor::doit($sql);
	}
	public function addOrUpdate($object){
		$response = false;
		$this->description = $object['description'];
		$this->amount = $object['amount'];
		$this->user_id = $object['user_id'];
		$this->entidad = $object['entidad'];
		$this->fecha = $object['date'];
		$this->fecha_pago = $object['payment_date'];
		$this->pagado = $object['pagado'];
		$this->document_number = $object['document_number'];
		$this->documento = $object['documento'];
		$this->pago = $object['pago'];
		$this->empresa = $object['empresa'];
		$this->active = $object['active'];
		if($object['id']==0){
			$response = $this->add();
		}else{
			$this->id=$object['id'];
			$response = $this->update();
		}
		return $response;
	}
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}

	public static function getByEntityId($id){
		$sql = "select * from ".self::$tablename." where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());
	}
	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());

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
		return Model::one($query[0],new StockData());

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
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StockData());
	}
	
	public static function dinamycQuery($sWhere){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}
	public static function sumStock($u){
		$year=date('Y');
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$u and year(fecha)='$year' and active=1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}
	public static function sumStockByDate($company, $month, $year){
		$sql = "select sum(amount) as amount from ".self::$tablename." where empresa=$company and year(fecha)='$year' and active=1";
		if(isset($month) && !empty($month) && $month!=0){
			$sql.=" and month(fecha)= '$month' ";
		}
		$query = Executor::doit($sql);
		if(!$query[0]){
			return 0;
		}
		return Model::one($query[0],new StockData());
	}
	public static function dinamycAllQuery( $sWhere, $sSelect = "SELECT * ", $all, $sOrder=" order by created_at desc"){
		$sql = $sSelect." FROM ".self::$tablename." where ".$sWhere.$sOrder;
		$query = Executor::doit($sql);
		if($all){
			return Model::many($query[0],new StockData());
		}else{
			return Model::one($query[0],new StockData());
		}
	}
}

?>