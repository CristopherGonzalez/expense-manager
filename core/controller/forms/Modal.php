<?php
class Modal{
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

	public function renderInit($is_small=false){
		$htmlinit="<div class='modal fade' id='".$this->id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
		if($is_small){
			$htmlinit.="<div class='modal-dialog modal-sm' role='document'>";
		 }else{
			$htmlinit.="<div class='modal-dialog' role='document'>";
		 }
		$htmlinit.="<div class='modal-content'>";
		$htmlinit.="<form class='form-horizontal' role='form' method='post' id='add_register' name='add_register'>";
		$htmlinit.= $this->renderHeader();
		$htmlinit.="<div class='modal-body table-responsive'>";
		return $htmlinit;
	}
	public function renderHeader(){
		$htmlheader="<div class='modal-header'>";
		$htmlheader.="<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		$htmlheader.="<center><h4 class='modal-title' id='myModalLabel'>".$this->title."</h4></center>";
		$htmlheader.="</div>";
		return $htmlheader;
	}

	public function renderFooter($with_add_button=true,$with_generate_button=false){
		$htmlfooter="<div class='modal-footer'>";
		$htmlfooter.="<div class='form-group'>";
		$htmlfooter.="<span class='col-md-1 col-sm-1 col-xs-12'></span>";
		if($with_generate_button){
			$htmlfooter.="<span class='col-md-12 col-sm-12 col-xs-12' style='float:right;'>";
			$htmlfooter.=$this->renderButtonGenerate();
			$htmlfooter.="</span>";
		}else{
			$htmlfooter.="<label class='col-md-7 col-sm-7' style='color:#999; font-weight:normal;'>Registrado por ".$this->user." el ".date('Y-m-d')." </label>";
			$htmlfooter.="<span class='col-md-4 col-sm-4 col-xs-12'>";
			$htmlfooter.="<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>";
			if($with_add_button){$htmlfooter.=$this->renderButtonAdd();}
			$htmlfooter.="</span>";
		}
		$htmlfooter.="</div></div>";
		return $htmlfooter;
	}
	public function renderButtonAdd(){
		return "<button type='submit' id='save_data' class='btn btn-primary'>Agregar</button>";
	}
	public function renderButtonGenerate(){
		return "<button type='button' id='btn_generate' class='btn btn-primary' disabled><span class='fa fa-plus'></span> Generar</button>";
	}
	public function renderEnd($with_add_button=true,$with_generate_button=false,$with_footer=true){
		$htmlend="</div>";
		if($with_footer){$htmlend.=$this->renderFooter($with_add_button,$with_generate_button);}
		$htmlend.="</form></div></div></div>";
		return $htmlend;
	}
	
	
}


?>
