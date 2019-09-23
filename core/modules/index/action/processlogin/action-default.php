<?php
/**
* @author abisoft-gt
*  Proceso de Login
**/

	if(!empty($_POST)){
		if($_POST["email"]!=""&&$_POST["password"]!=""){
			$con = Database::getCon();
			$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
			$password=sha1(md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)))));
			$user = UserData::getLogin($email,$password);
			if($user!=null){
				$_SESSION["user_id"]=$user->id;
				Core::redir("./?view=home");
			}else{
				Core::redir("./?view=index&alert=1");
			}
		}else{
			Core::redir("./?view=index&alert=3");
		}
	}

?>