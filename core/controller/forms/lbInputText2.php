<?php
class lbInputText2{
	public function lbInputText2($name,$value){
		$this->name = $name;
		$this->value = $value;

	}
	public function render(){
		return "<label for='$this->name'> $this->value</label> <input type='text' class='form-control' id='$this->name' name='$this->name'>";
	}
	
}


?>
<?php 
	/*include 'core/controller/forms/lbInputText2.php';
	$prueba = new lbInputText2("txt_name_prueba","prueba");
	echo $prueba->render();*/
?>