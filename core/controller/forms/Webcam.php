<?php

class Webcam{
	public function __construct($name){
		$this->name = $name;
		$this->funjs = "";
		$this->tag = "";
	}
	
	public function renderModalImageCam(){
		$modal_content = new ModalCategory("Generar Imagen","frmwebcam".$this->name,UserData::getById($_SESSION['user_id']));
		$html= $modal_content->renderInit();
		$html.="<div class='row justify-content-md-center'>";
		$html.="<span class='col-md-12 col-sm-12 col-xs-12' style='margin:3px;'><center>";
		$html.="<input type='button' class='btn btn-primary' style='margin-right:2px;' id='btn_generate_".$this->name."' name='btn_generate_".$this->name."' value='Sacar Foto' onclick='generate_image();'>";
		$html.="<input type='button' class='btn btn-primary' style='margin-right:2px;' data-dismiss='modal' id='btn_save_".$this->name."' name='btn_save_".$this->name."' value='Guardar Foto' onclick='save_image(".$this->name."_image);'>";
		$html.="<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button></span></center><br>";
		$html.="<div class='video-wrap col-md-12 col-sm-12 col-xs-12 table-responsive' style='min-width:90%;' id='dv_video_".$this->name."'><video class='col-md-12 col-sm-12 col-xs-12 table-responsive' id='video_".$this->name."' playsinline autoplay></video></div><br>";
		$html.="<div class='video-wrap col-md-12 col-sm-12 col-xs-12 table-responsive' style='min-width:90%;margin-top:3px;'><canvas id='canvas_".$this->name."' class='col-md-12 col-sm-12 col-xs-12'></canvas></div>";
		$html.="</div>";
		$html.=  $modal_content->renderEnd(false,false,false);
		return $html;
	}
}