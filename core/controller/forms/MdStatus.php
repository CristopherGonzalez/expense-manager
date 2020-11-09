<?php

class MdStatus
{
	public function __construct($name)
	{
		$this->name = $name;
		$this->disabled = "";
	}

	public function renderFormulario()
	{
		$user_session = UserData::getById($_SESSION['user_id']);
		$modal_content = new Modal("Estado","frm" . $this->name , $user_session);
		$html = $modal_content->renderInit();
		$Where = " empresa = ". $_SESSION['company_id']. "  and active=1 ";
		$stocks = StockData::dynamicQueryArray($Where . " and  MONTH(fecha) = MONTH(CURRENT_DATE())");
		$incomes = IncomeData::dynamicQueryArray($Where . " and pagado=0");
		$debts = DebtsData::dynamicQueryArray($Where . " and pagado=0 and   MONTH(fecha) = MONTH(CURRENT_DATE())");
		$expenses = ExpensesData::dynamicQueryArray($Where . " and pagado=0 and  MONTH(fecha_vence) = MONTH(CURRENT_DATE())");
		$html .= "
				
				<div class='modal-footer'>
					<div class='form-group'>
						<span class='col-xs-12'>
							<button type='button' id='btn_close' name='btn_close' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
							<button type='button' id='btn_save_debt' name='btn_save_debt' class='btn btn-primary " . ($this->disabled == 'disabled' ? 'hidden' : '') . "'>Enviar</button>
						</span>
					</div>
				</div>";
		$html .=  $modal_content->renderEnd(false, false, false);
		return $html;
	}
}
