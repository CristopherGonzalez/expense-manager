<?php
/**
 * Clase de categoria de egreso
 */
class CategoryExpenseData {
	public static $tablename = "category_expense";	/** Nombre de la tabla category_expence para conexion con base de datos */
	/**
	 * Constructor de la clase, con parametros por defecto
	 */
	public function __construct(){
		$this->name = "";
		$this->user_id = "";
		$this->created_at = "NOW()";
		$this->tipo = "";
		$this->empresa = "";
	}
	/** Funcion para que obtiene el tipo de la categoria de egreso  
	 * @return TypeData Tipo de categoria de egreso
	 */
	public function getTypeExpense(){ return TypeData::getById($this->tipo);}
	/**
	 * @brief Funcion para agregar una nueva categoria de egreso
	 * @return response Respuesta de la insercion
	 */
	public function add(){
		$sql = "insert into ".self::$tablename." (name,user_id,created_at,tipo,empresa) ";
		$sql .= "value (\"$this->name\",$this->user_id,$this->created_at,$this->tipo,$this->empresa)";
		return Executor::doit($sql);
	}
	/**
	 * @brief Funcion para eliminar una  categoria de egreso
	 * @param mixed $id int id de la categoria que se quiere eliminar
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
	 * @brief Funcion para eliminar una  categoria de egreso
	 * @return response Resultado de la eliminacion
	 */
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}
	/**
	 * @brief Funcion para actualizar una  categoria de egreso
	 * @return response Resultado de la actualizacion
	 */
	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",tipo= $this->tipo,empresa= $this->empresa where id=$this->id";
		$query = Executor::doit($sql);
		return $query;
	}
	/**
	 * @brief Funcion para obtener una  categoria de egreso por id
	 * @param mixed $id int id de la categoria que se quiere obtener
	 * @return CategoryExpenseData Categoria de egreso del id consultado
	 */
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryExpenseData());
	}
	/**
	 * @brief Funcion para obtener todas las categorias de egreso por empresa
	 * @param mixed $id int id de la empresa 
	 * @return array(CategoryExpenseData) Array con Categorias de egreso de la empresa
	 */
	public static function getAll($id){
		$sql = "select * from ".self::$tablename." where empresa=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());

	}
	/**
	 * @brief Funcion para obtener las categorias de egreso por empresa y nombre
	 * @param mixed $id int id de la empresa 
	 * @param mixed $name nombre de la categoria dde egreso 
	 * @return array(CategoryExpenseData) Array con Categorias de egreso de la empresa
	 */
	public static function getLike($name,$id){
		$sql = "select * from ".self::$tablename." where name like '%$name%' and empresa=$id" ;
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());
	}
	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryExpenseData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryExpenseData());
	}

}

?>