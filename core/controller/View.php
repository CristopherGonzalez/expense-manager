<?php


// View.php
//  Una vista corresponde a cada componente visual dentro de un modulo.

class View {
	/**
	* @function load
	* la funcion load carga una vista correspondiente a un modulo
	**/	
	public static function load($view){
		// Module::$module;
		if(!isset($_GET['view'])){
			include "core/modules/".Module::$module."/view/".$view."/widget-default.php";
		}else{


			if(View::isValid()){
				include "core/modules/".Module::$module."/view/".$_GET['view']."/widget-default.php";				
			}else{
				//View::Error("<b>404 NOT FOUND</b> View <b>".$_GET['view']."</b> folder  !!");
				View::Error(" <div class='content-wrapper'> <section class='content-header'><h1> 404</h1></section><section class='content'> El archivo: <b>".$_GET['view']."</b> no existe  !!</div></div>");
			}



		}
	}

	/**
	* @function isValid
	* valida la existencia de una vista
	**/	
	public static function isValid(){
		$valid=false;
		if(isset($_GET["view"])){
			if(file_exists($file = "core/modules/".Module::$module."/view/".$_GET['view']."/widget-default.php")){
				$valid = true;
			}
		}
		return $valid;
	}

	public static function Error($message){
		print $message;
	}

}

?>