<?php
class ModalCategory{
	/**
	 * @brief Constructor de la clase, con parametros por defecto
	 * @param mixed $title Titulo de la modal
	 * @param mixed $id Identificado de la modal
	 * @param mixed $user Usuario de la modal
	 */
	public function __construct($title,$id,$user){
		$this->title = $title;
		$this->id = $id;
		$this->user = $user->name;
	}
	/**
	 * @return $select  string select con el control creado
	 */

	public function renderInit(){
		$htmlinit="<div class='modal fade' id='".$this->id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
		$htmlinit.="<div class='modal-dialog'>";
		$htmlinit.="<div class='modal-content'>";
		$htmlinit.="<form class='form-horizontal' role='form' method='post' id='add_register' name='add_register'>";
		$htmlinit.= $this->renderHeader();
		$htmlinit.="<div class='modal-body table-responsive'>";
		return $htmlinit;
	}
	public function renderHeader(){
		$htmlheader="<div class='modal-header'>";
		$htmlheader.="<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		$htmlheader.="<h4 class='modal-title' id='myModalLabel'>".$this->title."</h4>";
		$htmlheader.="</div>";
		return $htmlheader;
	}

	public function renderFooter($with_add_button=true){
		$htmlfooter="<div class='modal-footer'>";
		$htmlfooter.="<div class='form-group'>";
		$htmlfooter.="<span class='col-md-1 col-sm-1 col-xs-12'></span>";
		$htmlfooter.="<label class='col-md-7 col-sm-7' style='color:#999; font-weight:normal;'>Registrado por ".$this->user." el ".date('Y-m-d')." </label>";
		$htmlfooter.="<span class='col-md-4 col-sm-4 col-xs-12'>";
		$htmlfooter.="<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>";
		if($with_add_button){$htmlfooter.=$this->renderButtonAdd();}
		$htmlfooter.="</span></div></div>";
		return $htmlfooter;
	}
	public function renderButtonAdd(){
		return "<button type='submit' id='save_data' class='btn btn-primary'>Agregar</button>";
	}
	public function renderEnd($with_add_button=true){
		$htmlend="</div>";
		$htmlend.=$this->renderFooter($with_add_button);
		$htmlend.="</form></div></div></div>";
		return $htmlend;
	}
	
	
}


?>
