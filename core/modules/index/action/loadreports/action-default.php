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
	//$incomes = IncomeData::dinamycQuery($sWhere);
	//$expenses = ExpensesData::dinamycQuery($sWhere);
	//$partners = ResultData::dinamycQuery($sWhere);
	$sumIncomeMonth = IncomeData::sumIncome_Month($month, $_SESSION['company_id'], $year);
	$sumExpenseMonth = ExpensesData::sumExpenses_Month($month, $_SESSION['company_id'], $year);
	$sumPartnerMonth = ResultData::sumPartner_Month($month, $_SESSION['company_id'], $year);
	$sumIncomePayment = IncomeData::sumIncomeByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumExpensesPayment = ExpensesData::sumExpensesByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$sumPartnersPayment = ResultData::sumPartnerByPaymentStatusByDate($_SESSION['company_id'], 0, $month, $year);
	$resultSumMonth = (isset($sumIncomeMonth->total) ? $sumIncomeMonth->total : 0) - (isset($sumExpenseMonth->total) ? $sumExpenseMonth->total : 0);
	$result = array();
	$types = TypeData::getAllType();
	$dataIncomeResponse = array();
	$dataExpensesResponse = array();
	?>
	<script>
		var response = {
			totalSumMonthYear: "<?php echo $resultSumMonth; ?>",
			percentageSumMonthYear: "<?php echo round(($resultSumMonth / (isset($sumIncomeMonth->total) ? $sumIncomeMonth->total : 1)) * 100,2); ?>"
		}
	</script>

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
										<?php echo round(($sumIncomePayment->amount*100)/($sumIncomeMonth->total==0?1:$sumIncomeMonth->total),2); ?>%
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1">
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row panel-heading">
									<div class="col-md-7 col-sm-7 col-xs-7"></div>
									<div class="col-md-1 col-sm-1 col-xs-1"></div>
									<div class="col-md-1 col-sm-1 col-xs-1"></div>
									<div class="col-md-2 col-sm-2 col-xs-2">Impago</div>
								</div>
								<div class="panel-group" id="accordion">
									<?php 
										$i = 0;
										foreach ($types as $type) {
												if ($type->tipo == "Ingreso") { 
													$sumIncomeType =  isset(IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  IncomeData::sumIncomeByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
													if($sumIncomeType==0){continue;};?>
											<div class="panel panel-default" style="border:0px !important;">
												<div class="panel-heading">
													<div class="row panel-title">
														<div class="col-md-2 col-sm-2 col-xs-2">
															<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i]; $i++; ?>;" >
																<?php 
																echo round(($sumIncomeType/($sumIncomeMonth->total==0?1:$sumIncomeMonth->total))*100, 2); ?>%
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
															$incomesByType = IncomeData::IncomesByCategoryTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);

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
																			$incomePay = IncomeData::sumIncomeCategoryByTypeAndPayment($_SESSION['company_id'], $type->id,$incomeByType->category_id, $month, $year,0);
																			$incomeAmountPayment = isset($incomePay) ? $incomePay->amount : 0; 
																			echo $incomeAmountPayment;
																		?>
																	</div>
																	<div class="col-md-1 col-sm-1 col-xs-1">
																		<?php 
																			$sumIncomeTypep = $incomeByType->amount==0?1:$incomeByType->amount;
																			echo round(($incomeAmountPayment*100)/$sumIncomeTypep, 2); ?>%
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
										<div class="row panel-heading">
											<div class="col-md-7 col-sm-7 col-xs-7"></div>
											<div class="col-md-1 col-sm-1 col-xs-1">Ing</div>
											<div class="col-md-1 col-sm-1 col-xs-1"></div>
											<div class="col-md-2 col-sm-2 col-xs-2">Impago</div>
										</div>
										<?php 
											$i = 0;
											foreach ($types as $type) {
													if ($type->tipo == "Egreso") { 
														$sumExpensesType =  isset(ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount) ?  ExpensesData::sumExpensesByType($_SESSION['company_id'], $type->id, $month, $year)->amount : 0;
														if($sumExpensesType==0){continue;};?>

												<div class="panel panel-default" style="border:0px !important;">
													

													<div class="panel-heading">
														<div class="row panel-title">

															<div class="col-md-2 col-sm-2 col-xs-2">
																	
																<button type="button" class="btn" style="color:#f5f5f5;background-color:<?php echo  $colors[$i]; $i++; ?>;" >
																
																<?php echo round(($sumExpensesType/$sumExpenseMonth->total)*100, 2); ?>%
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
																	];
																	echo round(($sumExpensesType/($sumIncomeMonth->total==0?1:$sumIncomeMonth->total))*100, 2); ?>%

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
																$ExpensesByType = ExpensesData::expensesByCategoryTypeAndDate($_SESSION['company_id'], $type->id, $month, $year);
																foreach($ExpensesByType as $ExpenseByType){?>
																	<div class="row">
																		<div class="col-md-2 col-sm-2 col-xs-2">
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																			<?php echo $ExpenseByType->description; ?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			$<?php echo $ExpenseByType->amount; ?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<?php echo round(($ExpenseByType->amount/($sumIncomeMonth->total==0?1:$sumIncomeMonth))*100, 2); ?>%
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<?php echo round(($ExpenseByType->amount/$sumExpensesType)*100, 2); ?>%
																		</div>

																		<div class="col-md-1 col-sm-1 col-xs-1">
																			$<?php
																				$ExpensesPay = ExpensesData::expensesCategoryByTypeAndPayment($_SESSION['company_id'], $type->id,$ExpenseByType->category_id, $month, $year,0);
																				$ExpensesAmountPayment = isset($ExpensesPay) ? $ExpensesPay->amount : 0; 
																				echo $ExpensesAmountPayment;
																			?>
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<?php 
																				$sumExpensesTypep = $ExpenseByType->amount==0?1:$ExpenseByType->amount;
																				echo round(($ExpensesAmountPayment*100)/$sumExpensesTypep, 2); ?>%
																		</div>
																		<div class="col-md-1 col-sm-1 col-xs-1">
																			<i class='fa fa-plus' onclick="changeIcon(this)" data-toggle="collapse" data-parent="#accordion" href="#collapseExp<?php echo $i-1;?>"></i>
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
											$<?php echo $sumPartnersPayment->amount; ?>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<?php echo round(($sumPartnersPayment->amount/$sumPartnerMonth->total)*100,2); ?>%
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
																	$sumPartnerPayment =  isset(ResultData::sumPartnerByPaymenStatusAndEntity($_SESSION['company_id'],0,$PartnerByEntity->entidad,$month,$year)->amount)? ResultData::sumPartnerByPaymenStatusAndEntity($_SESSION['company_id'],0,$PartnerByEntity->entidad,$month,$year)->amount : 0;
																?>
															<div class="col-md-1 col-sm-1 col-xs-1">
																$<?php echo $sumPartnerPayment; ?>
															</div>
															<div class="col-md-1 col-sm-1 col-xs-1">
																<?php 
																$sumPartnerPayment = $sumPartnerPayment==0?1:$sumPartnerPayment;
																echo round(($sumPartnerPayment/$PartnerByEntity->amount)*100,2); ?>%
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