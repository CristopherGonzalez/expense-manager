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
	$colors = [
		"#0069d9",
		"#ffc107",
		"#28a745",
		"#dc3545",
		"#6c757d",
		"#17a2b8",
		"#f60",
		"#7952b3",
		"#e83e8c"
	];
	$incomes = IncomeData::dinamycQuery($sWhere);
	$expenses = ExpensesData::dinamycQuery($sWhere);
	$partners = ResultData::dinamycQuery($sWhere);
	$sumIncomeMonth = IncomeData::sumIncome_Month($month, $_SESSION['company_id'], $year);
	$sumExpenseMonth = ExpensesData::sumExpenses_Month($month, $_SESSION['company_id'], $year);
	$sumIncomePayment = IncomeData::sumIncomeByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumExpensesPayment = ExpensesData::sumExpensesByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$resultSumMonth = (isset($sumIncomeMonth->total) ? $sumIncomeMonth->total : 0) - (isset($sumExpenseMonth->total) ? $sumExpenseMonth->total : 0);
	$result = array();
	$types = TypeData::getAllType();
	$dataIncomeResponse = array();
	$dataExpenseResponse = array();
	?>
	<script>
		var response = {
			totalSumMonthYear: "<?php echo $resultSumMonth; ?>",
			percentageSumMonthYear: "<?php echo ($resultSumMonth / (isset($sumExpenseMonth->total) ? $sumExpenseMonth->total : 1)) * 100; ?>"
		}
	</script>

	<?php if (isset($incomes) && is_array($incomes) && count($incomes) > 0) { ?>
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
										Ingresos $<?php echo $sumIncomeMonth->total; ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<span style="float:right;">Impagos</span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										$<?php echo $sumIncomePayment->amount; ?>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
										<?php echo round(($sumIncomePayment->amount*100)/$sumIncomeMonth->total); ?>%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">


								<div class="panel-group" id="accordion">
									<?php 
										$i = 0;
										foreach ($types as $type) {
												if ($type->tipo == "Ingreso") { ?>
											<div class="panel panel-default" style="border:0px !important;">
												<div class="panel-heading">
													<div class="row panel-title">
														<div class="col-md-2 col-sm-2 col-xs-2">
															<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i]; $i++; ?>;" >
																<?php 
																$sumIncomeType =  isset(IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
																echo round(($sumIncomeType/$sumIncomeMonth->total)*100, 2); ?>%
															</button>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-4">
															<?php echo $type->name; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo $sumIncomeType; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php $dataIncomeResponse[] = [
																"value"=> $sumIncomeType,
																"color"=>$colors[$i-1],
																"label"=>$type->name
																]; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
														</div>
															<?php 
																$sumIncomeTypePayment =  isset(IncomeData::sumIncomeByTypeAndPayment($_SESSION['company_id'],$type->id,$month,$year,0)->amount) ?  IncomeData::sumIncomeByTypeAndPayment($_SESSION['company_id'],$type->id,$month,$year,0)->amount : 0;
															?>
														<div class="col-md-1 col-sm-1 col-xs-1">
															$<?php echo $sumIncomeTypePayment; ?>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<?php 
															$sumIncomeType = $sumIncomeType==0?1:$sumIncomeType;
															echo round(($sumIncomeTypePayment*100)/$sumIncomeType,2); ?>%
														</div>
														<div class="col-md-1 col-sm-1 col-xs-1">
															<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseInc<?php echo $i-1;?>"></i>
														</div>
													</div>
												</div>

												<div id="collapseInc<?php echo $i-1;?>" class="panel-collapse collapse">
													<div class="panel-body">
														<?php 
															$incomesByType = IncomeData::IncomesByTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);
															foreach($incomesByType as $incomeByType){?>
																<div class="row">
																	<div class="col-md-2 col-sm-2 col-xs-2">
																	</div>
																	<div class="col-md-4 col-sm-4 col-xs-4">
																		<?php echo $incomeByType->description; ?>
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																		$<?php echo $incomeByType->amount; ?>
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																		<?php echo round(($incomeByType->amount*100)/$sumIncomeType, 2); ?>%
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																		$<?php
																			$incomePay = isset($incomeByType->pagado) && !boolval($incomeByType->pagado)?$incomeByType->amount: 0 ; 
																		echo $incomePay; ?>
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																		<?php 
																			$sumIncomeTypep = $sumIncomeTypePayment==0?1:$sumIncomeTypePayment;
																			echo round(($incomePay*100)/$sumIncomeTypep, 2); ?>%
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																	</div>
																</div>
															<?php }
														?>
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
		if (isset($expenses) && is_array($expenses) && count($expenses) > 0) { ?>
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
											Egresos $<?php echo $sumExpenseMonth->total; ?>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<span style="float:right;">Impagos</span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											$<?php echo $sumExpensesPayment->amount; ?>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<?php echo round(($sumExpensesPayment->amount*100)/$sumExpenseMonth->total); ?>%
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
										</div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">


									<div class="panel-group" id="accordionExpense">
										<?php 
											$i = 0;
											foreach ($types as $type) {
													if ($type->tipo == "Egreso") { ?>
												<div class="panel panel-default" style="border:0px !important;">
													<div class="panel-heading">
														<div class="row panel-title">
															<div class="col-md-2 col-sm-2 col-xs-2">
																<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i]; $i++; ?>;" >
																	<?php 
																	$sumExpensesType =  isset(ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
																	echo round(($sumExpensesType/$sumExpenseMonth->total)*100, 2); ?>%
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
																		"value"=> $sumExpensesType,
																		"color"=>$colors[$i-1],
																		"label"=>$type->name
																	]; ?>
															</div>
															<div class="col-md-1 col-sm-1 col-xs-1">
															</div>
																<?php 
																	$sumExpensesTypePayment =  isset(ExpensesData::sumExpensesByTypeAndPayment($_SESSION['company_id'],$type->id,$month,$year,0)->amount) ?  ExpensesData::sumExpensesByTypeAndPayment($_SESSION['company_id'],$type->id,$month,$year,0)->amount : 0;
																?>
															<div class="col-md-1 col-sm-1 col-xs-1">
																$<?php echo $sumExpensesTypePayment; ?>
															</div>
															<div class="col-md-1 col-sm-1 col-xs-1">
																<?php 
																$sumExpensesType = $sumExpensesType==0?1:$sumExpensesType;
																echo round(($sumExpensesTypePayment*100)/$sumExpensesType,2); ?>%
															</div>
															<div class="col-md-1 col-sm-1 col-xs-1">
																<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp<?php echo $i-1;?>"></i>
															</div>
														</div>
													</div>

													<div id="collapseExp<?php echo $i-1;?>" class="panel-collapse collapse">
														<div class="panel-body">
															<?php 
																$ExpensessByType = ExpensesData::ExpensesByTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);
																foreach($ExpensessByType as $ExpensesByType){?>
																	<div class="row">
																		<div class="col-md-2 col-sm-2 col-xs-2">
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<?php echo $ExpensesByType->description; ?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			$<?php echo $ExpensesByType->amount; ?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<?php echo round(($ExpensesByType->amount*100)/$sumExpensesType, 2); ?>%
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			$<?php
																				$ExpensesPay = isset($ExpensesByType->pagado) && !boolval($ExpensesByType->pagado)?$ExpensesByType->amount: 0 ; 
																			echo $ExpensesPay; ?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<?php 
																				$sumExpensesTypep = $sumExpensesTypePayment==0?1:$sumExpensesTypePayment;
																				echo round(($ExpensesPay*100)/$sumExpensesTypep, 2); ?>%
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																		</div>
																	</div>
																<?php }
															?>
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
	} else { ?>
	<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<strong>Debes seleccionar un año y un mes!</strong>
	</div>
<?php } ?>
<script>
	response.resultIncomeType = <?php echo json_encode($dataIncomeResponse); ?>;
	response.resultExpensesType = <?php echo json_encode($dataExpensesResponse); ?>;
	function changeIcon(input){
		var fa_status = null;
		input.classList.forEach(
			function(element){
				if(element=="fa-plus"){
					fa_status="fa-minus";
				}else if(element=="fa-minus"){
					fa_status="fa-plus";
				}
			}
		);
		if(fa_status!=null){
			var icons = document.getElementById('accordion').getElementsByClassName('fa');
				for(var i =0; i< icons.length; i++){
					icons[i].className = "fa fa-plus";
				}
			if(fa_status=="fa-plus"){
				input.className = "fa fa-plus collapsed";
			}else if(fa_status=="fa-minus"){
				input.className = "fa fa-minus";
			}
		}
	}

</script>