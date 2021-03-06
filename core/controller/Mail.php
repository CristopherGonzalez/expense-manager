<?php
require("send-email/class.phpmailer.php");
require("send-email/class.smtp.php");
class Mail{
	public function __construct($email,$step=5){
		$this->email_to = $email;
		$this->step = $step;
		$this->subject = "";
		$this->message = "";
		$this->additional_parameters = "";
		$this->smtp_host = "dm000397.ferozo.com";  
		$this->smtp_usuario = "avisos@dm000397.ferozo.com";  
		$this->smtp_clave = "Rl9*YAF6cW";  
	}
	public function send(){
		try {
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Port = 465; 
			$mail->SMTPSecure = 'ssl';
			$mail->IsHTML(true); 
			$mail->CharSet = "utf-8";

			// VALORES A MODIFICAR //
			$mail->Host = $this->smtp_host; 
			$mail->Username = $this->smtp_usuario; 
			$mail->Password = $this->smtp_clave;

			$mail->From = $this->email_to; // Email desde donde envío el correo.
			$mail->FromName = $this->email_to;
			$mail->AddAddress($this->email_to); // Esta es la dirección a donde enviamos los datos del formulario

			//$mail->Subject = "DonWeb - Ejemplo de formulario de contacto"; // Este es el titulo del email.
			//$mensajeHtml = nl2br($this->message);
			$mail->Body = "{$this->message} <br /><br />Copyright © ".date("Y")." mrcomanda.com - Todos los derechos reservados.<br />"; // Texto del email en formato HTML
			$mail->AltBody = "{$this->message} \n\n Copyright © " . date("Y") . " mrcomanda.com - Todos los derechos reservados."; // Texto sin formato HTML
			// FIN - VALORES A MODIFICAR //

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

				$mail->Subject = $this->subject;

				if(isset($this->additional_parameteres)){
					$mail->addCustomHeader($this->additional_parameteres);
				}

				$estadoEnvio = $mail->Send(); 
				if($estadoEnvio){
					return true;
				} else {
					return false;
				}

			}

			return false;
		} catch (Exception $e) {
			return $e->getMessage();
		}
		
	}

}

?>