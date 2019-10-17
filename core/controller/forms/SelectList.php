<?php
class SelectList{
	public function SelectList($name,$value,$options){
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
	}
	/**
	 * @param $class_css string Parametro css por si quiere incluir una clase css
	 * @return $select  string select con el control creado
	 */
	public function render($class_css=""){
		$select =  "<select class='form-control select2' style='width: 100%' id='$this->name' name='$this->name' >";
		$select.= "<option >---SELECCIONA---</option>";
		foreach($this->options as $option){
			$select.="<option value=".$option->id.">".$option->name."</option>";
		}
		$select.="</select>";
		return $select;
	}
	public function renderLabel($class_css=""){
		return "<label for='$this->name' class='$class_css control-label'>$this->value</label>";
	}
	
}


?>
<?php 
	/*include 'core/controller/forms/lbInputText2.php';
	$prueba = new lbInputText2("txt_name_prueba","prueba");
	echo $prueba->render();
		ok<label for="entidad" class="col-sm-2 control-label">Entidad: </label>
		<div class="col-sm-10">
			<select class="form-control select2" style="width: 100%" name="entity" id="entity" >
				<option >---SELECCIONA---</option>
				<?php
					//Se carga datos de entidades en modal
					$entities=EntityData::getAll($_SESSION["user_id"]);
					foreach($entities as $entity){
				?>
					<option value="<?php echo $entity->id; ?>"><?php echo $entity->name; ?></option>
				<?php 
					}
				?>
			</select>
		</div>
	
	*/
?>