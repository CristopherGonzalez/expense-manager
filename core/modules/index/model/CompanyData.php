	<?php
	class CompanyData
	{
		public static $tablename = "empresas";


		public function __construct()
		{
			$this->status = 1;
			$this->is_deleted = 0;
			$this->licenciaMRC = "";
			$this->pais = "";
			$this->ciudad = "";
			$this->telefono = "";
			$this->direccion = "";
			$this->tipo_negocio = "";
			$this->name = "";
			$this->email = "";
			$this->profile_pic = "res/images/companies/default.jpg";
			$this->skin = 1;
			$this->password = "";
			$this->created_at = "NOW()";
		}

		public function add()
		{
			$sql = "insert into " . self::$tablename . " (status, is_deleted, licenciaMRC, pais, ciudad, direccion, telefono, tipo_negocio, name,  email, profile_pic, skin, created_at) ";
			$sql .= "value ($this->status,$this->is_deleted,'" . $this->licenciaMRC . "'," . $this->pais . "," . $this->ciudad . ",'" . $this->direccion . "','" . $this->telefono . "'," . $this->tipo_negocio . ",'" . $this->name . "','" . $this->email . "','$this->profile_pic'," . $this->skin . "," . $this->created_at . ")";
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

		public function update()
		{
			$sql = "update " . self::$tablename . " set name='" . $this->name . "', status=" . $this->status . ", is_deleted=" . $this->is_deleted . ", licenciaMRC='" . $this->licenciaMRC . "', direccion='" . $this->direccion . "', telefono='" . $this->telefono . "', pais=" . $this->pais . ", ciudad=" . $this->ciudad . ", tipo_negocio=" . $this->tipo_negocio . ", email='" . $this->email . "',profile_pic='" . $this->profile_pic . "' where id=$this->id";
			if (Executor::doit($sql)) {
				return true;
			} else {
				return false;
			}
		}
		public static function changeStatus($is_deleted, $status, $id)
		{
			$sql = "update " . self::$tablename . " set is_deleted=" . $is_deleted . ", status=" . $status . " where id=" . $id;
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
			return Model::one($query[0], new CompanyData());
		}
		public static function getByLicense($license)
		{
			$sql = "select * from " . self::$tablename . " where licenciaMRC='$license'";
			$query = Executor::doit($sql);
			return Model::one($query[0], new CompanyData());
		}
		public static function getAll($u)
		{
			$sql = "select * from " . self::$tablename . " where user_id=$u";
			$query = Executor::doit($sql);
			return Model::many($query[0], new CompanyData());
		}

		public static function getLike($q, $u)
		{
			$sql = "select * from " . self::$tablename . " where  (LOWER(name) LIKE LOWER('%" . $q . "%') ) and user_id=$u";
			$query = Executor::doit($sql);
			return Model::many($query[0], new CompanyData());
		}

		public static function countQuery($where)
		{
			$sql = "SELECT count(*) AS numrows FROM " . self::$tablename . " where " . $where;
			$query = Executor::doit($sql);
			return Model::one($query[0], new CompanyData());
		}

		public static function query($sWhere, $offset, $per_page)
		{
			$sql = "SELECT * FROM " . self::$tablename . " where " . $sWhere . " LIMIT $offset,$per_page";
			$query = Executor::doit($sql);
			return Model::many($query[0], new CompanyData());
		}
		public static function getAllCount()
		{
			$sql = "select COUNT(id) as count from " . self::$tablename . " where is_deleted=false";
			$query = Executor::doit($sql);
			return Model::one($query[0], new CompanyData());
		}
	}

	?>