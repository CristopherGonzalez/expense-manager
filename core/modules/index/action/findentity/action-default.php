<?php
if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	$con = Database::getCon();
	$entity = EntityData::getById($id);
	echo $entity->tipo.",".$entity->category_id;
}
?>
