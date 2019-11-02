<?php
require("send-email/class.phpmailer.php");
require("send-email/class.smtp.php");
class Mail{
	public function __construct($email,$step){
		$this->email_to = $email;
		$this->step = $step;
		$this->subject = "";
		$this->message = "";
		$this->additional_parameters = "";
		$this->smtp_host = "dm000397.ferozo.com";  
		$this->smtp_usuario = "no-reply@dm000397.ferozo.com";  
		$this->smtp_clave = "A@zf29MeKVfa";  
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

			$mail->Subject = "DonWeb - Ejemplo de formulario de contacto"; // Este es el titulo del email.
			$mensajeHtml = nl2br($this->message);
			$mail->Body = "{$mensajeHtml} <br /><br />Formulario de ejemplo. By DonWeb<br />"; // Texto del email en formato HTML
			$mail->AltBody = "{$this->message} \n\n Formulario de ejemplo By DonWeb"; // Texto sin formato HTML
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

	/*// Datos de la cuenta de correo utilizada para enviar vía SMTP
$smtpHost = "dm000397.ferozo.com";  // Dominio alternativo brindado en el email de alta 
$smtpUsuario = "no-reply@dm000397.ferozo.com";  // Mi cuenta de correo
$smtpClave = "A@zf29MeKVfa";  // Mi contraseña

// Email donde se enviaran los datos cargados en el formulario de contacto
$emailDestino = "correo_destinatario@suDominio.com";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Port = 465; 
$mail->SMTPSecure = 'ssl';
$mail->IsHTML(true); 
$mail->CharSet = "utf-8";


// VALORES A MODIFICAR //
$mail->Host = $smtpHost; 
$mail->Username = $smtpUsuario; 
$mail->Password = $smtpClave;

$mail->From = $email; // Email desde donde envío el correo.
$mail->FromName = $nombre;
$mail->AddAddress($email); // Esta es la dirección a donde enviamos los datos del formulario

$mail->Subject = "DonWeb - Ejemplo de formulario de contacto"; // Este es el titulo del email.
$mensajeHtml = nl2br($mensaje);
$mail->Body = "{$mensajeHtml} <br /><br />Formulario de ejemplo. By DonWeb<br />"; // Texto del email en formato HTML
$mail->AltBody = "{$mensaje} \n\n Formulario de ejemplo By DonWeb"; // Texto sin formato HTML
// FIN - VALORES A MODIFICAR //

$estadoEnvio = $mail->Send(); 
if($estadoEnvio){
    echo "El correo fue enviado correctamente.";
} else {
    echo "Ocurrió un error inesperado.";
}*/

}

?>