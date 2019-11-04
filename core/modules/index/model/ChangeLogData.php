<?php
class ChangeLogData {
	public static $tablename = "logcambios";


	public function __construct(){
		$this->tabla = "";
		$this->registro_id = "";
		$this->description = "";
		$this->amount = "";
		$this->entidad = "";
		$this->fecha = "";
		$this->pagado = "0";
		$this->created_at = "NOW()";
		$this->user_id = "";
	}
	public function add(){
		$sql = "insert into ".self::$tablename." ( tabla, registro_id, description, amount, entidad, fecha, pagado, created_at, user_id) ";
		$sql .= "VALUES ('".$this->tabla."', ".$this->registro_id.", '".$this->description."', ".$this->amount.", ".$this->entidad.", '".$this->fecha."', ".$this->pagado.", ".$this->created_at.", ".$this->user_id.")";
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


	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ChangeLogData());
	}

	
	public static function getAllByIdAndTable($registro_id, $table){
		$sql = "select * from ".self::$tablename." where registro_id=".$registro_id." and table= ".$table;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ChangeLogData());

	}

	public static function getAllCount($registro_id){
		$sql = "select COUNT(id) as count from ".self::$tablename." where registro_id=$registro_id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ChangeLogData());

	}
	
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new ChangeLogData());
	}

	public static function query($sWhere){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." order by created_at desc ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ChangeLogData());
	}

}

?>