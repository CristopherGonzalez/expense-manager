<?php

class InputText{
	public function __construct($name,$value){
		$this->name = $name;
		$this->value = $value;
		$this->default_value = "";
	}
	public function render($class_css=""){ return "<input type='text' required class='form-control ".$class_css."' name='".$this->name."' id='".$this->name."' placeholder='".$this->value."' value='".$this->default_value."'>";}
	public function renderLabel($class_css=""){ return "<label for='".$this->name."' class='".$class_css." control-label'>".$this->value."</label>"; }
}

?>