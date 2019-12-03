<?php


// Core.php
//  obtiene las configuraciones, muestra y carga los contenidos necesarios.

class Core {

	public static function includeCSS(){
		$path = "res/css/";
		$handle=opendir($path);
		if($handle){
			while (false !== ($entry = readdir($handle)))  {
				if($entry!="." && $entry!=".."){
					$fullpath = $path.$entry;
				if(!is_dir($fullpath)){
						echo "<link rel='stylesheet' type='text/css' href='".$fullpath."' />";
					}
				}
			}
		closedir($handle);
		}

	}

	public static function redir($url){
		echo "<script>window.location='".$url."';</script>";
	}

	/*public static function redir($url){
		header("location: ".$url);
	}*/

	public static function alert($url){
		echo "<script>alert('".$url."');</script>";
	}


	public static function includeJS(){
		$path = "res/js/";
		$handle=opendir($path);
		if($handle){
			while (false !== ($entry = readdir($handle)))  {
				if($entry!="." && $entry!=".."){
					$fullpath = $path.$entry;
				if(!is_dir($fullpath)){
						echo "<script type='text/javascript' src='".$fullpath."'></script>";

					}
				}
			}
		closedir($handle);
		}

	}
	public static function encrypt_decrypt($action, $string) {
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = '$er$t$_y!y"·$%/()';
		$secret_iv = '?)(/&%RWQOIUYTREW';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	   	if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
	   return $output;
	}
	//TODO crear validacion 
	public static function getQuantityLinkageElements($element){
		$elements_linkage = array();
		switch (get_class($element)) {
			case 'CategoryIncomeData':
				$elements_linkage[] = IncomeData::getByCategoryId($element->id);
				$elements_linkage[] = EntityData::getByCategoryId($element->id,'Ingreso',$element->empresa);
				break;
			case 'CategoryExpenseData':
				$elements_linkage[] = EntityData::getByCategoryId($element->id,'Egreso',$element->empresa);
				$elements_linkage[] = ExpensesData::getByCategoryId($element->id);
				break;
			case 'EntityData':
				$elements_linkage[] = ExpensesData::getByEntityId($element->id);
				$elements_linkage[] = IncomeData::getByEntityId($element->id);
				$elements_linkage[] = ResultData::getByEntityId($element->id);
			default:
				break;
		}
		if(is_array($elements_linkage) && count($elements_linkage)>0){
			foreach($elements_linkage as $element_linkage){
				if((isset($element_linkage) && !empty($element_linkage)) || (is_array($element_linkage) && count($element_linkage)>0)){
					return true;
				}
			}
		}
		return false;
	}
	
}



?>