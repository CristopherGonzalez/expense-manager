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
		if(isset($this->step) && !empty($this->step)){
			if($this->step == 1){
				$this->subject = "Nueva Cuenta pendiente de actualizacion por la empresa.";
				$this->message = "Link para activacion de nueva cuenta.";
			}
			if($this->step == 2){
				$this->subject = "Nueva Cuenta pendiente de actualizacion por MRC.";
				$this->message = "Link para activacion de nueva cuenta.";
			}
			if($this->step == 3){
				$this->subject = "Desactivacion de cuenta";
				$this->message = "Link para desactivacion de cuenta.";
			}
			
			mail($this->email_to,$this->subject,$this->message);

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