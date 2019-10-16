<?php

/**
* @author Abisoft - GT
* http://abisoftgt.net
**/

session_start();

include "core/autoload.php";

define("ROOT",dirname(__FILE__));


$lb = new Lb();
$lb->loadModule("index");

?>