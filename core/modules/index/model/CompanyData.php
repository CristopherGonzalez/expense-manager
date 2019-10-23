	<?php
class CompanyData {
	public static $tablename = "empresas";


	public function CompanyData(){
		$this->status = "";
		$this->is_deleted = "";
		$this->licenciaMRC = "";
		$this->pais = "";
		$this->ciudad = "";
		$this->tipo_negocio = "";
		$this->name = "";
		$this->password = "";
		$this->email = "";
		$this->profile_pic = "";
		$this->skin = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into category_expence (status, is_deleted, licenciaMRC, pais, ciudad, tipo_negocio, name, password, email, profile_pic, skin, created_at) ";
		$sql .= "value (\"$this->status\",\"$this->is_deleted\",$this->licenciaMRC,\"$this->pais\",\"$this->ciudad\",\"$this->tipo_negocio\",\"$this->name\",\"$this->password\",\"$this->email\",\"$this->profile_pic\",\"$this->skin\",\"$this->created_at\")";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CompanyData());
	}
	public static function getByLicense($license){
		$sql = "select * from ".self::$tablename." where licenciaMRC='$license'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CompanyData());
	}
	public static function getAll($u){
		$sql = "select * from ".self::$tablename." where user_id=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CompanyData());

	}

	public static function getLike($q,$u){
		$sql = "select * from ".self::$tablename." where name like '%$q%' and user_id=$u";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CompanyData());
	}

	public static function countQuery($where){
		$sql = "SELECT count(*) AS numrows FROM ".self::$tablename." where ".$where;
		$query = Executor::doit($sql);
		return Model::one($query[0],new CompanyData());
	}

	public static function query($sWhere, $offset,$per_page){
		$sql = "SELECT * FROM ".self::$tablename." where ".$sWhere." LIMIT $offset,$per_page";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CompanyData());
	}

}

?>