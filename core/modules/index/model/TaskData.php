<?php
/**
 * Clase de Tareas
 */
class TaskData {
	public static $tablename = "task";	/** Nombre de la tabla task para conexion con base de datos */
	/**
	 * Constructor de la clase, con parametros por defecto
	 */
	public function __construct(){
		$this->user_id = "";
		$this->empresa = "";
		$this->tarea = "";
		$this->hecho = "";
	}
	
	public function add(){
		$sql = "insert into ".self::$tablename." (user_id, empresa, tarea, hecho) ";
		$sql .= "value ($this->user_id,$this->empresa,\"$this->tarea\",$this->hecho)";
		return Executor::doit($sql);
	}
	/**
	 * @brief Funcion para eliminar una  tarea
	 * @param mixed $id int id de la tarea que se quiere eliminar
	 * @return response Resultado de la eliminacion
	 */
	public static function delete($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}

	}
	/**
	 * @brief Funcion para eliminar una  tarea
	 * @return response Resultado de la eliminacion
	 */
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}
	/**
	 * @brief Funcion para actualizar una tarea
	 * @return response Resultado de la actualizacion
	 */
	public function update(){
		$sql = "update ".self::$tablename." set tarea=\"$this->tarea\",hecho= $this->hecho where id=$this->id";
		$query = Executor::doit($sql);
		return $query;
	}
	/**
	 * @brief Funcion para obtener una tarea por id
	 * @param mixed $id int id de la tarea que se quiere obtener
	 * @return TaskData tarea del id consultado
	 */
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new TaskData());
	}
	/**
	 * @brief Funcion para obtener todas las tarea por empresa
	 * @param mixed $id int id de la empresa 
	 * @return array(TaskData) Array con tarea de la empresa
	 */
	public static function getAll($id){
		$sql = "select * from ".self::$tablename." where empresa=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TaskData());

	}
	/**
	 * @brief Funcion para obtener las tarea por empresa y nombre
	 * @param mixed $id int id de la empresa 
	 * @param mixed $name nombre de la tarea
	 * @return array(TaskData) Array con tarea de la empresa
	 */
	public static function getLike($name,$id){
		$sql = "select * from ".self::$tablename. " where (LOWER(name) LIKE LOWER('%" . $name . "%')) and empresa=$id" ;
		$query = Executor::doit($sql);
		return Model::many($query[0],new TaskData());
	}
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new TaskData());
	}
	public static function countPending($company)
	{
		$sql = "SELECT count(*) AS numrows FROM " . self::$tablename . " where  empresa=$company and hecho = false" ;
		$query = Executor::doit($sql);
		return Model::one($query[0], new TaskData());
	}
	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TaskData());
	}

}

?>