<?php
class DebtsData {
	public static $tablename = "deudas";


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
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, amount, upload_receipt, user_id, entidad, created_at, fecha, fecha_pago, pagado, document_number, documento, pago, empresa, active) ";
		$sql .= "value (\"$this->description\",$this->amount,\"$this->upload_receipt\",$this->user_id,$this->entidad,$this->created_at,\"$this->fecha\",\"$this->fecha_pago\",$this->pagado,\"$this->document_number\",'$this->documento','$this->pago',$this->empresa,$this->active)";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",fecha=\"$this->fecha\", entidad=$this->entidad, pagado='$this->pagado', document_number='$this->document_number', fecha_pago='$this->fecha_pago'";
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
		return Model::one($query[0],new DebtsData());
	}

	public static function getByEntityId($id){
		$sql = "select * from ".self::$tablename." where entidad=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new DebtsData());
	}
	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where empresa=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new DebtsData());

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
		return Model::one($query[0],new DebtsData());

	}
	
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new DebtsData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc LIMIT $offset,$per_page ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new DebtsData());
	}
	
	public static function dinamycQuery($sWhere){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new DebtsData());
	}
	
}

?>