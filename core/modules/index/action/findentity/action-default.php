<?php
if (isset($_REQUEST["id"])){
	$id=$_REQUEST["id"];
	$id=intval($id);
	$con = Database::getCon();
	$entity = EntityData::getById($id);
	echo $entity->tipo.",".$entity->category_id;
}
?>
