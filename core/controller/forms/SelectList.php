<?php
class SelectList{
	/**
	 * 
	 */
	public function __construct($name,$value,$options){
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
		$this->funjs = "";
		$this->tag = "";
		$this->default_value = "";
	}
	/**
	 * @param $class_css string Parametro css por si quiere incluir una clase css
	 * @return $select  string select con el control creado
	 */
	public function render($class_css=""){
		$select =  "<select ".$this->tag." required class='form-control $class_css' style='width: 100%' id='$this->name' name='$this->name' ".$this->funjs.">";
		$select.= "<option value=\"0\">Selecciona un ". $this->value ." </option>";
		foreach($this->options as $option){
			$select.="<option ".($this->default_value == $option->id ? "selected" : "")." value=".$option->id.">".$option->name."</option>";
		}
		$select.="</select>";
		return $select;
	}
	public function renderLabel($class_css=""){
		return "<label for='$this->name' class='$class_css control-label'>$this->value</label>";
	}
	
}


?>
