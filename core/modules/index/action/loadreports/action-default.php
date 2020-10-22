<?php
if ((isset($_POST['year']) && !empty($_POST['year']))) {
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$month = intval($_POST['month']);
	$year = intval($_POST['year']);
	$company_id = $_SESSION["company_id"];
	$stockWhere = " empresa=$company_id ";
	$stockSelect = "Select sum(amount) as total_before_month ";

	if ($month != 0 && $month != 1) {
		$stockWhere .= " and month(fecha) =" . ($month - 1);
	} else {
		$stockWhere .= " and month(fecha) =" . 12;
		$stockWhere .= " and year(fecha) = " . ($year - 1);
	}

	if ($year != 0) {
		if ($month != 1 && $month != 0) {
			$stockWhere .= " and year(fecha) = " . ($year);
		}
	} else {
		return "Debes seleccionar un año";
	}

	$colors = [
		"#28a745",
		"#6c757d",
		"#dc3545",
		"#0069d9",
		"#ffc107",
		"#17a2b8",
		"#ff6600",
		"#7952b3",
		"#e83e8c"
	];
	// $incomes = IncomeData::dinamycQuery($sWhere);
	// $expenses = ExpensesData::dinamycQuery($sWhere);
	// $partners = ResultData::dinamycQuery($sWhere);

	$sumIncomeMonth = IncomeData::sumIncomeByDate($month, $_SESSION['company_id'], $year);
	$sumExpenseMonth = ExpensesData::sumExpenses_Month($month, $_SESSION['company_id'], $year);
	$sumPartnerMonth = ResultData::sumPartner_Month($month, $_SESSION['company_id'], $year);
	$sumIncomeImpayment = IncomeData::sumIncomeByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumExpensesImpayment = ExpensesData::sumExpensesByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumPartnersWithdrawal = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'], $month, $year, true);
	$sumPartnersContribution = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'],  $month, $year, false);
	$sumPartnersWithdrawalPayment = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'], $month, $year, true, true);
	$sumPartnersWithdrawalImpayment = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'], $month, $year, true,false);
	$sumPartnersContributionPayment = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'], $month, $year, false, true);
	$sumPartnersContributionImpayment = ResultData::sumPartnerByPaymentStatusByDateAndAmount($_SESSION['company_id'], $month, $year, false, false);
	$sumPartnersImpayment = ResultData::sumPartnerByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumPartnersPayment = ResultData::sumPartnerByPaymentStatusByDate($_SESSION['company_id'], 1, $month, $year);
	$sumStockByDate = StockData::sumStockByDate($_SESSION['company_id'], $month, $year);
	//$sumStocksBeforeDate = StockData::dinamycQuery($sWhere);
	$sumStocksBeforeDate = StockData::dinamycAllQuery($stockWhere, $stockSelect, false);
	$resultSumMonth = (isset($sumIncomeMonth->total) ? $sumIncomeMonth->total : 0) - (isset($sumExpenseMonth->total) ? $sumExpenseMonth->total : 0);
	$result = array();
	$types = TypeData::getAllType();
	$dataIncomeResponse = array();
	$dataExpensesResponse = array();
?>
	<script>
		var response = {
			totalSumMonthYear: "<?php echo $resultSumMonth; ?>",
			percentageSumMonthYear: "<?php echo round(($resultSumMonth / (isset($sumIncomeMonth->total) ? $sumIncomeMonth->total : 1)) * 100, 2); ?>"
		}
	</script>





	<?php if ((isset($_POST["annual"]) && !empty($_POST["annual"]) && $_POST['annual'] == "true") && ((isset($sumIncomeMonth) && $sumIncomeMonth->total > 0) || (isset($sumExpenseMonth) && $sumExpenseMonth->total > 0))) { ?>

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Resultados de la Gestión</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<?php $sumExpensesAnnual =  ExpensesData::sumExpensesAnnual($_SESSION['company_id'], $year); ?>
			<?php $sumIncomeAnnual =  IncomeData::sumIncomeAnnual($_SESSION['company_id'], $year); ?>
			<?php $sumDebtsAnnual =  DebtsData::sumDebtsAnnual($_SESSION['company_id'], $year); ?>
			<script>
				response.annual_expenses = [
					"<?php echo number_format($sumExpensesAnnual->enero ? $sumExpensesAnnual->enero :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->febrero ? $sumExpensesAnnual->febrero :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->marzo ? $sumExpensesAnnual->marzo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->abril ? $sumExpensesAnnual->abril :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->mayo ? $sumExpensesAnnual->mayo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->junio ? $sumExpensesAnnual->junio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->julio ? $sumExpensesAnnual->julio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->agosto ? $sumExpensesAnnual->agosto :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->septiembre ? $sumExpensesAnnual->septiembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->octubre ? $sumExpensesAnnual->octubre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->noviembre ? $sumExpensesAnnual->noviembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumExpensesAnnual->diciembre ? $sumExpensesAnnual->diciembre :  0, 2, ',', ''); ?>"
				];
				response.annual_incomes = [
					"<?php echo number_format($sumIncomeAnnual->enero ? $sumIncomeAnnual->enero : 0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->febrero ? $sumIncomeAnnual->febrero :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->marzo ? $sumIncomeAnnual->marzo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->abril ? $sumIncomeAnnual->abril :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->mayo ? $sumIncomeAnnual->mayo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->junio ? $sumIncomeAnnual->junio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->julio ? $sumIncomeAnnual->julio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->agosto ? $sumIncomeAnnual->agosto :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->septiembre ? $sumIncomeAnnual->septiembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->octubre ? $sumIncomeAnnual->octubre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->noviembre ? $sumIncomeAnnual->noviembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumIncomeAnnual->diciembre ? $sumIncomeAnnual->diciembre :  0, 2, ',', ''); ?>"
				];
				response.annual_debts = [
					"<?php echo number_format($sumDebtsAnnual->enero ? $sumDebtsAnnual->enero :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->febrero ? $sumDebtsAnnual->febrero :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->marzo ? $sumDebtsAnnual->marzo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->abril ? $sumDebtsAnnual->abril :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->mayo ? $sumDebtsAnnual->mayo :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->junio ? $sumDebtsAnnual->junio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->julio ? $sumDebtsAnnual->julio :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->agosto ? $sumDebtsAnnual->agosto :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->septiembre ? $sumDebtsAnnual->septiembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->octubre ? $sumDebtsAnnual->octubre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->noviembre ? $sumDebtsAnnual->noviembre :  0, 2, ',', ''); ?>",
					"<?php echo number_format($sumDebtsAnnual->diciembre ? $sumDebtsAnnual->diciembre :  0, 2, ',', ''); ?>"
				];
				// console.log(response.annual_expenses);
				// console.log(response.annual_incomes);
				// console.log(response.annual_debts);
			</script>
			<div class="box-body table-responsive">
				<canvas id="LineAnnual" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
			</div>

		</div>

	<?php } ?>



	<?php if (isset($sumIncomeMonth) && $sumIncomeMonth->total > 0) { ?>
		<div class="box" style="background:#f5f5f5 !important;" id="reportIncome">
			<div class="box-header  with-border">
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-12">
						<canvas id="doughnutIncome" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
					</div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row panel-title">
									<div class="col-md-6 col-sm-6 col-xs-6">
										Ingresos $<?php echo  round($sumIncomeMonth->total, 2); ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<span style="float:right;">Impagos</span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$<?php echo  round($sumIncomeImpayment->amount, 2); ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<?php echo round(($sumIncomeImpayment->amount * 100) / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total), 2); ?>%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel-group" id="accordionIncome">
									<div class="row panel-heading">
										<div class="col-md-7 col-sm-7 col-xs-7"></div>
										<div class="col-md-1 col-sm-1 col-xs-1"></div>
										<div class="col-md-1 col-sm-1 col-xs-1"></div>
										<div class="col-md-2 col-sm-2 col-xs-2">Impago</div>
									</div>
									<?php
									$i = 0;
									$j = 0;
									foreach ($types as $type) {
										if ($type->tipo == "Ingreso") {
											$sumIncomeType =  isset(IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
											if ($sumIncomeType == 0) {
												continue;
											}; ?>
											<div class="panel panel-default" style="border:0px !important;">
												<div class="panel-heading">
													<div class="row panel-title">
														<div class="col-md-2 col-sm-2 col-xs-2">
															<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i];
																																	$i++; ?>;">
																<?php
																echo round(($sumIncomeType / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total)) * 100, 2); ?>%
															</button>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-4">
															<?php echo $type->name; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo $sumIncomeType; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php
															$dataIncomeResponse[] = [
																"value" => $sumIncomeType,
																"color" => $colors[$i - 1],
																"label" => $type->name
															]; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
														</div>
														<?php
														$sumIncomeTypePayment =  isset(IncomeData::sumIncomeByTypeAndPayment($_SESSION['company_id'], $type->id, $month, $year, 0)->amount) ?  IncomeData::sumIncomeByTypeAndPayment($_SESSION['company_id'], $type->id, $month, $year, 0)->amount : 0;
														?>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo $sumIncomeTypePayment; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php
															$sumIncomeType = $sumIncomeType == 0 ? 1 : $sumIncomeType;
															echo round(($sumIncomeTypePayment * 100) / $sumIncomeType, 2); ?>%
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordionIncome" href="#collapseInc<?php echo $i - 1; ?>"></i>
														</div>
													</div>
												</div>
												<div id="collapseInc<?php echo $i - 1; ?>" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="panel-group" id="accordionIncomeUnit">
															<div class="panel panel-default" style="border:0px !important;">
																<?php
																$incomesByType = IncomeData::IncomesByCategoryTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);
																foreach ($incomesByType as $incomeByType) {
																	$j++; ?>
																	<div class="panel">
																		<div class="row panel-title">
																			<div class="col-md-2 col-sm-2 col-xs-2">
																			</div>
																			<div class="col-md-4 col-sm-4 col-xs-4">
																				<?php echo $incomeByType->description; ?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				$<?php echo  round($incomeByType->amount, 2); ?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<?php echo round(($incomeByType->amount * 100) / $sumIncomeType, 2); ?>%
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				$<?php
																					$incomePay = IncomeData::sumIncomeCategoryByTypeAndPayment($_SESSION['company_id'], $type->id, $incomeByType->category_id, $month, $year, 0);
																					$incomeAmountPayment = isset($incomePay) ? $incomePay->amount : 0;
																					echo  round($incomeAmountPayment, 2);
																					?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<?php
																				$sumIncomeTypep = $incomeByType->amount == 0 ? 1 : $incomeByType->amount;
																				echo round(($incomeAmountPayment * 100) / $sumIncomeTypep, 2); ?>%
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseSubInc<?php echo $j; ?>"></i>
																			</div>
																		</div>
																	</div>
																	<div id="collapseSubInc<?php echo $j; ?>" class="panel-collapse collapse">
																		<div class="panel-body">
																			<!-- este row es el que se repite-->
																			<?php $IncomesByCategory = IncomeData::IncomesByCategoryIdAndDate($_SESSION['company_id'], $incomeByType->category_id, $month, $year);
																			foreach ($IncomesByCategory as $IncomeByCategory) {
																			?>
																				<div class="row">
																					<div class="col-md-2 col-sm-2 col-xs-2">
																					</div>
																					<div class="col-md-4 col-sm-4 col-xs-4">
																						<?php echo $IncomeByCategory->description; ?>
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						$<?php echo  round($IncomeByCategory->amount, 2); ?>
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						<?php echo round(($IncomeByCategory->amount / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total)) * 100, 2); ?>%
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						<?php echo round(($IncomeByCategory->amount / $sumIncomeType) * 100, 2); ?>%
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						$<?php echo ($IncomeByCategory->pagado == "0") ? $IncomeByCategory->amount : 0; ?>
																					</div>
																					<div class="col-md-2 col-sm-2 col-xs-2">
																						<a href="./?view=editincome&id=<?php echo $IncomeByCategory->id ?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a>

																					</div>
																				</div>
																			<?php }
																			?>
																		</div>
																	</div>
																<?php }
																?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php	} ?>
									<?php	} ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados de Ingresos!</strong> No se encontraron resultados en la base de datos!.
		</div>
	<?php }
	if (isset($sumExpenseMonth) && $sumExpenseMonth->total > 0) { ?>
		<div class="box" style="background:#f5f5f5 !important;" id="reportExpenses">
			<div class="box-header  with-border">
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-12">
						<canvas id="doughnutExpenses" style="width: 500px; height: 500px;" width="500" height="500"></canvas>
					</div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row panel-title">
									<div class="col-md-6 col-sm-6 col-xs-6">
										Egresos $<?php echo  round($sumExpenseMonth->total, 2); ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<span style="float:right;">Impagos</span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$<?php echo  round($sumExpensesImpayment->amount, 2); ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<?php echo round(($sumExpensesImpayment->amount * 100) / ($sumExpenseMonth->total == 0 ? 1 : $sumExpenseMonth->total), 2); ?>%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel-group" id="accordionExpense">
									<div class="row panel-heading">
										<div class="col-md-7 col-sm-7 col-xs-7"></div>
										<div class="col-md-1 col-sm-1 col-xs-1">Ing</div>
										<div class="col-md-1 col-sm-1 col-xs-1"></div>
										<div class="col-md-2 col-sm-2 col-xs-2">Impago</div>
									</div>
									<?php
									$i = 0;
									$j = 0;
									foreach ($types as $type) {
										if ($type->tipo == "Egreso") {
											$sumExpensesType =  isset(ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
											if ($sumExpensesType == 0) {
												continue;
											}; ?>
											<div class="panel panel-default" style="border:0px !important;">
												<div class="panel-heading">
													<div class="row panel-title">
														<div class="col-md-2 col-sm-2 col-xs-2">
															<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i];
																																	$i++; ?>;">

																<?php echo round(($sumExpensesType / ($sumExpenseMonth->total == 0 ? 1 : $sumExpenseMonth->total)) * 100, 2); ?>%
															</button>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-4">
															<?php echo $type->name; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo $sumExpensesType; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php
															$dataExpensesResponse[] = [
																"value" => $sumExpensesType,
																"color" => $colors[$i - 1],
																"label" => $type->name
															];
															echo round(($sumExpensesType / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total)) * 100, 2); ?>%
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
														</div>
														<?php
														$sumExpensesTypePayment =  isset(ExpensesData::sumExpensesByTypeAndPayment($_SESSION['company_id'], $type->id, $month, $year, 0)->amount) ?  ExpensesData::sumExpensesByTypeAndPayment($_SESSION['company_id'], $type->id, $month, $year, 0)->amount : 0;
														?>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo  round($sumExpensesTypePayment, 2); ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php
															$sumExpensesType = $sumExpensesType == 0 ? 1 : $sumExpensesType;
															echo round(($sumExpensesTypePayment * 100) / $sumExpensesType, 2); ?>%
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordionExpense" href="#collapseExp<?php echo $i - 1; ?>"></i>
														</div>
													</div>
												</div>
												<div id="collapseExp<?php echo $i - 1; ?>" class="panel-collapse collapse">
													<div class="panel-body">
														<div class="panel-group" id="accordionExpenseUnit">
															<div class="panel panel-default" style="border:0px !important;">
																<?php
																$ExpensesByType = ExpensesData::expensesByCategoryTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);
																foreach ($ExpensesByType as $ExpenseByType) {
																	$j++; ?>
																	<div class="panel">
																		<div class="row panel-title">
																			<div class="col-md-2 col-sm-2 col-xs-2">
																			</div>
																			<div class="col-md-4 col-sm-4 col-xs-4">
																				<?php echo $ExpenseByType->description; ?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				$<?php echo  round($ExpenseByType->amount, 2); ?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<?php echo round(($ExpenseByType->amount / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total)) * 100, 2); ?>%
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<?php echo round(($ExpenseByType->amount / $sumExpensesType) * 100, 2); ?>%
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				$<?php
																					$ExpensesPay = ExpensesData::expensesCategoryByTypeAndPayment($_SESSION['company_id'], $type->id, $ExpenseByType->category_id, $month, $year, 0);
																					$ExpensesAmountPayment = isset($ExpensesPay) ? $ExpensesPay->amount : 0;
																					echo  round($ExpensesAmountPayment, 2);
																					?>
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<?php
																				$sumExpensesTypep = $ExpenseByType->amount == 0 ? 1 : $ExpenseByType->amount;
																				echo round(($ExpensesAmountPayment * 100) / $sumExpensesTypep, 2); ?>%
																			</div>
																			<div class="col-md-1 col-sm-1 col-xs-1">
																				<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseSubExp<?php echo $j; ?>"></i>
																			</div>
																		</div>
																	</div>
																	<div id="collapseSubExp<?php echo $j; ?>" class="panel-collapse collapse">
																		<div class="panel-body">
																			<!-- este row es el que se repite-->
																			<?php $ExpensesByCategory = ExpensesData::ExpensesByCategoryIdAndDate($_SESSION['company_id'], $ExpenseByType->category_id, $month, $year);
																			foreach ($ExpensesByCategory as $ExpenseByCategory) {
																			?>
																				<div class="row">
																					<div class="col-md-2 col-sm-2 col-xs-2">
																					</div>
																					<div class="col-md-4 col-sm-4 col-xs-4">
																						<?php echo $ExpenseByCategory->description; ?>
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						$<?php echo  round($ExpenseByCategory->amount, 2); ?>
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						<?php echo round(($ExpenseByCategory->amount / ($sumIncomeMonth->total == 0 ? 1 : $sumIncomeMonth->total)) * 100, 2); ?>%
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						<?php echo round(($ExpenseByCategory->amount / $sumExpensesType) * 100, 2); ?>%
																					</div>
																					<div class="col-md-1 col-sm-1 col-xs-1">
																						$<?php echo  round(($ExpenseByCategory->pagado == "0") ? $ExpenseByCategory->amount : 0, 2); ?>
																					</div>
																					<div class="col-md-2 col-sm-2 col-xs-2">
																						<a href="./?view=editexpense&id=<?php echo $ExpenseByCategory->id ?>" target="_blank"><span class="glyphicon glyphicon-list-alt"></span></a>
																					</div>
																				</div>
																			<?php }
																			?>
																		</div>
																	</div>
																<?php }
																?>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php	} ?>
									<?php	} ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados de Egresos!</strong> No se encontraron resultados en la base de datos!.
		</div>
	<?php }
	if (isset($sumPartnerMonth) && $sumPartnerMonth->total > 0) { ?>
		<div class="box" style="background:#f5f5f5 !important;" id="reportPartner">
			<div class="box-header  with-border">
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-12">
					</div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row panel-title">
									<div class="col-md-2 col-sm-2 col-xs-2">
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										Socios $<?php echo $sumPartnerMonth->total; ?>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<span style="float:right;">Impagos</span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$<?php echo $sumPartnersImpayment->amount; ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<?php echo round(($sumPartnersImpayment->amount / $sumPartnerMonth->total) * 100, 2); ?>%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="panel-group" id="accordion">
									<div class="row panel-heading">
										<div class="col-md-7 col-sm-7 col-xs-7"></div>
										<div class="col-md-1 col-sm-1 col-xs-1"></div>
										<div class="col-md-1 col-sm-1 col-xs-1"></div>
										<div class="col-md-2 col-sm-2 col-xs-2">Impago</div>
									</div>
									<?php
									$i = 0;
									$PartnersByEntity = ResultData::partnersByEntityGroup($_SESSION['company_id'], $month, $year);
									foreach ($PartnersByEntity as $PartnerByEntity) {
									?>
										<div class="panel panel-default" style="border:0px !important;">
											<div class="panel-heading">
												<div class="row panel-title">
													<div class="col-md-2 col-sm-2 col-xs-2">
													</div>
													<div class="col-md-4 col-sm-4 col-xs-4">
														<?php echo $PartnerByEntity->description; ?>
													</div>
													<div class="col-md-1 col-sm-1 col-xs-1">
														$<?php echo $PartnerByEntity->amount; ?>
													</div>
													<div class="col-md-1 col-sm-1 col-xs-1">
													</div>
													<div class="col-md-1 col-sm-1 col-xs-1">
													</div>
													<?php
													$sumPartnerPayment =  isset(ResultData::sumPartnerByPaymenStatusAndEntity($_SESSION['company_id'], 0, $PartnerByEntity->entidad, $month, $year)->amount) ? ResultData::sumPartnerByPaymenStatusAndEntity($_SESSION['company_id'], 0, $PartnerByEntity->entidad, $month, $year)->amount : 0;
													?>
													<div class="col-md-1 col-sm-1 col-xs-1">
														$<?php echo $sumPartnerPayment; ?>
													</div>
													<div class="col-md-1 col-sm-1 col-xs-1">
														<?php
														$PartnerByEntityAmount = $PartnerByEntity->amount == 0 ? 1 : $PartnerByEntity->amount;
														echo round(($sumPartnerPayment / $PartnerByEntityAmount) * 100, 2); ?>%
													</div>
													<div class="col-md-1 col-sm-1 col-xs-1">
													</div>
												</div>
											</div>
										</div>
									<?php	} ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados de Socios!</strong> No se encontraron resultados en la base de datos!.
		</div>
	<?php }

	if ((isset($sumIncomeMonth) && $sumIncomeMonth->total > 0) || (isset($sumExpenseMonth) && $sumExpenseMonth->total > 0)) { ?>

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					Resultado de la Gestión
				</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>+ Valores Iniciales</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo round($sumStocksBeforeDate->total_before_month ? $sumStocksBeforeDate->total_before_month : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>+ Aportes Socios</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo round($sumPartnersContribution->amount ? $sumPartnersContribution->amount : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>- Retiros Socios </strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo  round($sumPartnersWithdrawal->amount ? $sumPartnersWithdrawal->amount : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>+ Ingresos pagados</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php $inc = $sumIncomeMonth->total ? $sumIncomeMonth->total : 0 ?>
						<?php $incp = $sumIncomeImpayment->amount ? $sumIncomeImpayment->amount : 0 ?>
						<label>$<?php echo round($inc - $incp, 2); ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>- Egresos pagados</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php $exp = $sumExpenseMonth->total ? $sumExpenseMonth->total : 0 ?>
						<?php $expp = $sumExpensesImpayment->amount ? $sumExpensesImpayment->amount : 0 ?>
						<label>$<?php echo round($exp - $expp, 2); ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>+ Deuda generada</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php $sumDebImpay = DebtsData::sumDebtsByPay($_SESSION['company_id'], $year, $month, 0) ?>
						<?php $sumDebPay = DebtsData::sumDebtsByPay($_SESSION['company_id'], $year, $month, 1) ?>
						<?php $debImp = $sumDebImpay->total ? $sumDebImpay->total : 0 ?>
						<?php $debPay = $sumDebPay->total ? $sumDebPay->total : 0 ?>
						<label>$<?php echo round($debImp, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>- Deuda pagada</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo round($debPay ? $debPay : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>= Valores teorico</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$
							<?php
							$valor_inicial = $sumStocksBeforeDate->total_before_month ? $sumStocksBeforeDate->total_before_month : 0;
							$socio_aporte = $sumPartnersPayment->amount ? $sumPartnersPayment->amount : 0;
							$socio_retiro = $sumPartnersImpayment->amount ? $sumPartnersImpayment->amount : 0;
							$saldo_socio = $socio_aporte - $socio_retiro;
							$ingresos = $sumIncomeMonth->total;
							$egresos = $sumExpenseMonth->total;
							$deuda_pagada = $debPay;
							$deuda_generada = $debImp;
							$valores_teoricos =  ($valor_inicial + $saldo_socio + $ingresos - $egresos + $deuda_generada - $deuda_pagada);
							echo round($valores_teoricos, 2);
							$diferencia =  ($sumStockByDate->amount ? $sumStockByDate->amount : 0) - ($valores_teoricos > 0 ? $valores_teoricos : $valores_teoricos * -1);
							?>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Valores real</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo round($sumStockByDate->amount ? $sumStockByDate->amount : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Diferencia</strong></label>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label>$<?php echo round($diferencia, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<h4 style="float:right;"><strong>Resultado de la gestión</strong></h4>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<h4><strong><?php echo  round(((abs($diferencia) / round($sumStockByDate->amount ? $sumStockByDate->amount : 0, 2)) * 100), 2); ?>%</strong></h4>
					</div>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right">
							<strong>
								<h4>Impagos</h4>
							</strong>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>mes/año</label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>total</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Ingresos</strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo round($sumIncomeImpayment->amount ? $sumIncomeImpayment->amount : 0, 2) ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumIncomeMonth->total ? $sumIncomeMonth->total : 0, 2) ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Egresos</strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumExpensesImpayment->amount ? $sumExpensesImpayment->amount : 0, 2) ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumExpenseMonth->total ? $sumExpenseMonth->total : 0, 2) ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Socios aportes </strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumPartnersContributionImpayment->amount, 2); ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo round($sumPartnersContribution->amount ? $sumPartnersContribution->amount : 0, 2); ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Socios retiros</strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumPartnersWithdrawalImpayment->amount, 2); ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($sumPartnersWithdrawal->amount ? $sumPartnersWithdrawal->amount : 0, 2); ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong>Deuda</strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($debImp, 2); ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo  round($debPay + $debImp, 2); ?></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<label style="float:right"><strong></strong></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo round(($sumExpensesImpayment->amount ? $sumExpensesImpayment->amount : 0) + $socio_retiro + $debImp, 2); ?></label>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<label>$<?php echo round(($sumExpenseMonth->total ? $sumExpenseMonth->total : 0) + ($sumPartnerMonth->total ? $sumPartnerMonth->total : 0) + ($debPay + $debImp), 2); ?></label>
					</div>
				</div>

			</div>
			<!-- /.box-body -->
		</div>
	<?php } else { ?>
		<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Sin Resultados de Ingresos o egreso </strong> No se puede generar el resultado de la gestión!.
		</div>
	<?php }
} else { ?>
	<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<strong>Debes seleccionar un año y un mes!</strong>
	</div>
<?php } ?>
<script>
	response.resultIncomeType = <?php echo json_encode($dataIncomeResponse); ?>;
	response.resultExpensesType = <?php echo json_encode($dataExpensesResponse); ?>;

	function changeIcon(input) {
		var fa_status = null;
		input.classList.forEach(
			function(element) {
				if (element == "fa-plus") {
					fa_status = "fa-plus";
				} else if (element == "fa-minus") {
					fa_status = "fa-minus";
				}
			}
		);
		if (fa_status != null) {
			input.classList.remove(fa_status);
			if (fa_status == "fa-plus") {
				input.classList.add("fa-minus");
			} else if (fa_status == "fa-minus") {
				input.classList.add("fa-plus");
			}
		}
	}
</script>