<?php
/**
* @author abisoft-gt
*  Proceso de Login
**/

	if(!empty($_POST)){
		if($_POST["email"]!=""&&$_POST["password"]!=""&&$_POST["license"]!=""){
			$con = Database::getCon();
			$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
			$license=mysqli_real_escape_string($con,(strip_tags($_POST["license"],ENT_QUOTES)));
			$password=sha1(md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES)))));

			$company =  CompanyData::getByLicense($license);
			if($company==null  || empty($company)){ 
				Core::redir("./?view=index&alert=1"); 
			}else{
				$user = UserData::getLogin($email,$password);
				if($user!=null && ($user->empresa == $company->id)){
					if($user->status == 1){
						Core::redir("./?view=index&alert=4");
					}else if($user->status == 2){
						Core::redir("./?view=index&alert=5");
					}else if($user->status == 3){
						$_SESSION["user_id"]=$user->id;
						$_SESSION["company_id"]=$user->empresa;
						Core::redir("./?view=home");
					}else if($user->status == 4){
						Core::redir("./?view=index&alert=6");
					}
					
				}else{
					Core::redir("./?view=index&alert=1");
				}
			}	
			
		}else{
			Core::redir("./?view=index&alert=3");
		}
	}

?>