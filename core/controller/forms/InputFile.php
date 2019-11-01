<?php

class InputFile{
	public function __construct($name,$value,$type){
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
		$this->funjs = "";
		$this->tag = "";
		$this->file = "res/images/default_image.jpg";
		$this->file_input = "";
	}
	public function render($class_css="",$with_cam=false){ 
		$html = "<div class=".$class_css.">";
		$html.= "<label for=".$this->name.">".$this->value;
		$html.= "<input type='file' class='form-control' accept=".$this->type." id=".$this->name." name=".$this->name." ".$this->funjs."></label>";
		if($with_cam){ $html.= $this->renderImageCam();}
		$html.= "</div>";
		return $html;
	}
	public function renderImage($class_css="",$alt="",$tag=""){ 
		$html = "<div class=".$class_css.">";
        $html.= "<img src=".$this->file." alt='".$alt."' ".$tag." value='".$this->file_input."'  class='img-thumbnail' id='".$this->name."_image' height='60' width='75' ></div>";
        return $html;
	}
	public function renderImageCam($class_css=""){
		return "<input type='file' class='".$class_css." form-control' capture=camera accept=".$this->type." id='".$this->name."_cam' name='".$this->name."_cam' ".$this->funjs.">";
	}
}