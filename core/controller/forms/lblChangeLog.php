<?php

class lblChangeLog{
	public function __construct($id,$table){
		$this->id = $id;
		$this->table = $table;
		$this->change_log = new ChangeLogData();
	}
	public function renderLabel($class_css=""){ return "<label class='".$class_css." control-label' data-toggle='modal' data-target='#frm".$this->table."' ><i class='fa fa-list'></i>  Ver LOG de cambios</label>"; }
	public function setValuesByTable(){
		$this->change_log = "";
	}
	public function getValuesByTable(){
		return ChangeLogData::getAllByIdAndTable($this->id, $this->table);
	}
}

?>