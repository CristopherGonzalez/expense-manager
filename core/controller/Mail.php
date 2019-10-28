<?php

class Mail{
	public function __construct($email,$step){
		$this->email_to = $email;
		$this->step = $step;
		$this->subject = "";
		$this->message = "";
		$this->additional_parameters = "";
	}
	public function send(){
		try {
			if(isset($this->step) && !empty($this->step)){
				if($this->step == 1){
					$this->subject = "Nueva Cuenta pendiente de actualizacion por la empresa.";
				}
				if($this->step == 2){
					$this->subject = "Nueva Cuenta pendiente de actualizacion por MRC.";
				}
				if($this->step == 3){
					$this->subject = "Desactivacion de cuenta";
				}
				$this->additional_parameters = 'From: no-reply@mrcomanda.com' . "\r\n" .
					'Reply-To: no-reply@mrcomanda.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				if(!mail($this->email_to,$this->subject,$this->message,$this->additional_parameters)){
					throw new Exception(" Correo de activación no pudo ser enviado.");
				}
			}
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
		
	}

	/*$para      = 'nobody@example.com';
	$titulo    = 'El título';
	$mensaje   = 'Hola';
	$cabeceras = 'From: webmaster@example.com' . "\r\n" .
	'Reply-To: webmaster@example.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();*/

}

?>