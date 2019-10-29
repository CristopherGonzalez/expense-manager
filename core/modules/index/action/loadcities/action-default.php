<?php
	$con = Database::getCon();
	//Se capturan los datos enviados por ajax
	$country = intval($_REQUEST['id']);
	$city_id_name = strip_tags($_REQUEST['city_name'], ENT_QUOTES);
	$mode = strip_tags($_REQUEST['mode'], ENT_QUOTES);

	if(isset($country) && !empty($country)){
		$cities = CityData::getByIdCountry($country);
	}
	if(isset($cities) && !empty($cities)){
		
		$city_select = new SelectList($city_id_name,"Ciudad",$cities);
		$city_select->tag="required";
		if(strcmp($mode,"modal")==0){ ?>
			<?php 
				echo $city_select->renderLabel('col-sm-2');
			?>
			<div class="col-sm-10">
				<?php echo $city_select->render(); ?>
			</div>
		<?php
		}else{
			echo $city_select->render();
		}
	}
?>