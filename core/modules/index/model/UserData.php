<?php
class UserData {
	public static $tablename = "user";


	public function Userdata(){
		$this->status = 1;
		$this->is_deleted = "";
		$this->name = "";
		$this->password = "";
		$this->email = "";
		$this->profile_pic = "default.png";
		$this->skin = 7;
		$this->empresa = "";
		$this->is_admin = "";
		$this->created_at = "NOW()";
	}

	public function getSkin(){ return SkinsData::getById($this->skin);}

	public function add(){
		$sql = "insert into user (name,password,email,profile_pic,skin,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->password\",\"$this->email\",\"$this->profile_pic\",\"$this->skin\",$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delete($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
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

	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";	
		Executor::doit($sql);
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

}

?>