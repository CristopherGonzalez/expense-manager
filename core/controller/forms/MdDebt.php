<?php

class MdDebt
{
	public function __construct($name)
	{
		$this->name = $name;
		$this->disabled = "";
	}

	public function renderFormulario()
	{
		$modal_content = new Modal("Generar Deuda", "frm" . $this->name, UserData::getById($_SESSION['user_id']));
		$html = $modal_content->renderInit();
		$entities = EntityData::getByType('Deudas', $_SESSION["company_id"]);
		$types = TypeData::getAllDebts();
		$user_session = UserData::getById($_SESSION['user_id']);
		$html .= "
			<form id='generate_debt'> 
				<div class='form-group'>
					<label for='date' class='col-sm-3 control-label'>Fecha (*)</label>
					<div class='col-sm-9'>
						<input type='date' " . $this->disabled . " required class='form-control' id='debt_date' name='debt_date' placeholder='Fecha' required value='" . date("Y-m-d") .
			"'>
					</div>
				</div>
				<div class='form-group'>
					<label for='document_number' class='col-sm-3 control-label' style='padding-top:0px !important;'>Numero de Documento (*)</label>
					<div class='col-sm-9'>
						<input type='text' " . $this->disabled . "  class='form-control' id='debt_document_number' name='debt_document_number' required placeholder='Numero de Documento'>
					</div>
				</div>
				<div class='form-group'>
					<label for='description' class='col-sm-3 control-label'>Descripción (*)</label>
					<div class='col-sm-9'>
						<textarea type='text' " . $this->disabled . "  class='form-control' id='debt_description' name='debt_description' placeholder='Descripción' required></textarea>
					</div>
				</div>
				<div class='form-group'>
					<label for='amount' class='col-sm-3 control-label'>Importe (*)</label>
					<div class='col-sm-5'>
						<input type='number' " . $this->disabled . "  required class='form-control' id='debt_amount' name='debt_amount' placeholder='Importe' pattern='^[0-9]{1,9}(\.[0-9]{0,2})?$' title='Ingresa sólo números con 0 ó 2 decimales' maxlength='8'>
					</div>
					<label for='amount' class='col-sm-2 control-label'>Cuotas (*)</label>
					<div class='col-sm-2'>
						<input type='number' " . $this->disabled . "  required class='form-control' id='debt_payment_fees' name='debt_payment_fees' placeholder='Cuotas' min='1' max='60' title='Ingresa sólo números entre el 1 y 60' value='1'>
					</div>
				</div>

				<div class='form-group'>
					<label for='debt_entity' class='col-sm-3 control-label'>Entidad (*)</label>
				
					<div class='col-sm-9'>
						<select  required='required' " . $this->disabled . "  class='form-control' style='width: 100%' id='debt_entity' name='debt_entity' onchange='updateSelects();'>
							<option value=''>Selecciona una Entidad </option>";
		foreach ($entities as $entity) {
			$html .= "<option  value=" . $entity->id . ">" . $entity->name . "</option>";
		}
		$html .= "</select>
					</div>
				</div>
				<div class='form-group'>
					<label for='debt_type' class='col-sm-3 control-label'>Tipo (*)</label>

					<div class='col-sm-9'>
						<select disabled required='required' class='form-control' style='width: 100%' id='debt_type' name='debt_type'>
							<option value=''>Selecciona un Tipo </option>";
		foreach ($types as $type) {
			$html .= "<option  value=" . $type->id . ">" . $type->name . "</option>";
		}
		$html .= "</select>
					</div>
				</div>
				<div class='form-group'>
					<label for='document' class='col-sm-3 text-right'>Documento</label>
					<div class='col-sm-5'>
						<input type='file' " . $this->disabled . "  class='form-control' accept='image/*' id='debt_document' name='debt_document' onchange='load_image(this);'>
					</div>
					
					<div class='col-sm-4'>
						<img src='res/images/default_image.jpg' alt='Imagen en blanco a la espera de que carga de documento' class='img-thumbnail' id='debt_document_image' height='60' width='75' >
					</div>
				</div>
				<div class='form-group'>
					<label for='payment' class='col-sm-3  text-right'>Pago</label>
					<div class='col-sm-5'>
						<input type='file' " . $this->disabled . "  class='form-control' accept='image/*' id='debt_payment' name='debt_payment' onchange='load_image(this);'>
					</div>
					<div class='col-sm-4'>
						<img src='res/images/default_image.jpg' alt='Imagen en blanco a la espera de que carga de documento' class='img-thumbnail' id='debt_payment_image'  height='60' width='75' >
					</div>
				</div>
				<div class='form-group panel-body'>
					<span class='col-xs-12'>(*)<em>Estos valores son requeridos.</em></span>
					<span class='col-xs-12'>(**)<em>Si deseas cargar imagenes desde la cámara debes guardar y luego cargarla en la edición.</em></span>
				</div>
				<div class='alert alert-danger'  id='debt_result'  name='debt_result' role='alert'>Debes ingresar todos los campos requeridos y con el formato correcto.</div>
				<div class='modal-footer'>
					<div class='form-group'>
					<span class='col-md-1 col-sm-1 col-xs-12'></span>
						<label class='col-md-7 col-sm-7' style='color:#999; font-weight:normal;'>Registrado por " . $user_session->name . " el " . date('Y-m-d') . "</label>
						<span class='col-md-4 col-sm-4 col-xs-12'>
							<button type='button' id='btn_close_debt' name='btn_close_debt' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
							<button type='button' id='btn_save_debt' name='btn_save_debt' class='btn btn-primary " . ($this->disabled == 'disabled' ? 'hidden' : '') . "'>Agregar</button>
						</span>
					</div>
				</div>
			</form>";
		$html .=  $modal_content->renderEnd(false, false, false);
		return $html;
	}
}
