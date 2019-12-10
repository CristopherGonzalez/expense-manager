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
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, amount, user_id, entidad, created_at, fecha, pagado, documento, pago, pagado_con, empresa) ";
		$sql .= "value (\"$this->description\",\"$this->amount\",\"$this->user_id\",\"$this->entidad\",$this->created_at,\"$this->fecha\",\"$this->pagado\",\"$this->documento\",\"$this->pago\",\"$this->pagado_con\",$this->empresa)";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",amount=\"$this->amount\",entidad=\"$this->entidad\",created_at=\"$this->created_at\",fecha=\"$this->fecha\",pagado=\"$this->pagado\",pagado_con=\"$this->pagado_con\",documento=\"$this->documento\",pago=\"$this->pago\" where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
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
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IncomeData());
	}
}

?>