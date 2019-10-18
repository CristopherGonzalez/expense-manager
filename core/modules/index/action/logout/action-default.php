<?php
/**
* @author abisoft
* Proceso de Cerrar sesion
**/

	unset($_SESSION["user_id"]);
	unset($_SESSION["company_id"]);
	session_destroy();
	Core::redir("./?view=index&alert=2");
?>