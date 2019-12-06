<?php
if ((isset($_POST['month']) && !empty($_POST['month'])) && (isset($_POST['year']) && !empty($_POST['year']))) {
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = intval($_POST['month']);
	$year = intval($_POST['year']);
	$company_id = $_SESSION["company_id"];
	$sWhere = " empresa=$company_id ";
	if ($month != 0) {
		$sWhere .= " and month(fecha) =" . $month;
	}
	if ($year != 0) {
		$sWhere .= " and year(fecha) = " . $year;
	}
	$incomes = IncomeData::dinamycQuery($sWhere);
	$expenses = ExpensesData::dinamycQuery($sWhere);

	if (isset($incomes) && is_array($incomes)) { 


		foreach($incomes as $income){?>
			<div class="panel panel-default" style="border:0px !important;">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-2">
							<button type="button" class="btn btn-primary">80%</button>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4">
							Compras Infraestructura
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							$400
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							40%
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							70%
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							$100
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							25%
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<i class='fa fa-plus'></i>
						</div>
					</div>


				</div>
				<div id="collapse1" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2 col-sm-2 col-xs-2">

							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								Empresas delivery
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								$70
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								7%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								70%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								$70
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								100%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								<i class="fa fa-minus"></i>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-sm-2 col-xs-2">

							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								RevistaS
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								$30
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								3%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								30%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								$30
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
								100%
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1">
							</div>
						</div>
					</div>
				</div>
			</div>

<?php	}

	} else { ?>
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados!</strong> No se encontraron resultados en la base de datos!.
		</div>
	<?php }
	} else { ?>
	<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<strong>Debes seleccionar un año y un mes!</strong>
	</div>
<?php } ?>





<div class="box-body">
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-12">
			<canvas id="doughnutExpenses" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
		</div>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-6">
							Egresos $800
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
						</div>
						<div class="col-md-2 col-sm-2 col-xs-2">
							<span style="float:right;">Impagos</span>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							$100
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
							25%
						</div>
						<div class="col-md-1 col-sm-1 col-xs-1">
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel-group" id="accordion">
						
						<div class="panel panel-default" style="border:0px !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-2">
										<button type="button" class="btn btn-danger">10%</button>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										Clientes
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$400
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										40%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										70%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$100
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										25%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<i class='fa fa-plus'></i>
									</div>
								</div>


							</div>
							<div id="collapse1" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="row">
										<div class="col-md-2 col-sm-2 col-xs-2">

										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											Empresas delivery
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											$70
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											7%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											70%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											$70
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											100%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<i class="fa fa-minus"></i>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2 col-sm-2 col-xs-2">

										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											RevistaS
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											$30
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											3%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											30%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											$30
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											100%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default" style="border:0px !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-2">
										<button type="button" class="btn btn-warning">5%</button>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										Personal
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$400
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										40%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										70%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$100
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										25%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<i class='fa fa-plus'></i>
									</div>
								</div>


							</div>

						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>