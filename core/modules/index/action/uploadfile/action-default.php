<?php
if (!isset($_SESSION['user_id'])){
	Core::redir("./");//Redirecciona 
	exit;
}
	//$con = Database::getCon();
	$id=intval($_SESSION['user_id']);
	$target_dir="res/images/upload/";
	$image_name = time()."_".basename($_FILES["img_file"]["name"]);
	$target_file = $target_dir .$image_name ;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$imageFileZise=$_FILES["img_file"]["size"];

	/* Inicio Validacion*/
	// Allow certain file formats
	if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) and $imageFileZise>0) {
	$errors[]= "<p>Lo sentimos, sólo se permiten archivos JPG , JPEG, PNG y GIF.</p>";
	} else if ($imageFileZise > 1048576) {//1048576 byte=1MB
	$errors[]= "<p>Lo sentimos, pero el archivo es demasiado grande. Selecciona una imagen menos de 1MB</p>";
	} else if (empty($id)){
		$errors[]= "<p>El ID está vacío.</p>";
	} else{
		/* Fin Validacion*/
		if ($imageFileZise>0){
		move_uploaded_file($_FILES["img_file"]["tmp_name"], $target_file);
		$imagen=basename($_FILES["img_file"]["name"]);
		$img_update="profile_pic='$image_name' ";
		}else { $img_update="";}
		$message[] = "Archivo subido al servidor con exito. ";

		/*
		if(isset($_FILES["img_file"]) && !empty($_FILES["img_file"])){
			if(isset($_FILES["img_file"]["tmp_name"]) && !empty($_FILES["img_file"]["tmp_name"])){
				$doc_file = addslashes(file_get_contents($_FILES["img_file"]["tmp_name"])); 
				if(isset($doc_file) && !empty($doc_file)){
					
				}
			}
		}*/
	}			
	?>
<?php 
	if (isset($errors)){
?>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Error! </strong>
		<?php
		foreach ($errors as $error){
				echo $error;
			}
		?>
	</div>	
<?php
	}
?>
<?php 
	if (isset($messages)){
?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Aviso! </strong>
		<?php
		foreach ($messages as $message){
				echo $message;
			}
		?>
	</div>	
<?php
	}
?>
