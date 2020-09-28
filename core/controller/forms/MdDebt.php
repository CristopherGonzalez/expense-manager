<?php

class MdDebt
{
	public function __construct($name)
	{
		$this->name = $name;
		$this->funjs = "";
		$this->tag = "";
	}

	public function renderFormulario()
	{
		$modal_content = new Modal("Generar Deuda", "frm" . $this->name, UserData::getById($_SESSION['user_id']));
		$html = $modal_content->renderInit();
		$entities = EntityData::getByType('Deudas', $_SESSION["company_id"]);
		$types = TypeData::getAllDebts();
		$entity_select = new SelectList("entity", "Entidad", $entities);
		$entity_select->funjs = "onchange=\"change_entity('type_debt','category');\"";
		$type_debt_select = new SelectList("type_debt", "Tipo", $types);
		$type_debt_select->tag = "disabled";
		$user_session = UserData::getById($_SESSION['user_id']);
		$html .= "
			<form class='form-horizontal' role='form' method='post' id='add_register' name='add_register' enctype='multipart/form-data'> 
				<div class='form-group'>
					<label for='date' class='col-sm-2 control-label'>Fecha</label>
					<div class='col-sm-10'>
						<input type='date' required class='form-control' id='date' name='date' placeholder='Fecha' required value='" . date("Y-m-d") . "'>
					</div>
				</div>
				<div class='form-group'>
					<label for='document_number' class='col-sm-2 control-label' style='padding-top:0px !important;'>Numero de Documento </label>
					<div class='col-sm-10'>
						<input type='text' class='form-control' id='document_number' name='document_number' required placeholder='Numero de Documento'>
					</div>
				</div>
				<div class='form-group'>
					<label for='description' class='col-sm-2 control-label'>Descripción </label>
					<div class='col-sm-10'>
						<textarea type='text' class='form-control' id='description' name='description' placeholder='Descripción' required></textarea>
					</div>
				</div>
				<div class='form-group'>
					<label for='amount' class='col-sm-2 control-label'>Importe </label>
					<div class='col-sm-7'>
						<input type='number' required class='form-control' id='amount' name='amount' placeholder='Importe' pattern='^[0-9]{1,9}(\.[0-9]{0,2})?$' title='Ingresa sólo números con 0 ó 2 decimales' maxlength='8'>
					</div>
					<label for='amount' class='col-sm-1 control-label'>Cuotas </label>
					<div class='col-sm-2'>
						<input type='number' required class='form-control' id='payment_fees' name='payment_fees' placeholder='Cuotas' min='1' max='60' title='Ingresa sólo números entre el 1 y 60' value='1'>
					</div>
				</div>

				<div class='form-group'>" .
			$entity_select->renderLabel('col-sm-2') . "
					<div class='col-sm-10'>" .
			$entity_select->render() . "
					</div>
				</div>
				<div class='form-group'>" .
			$type_debt_select->renderLabel('col-sm-2') . "
					<div class='col-sm-10'>" .
			$type_debt_select->render() . "
					</div>
				</div>
				<div class='form-group'>
					<span class='col-md-2 col-sm-2 col-xs-12'></span>
					<label for='document' class='col-sm-6'>Documento
						<input type='file' class='form-control' accept='image/*' id='document' name='document' onchange='load_image(this);'>
						<!--<input type='button' class='btn btn-default' id='btn_webcam_document' name='btn_webcam_document' value='Sacar Foto' data-toggle='modal' href='#frmwebcamdocument' onclick='add_parameters_from_webcam('document')'>-->
					</label>
					<div class='col-sm-4'>
						<img src='res/images/default_image.jpg' alt='Imagen en blanco a la espera de que carga de documento' class='img-thumbnail' id='document_image' height='60' width='75' >
					</div>
				</div>
				<div class='form-group'>
					<span class='col-md-2 col-sm-2 col-xs-12'></span>
					<label for='payment' class='col-sm-6'>Pago
						<input type='file' class='form-control' accept='image/*' id='payment' name='payment' onchange='load_image(this);'>
						<!--<input type='button' class='btn btn-default' id='btn_webcam_payment' name='btn_webcam_payment' value='Sacar Foto' data-toggle='modal' href='#frmwebcampayment' onclick='add_parameters_from_webcam('payment')'>-->
					</label>
					<div class='col-sm-4'>
						<img src='res/images/default_image.jpg' alt='Imagen en blanco a la espera de que carga de documento' class='img-thumbnail' id='payment_image'  height='60' width='75' >
					</div>
				</div>
				<div class='modal-footer'>
					<div class='form-group'>
					<span class='col-md-1 col-sm-1 col-xs-12'></span>
						<label class='col-md-7 col-sm-7' style='color:#999; font-weight:normal;'>Registrado por " . $user_session->name . " el " . date('Y-m-d') . "</label>
						<span class='col-md-4 col-sm-4 col-xs-12'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
							<button type='submit' id='btn_save_" . $this->name . "' name='btn_save_" . $this->name . "' class='btn btn-primary'>Agregar</button>
						</span>
					</div>
				</div>
			</form>";
		$html .=  $modal_content->renderEnd(false, false, false);
		return $html;
	}
}
