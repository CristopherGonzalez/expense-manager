<?php
/**
* @author abisoft-gt
*  Proceso de Login
**/

	if(!empty($_POST)){
		if($_POST["email"]!=""&&$_POST["password"]!=""){
			$con = Database::getCon();
			$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
			$license=mysqli_real_escape_string($con,(strip_tags($_POST["license"],ENT_QUOTES)));
			$password=sha1(md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)))));

			$company =  CompanyData::getByLicense($license);
			if($company==null && isset($company) && !empty($company)){ 
				Core::redir("./?view=index&alert=1"); 
			}else{
				
			$user = UserData::getLogin($email,$password);
			if($user!=null && ($user->empresa == $company->id)){
				$_SESSION["user_id"]=$user->id;
				$_SESSION["company_id"]=$user->empresa;
				Core::redir("./?view=home");
			}else{
				Core::redir("./?view=index&alert=1");
			}
		}else{
			Core::redir("./?view=index&alert=3");
		}
	}

?>