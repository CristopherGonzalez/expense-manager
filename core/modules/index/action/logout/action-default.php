<?php
/**
* @author abisoft
* Proceso de Cerrar sesion
**/

	unset($_SESSION["user_id"]);
	session_destroy();
	Core::redir("./?view=index&alert=2");
?>