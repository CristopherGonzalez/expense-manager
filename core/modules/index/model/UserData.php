<?php
class UserData {
	public static $tablename = "user";


	public function __construct(){
		$this->status = 1;
		$this->is_deleted = 0;
		$this->name = "";
		$this->password = "";
		$this->email = "";
		$this->profile_pic = "default.png";
		$this->skin = 7;
		$this->empresa = "";
		$this->is_admin = 0;
		$this->created_at = "NOW()";
	}

	public function getSkin(){ return SkinsData::getById($this->skin);}

	public function add(){
		$sql = "insert into user (status,is_deleted,name,password,email,profile_pic,skin,empresa,is_admin,created_at) ";
		$sql .= "value ($this->status,$this->is_deleted,'$this->name','$this->password','$this->email','$this->profile_pic',$this->skin,$this->empresa,$this->is_admin,$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delete($id){
		$sql = "update ".self::$tablename." set status=4,is_deleted=1 where id=$id";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\",email=\"$this->email\",skin=\"$this->skin\" where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}

	public function update_image($img_update,$id){
		$sql = "update ".self::$tablename." SET $img_update WHERE id=$id;";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function update_name(){
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";	
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function update_status(){
		$sql = "update ".self::$tablename." set status=".$this->status.", is_admin=".$this->is_admin." where id=".$this->id;	
		if (Executor::doit($sql)){
			return true;
		}else{
			return false;
		}
	}
	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getByEmail($email){
		$sql = "select * from ".self::$tablename." where email=\"$email\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}


	public static function getLogin($email,$password){
		$sql = "select * from ".self::$tablename." where email=\"$email\" and password=\"$password\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}
	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());

	}

	public static function getInactives(){
		$sql = "select * from ".self::$tablename." where status=0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getActives(){
		$sql = "select * from ".self::$tablename." where status=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
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