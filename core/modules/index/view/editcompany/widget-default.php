<?php 

if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] == "1"):
    if (isset($_GET['id']) && !empty($_GET['id'])){
        $id=$_GET["id"];
    }else{
        Core::redir("./?view=company");
    }

    //query
    $company=CompanyData::getById($id);
    $types=BusinessTypeData::getAll();
    $countries = CountryData::getAll();
    if(!isset($company) || empty($company) || $company->is_deleted == 1 ){
        Core::redir("./?view=company");
    }
    if(isset($company->profile_pic) && !empty($company->profile_pic)){ 
        $img = $company->profile_pic;
    }
?> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-edit"></i>Editar Empresa</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6"><!-- left column -->
                <form role="form" method="post" name="upd" id="upd"><!-- form start -->
                    <div class="box box-primary"> <!-- general form elements -->
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar Empresa</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label for="name" class="control-label">Nombre</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Nombre" value="<?php echo $company->name; ?>">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label for="name" class="control-label">Contraseña</label>
                                <input type="text" required class="form-control" id="password" name="password" placeholder="Constraseña" value="<?php echo Core::encrypt_decrypt('decrypt',$company->password); ?>">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <?php 
                                    $country_select = new SelectList("country_company","Pais",$countries);
                                    $country_select->funjs = "onchange=load_cities(this.value,'city_company','cities_response')";
                                    $country_select->tag="required";
                                    $country_select->default_value = $company->pais;
                                    echo $country_select->renderLabel();
                                    echo $country_select->render();
                                ?>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div id="cities_response">
                                    <?php 
                                        $cities = CityData::getByIdCountry($company->pais);
                                        $city_select = new SelectList("city_company","Ciudad",$cities);
                                        $city_select->default_value = $company->ciudad;
                                        $city_select->tag="required";
                                        echo $city_select->renderLabel();
                                        echo $city_select->render();
                                    ?>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <?php 
                                    $types_bussiness = new SelectList("types_bussiness","Tipo de Negocio",$types);
                                    $types_bussiness->default_value = $company->tipo_negocio;
                                    $types_bussiness->tag="required";
                                    echo $types_bussiness->renderLabel();
                                    echo $types_bussiness->render();
                                ?>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label for="email" class="control-label">Correo</label>
                                <input type="email" required class="form-control " name="email" id="email" placeholder="Correo" value="<?php echo $company->email; ?>">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label for="name" class="control-label">Licencia MRC</label>
                                <input type="text" required class="form-control" id="license" name="license" placeholder="Licencia MRC" value="<?php echo $company->licenciaMRC; ?>">
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label for="status">
                                    <input type="checkbox" id="status" name="status" <?php if($company->status==2){echo "checked";} ?> > Inhabilitado
                                </label>
                            </div>
                            <div class="form-group">
                                <?php $file_profile = new InputFile('profile_pic','Foto','image/*');
                                $file_profile->funjs =  "onchange='load_image(this);'";
                                $file_profile->file = (isset($img) && !empty($img))? $img : "res/images/companies/default.jpg";
                                echo $file_profile->render('col-md-8 col-sm-8 col-xs-12');
                                echo $file_profile->renderImage('col-md-4 col-sm-4 col-xs-12','Imagen del logo de la empresa',"data-toggle='modal' data-target='#formModal'");?>
                            </div>
                            <?php $modal_content = new ModalCategory("Imagen de perfil de Empresa","formModal",UserData::getById($_SESSION['user_id']));
                                echo $modal_content->renderInit();?>
                                <div class="form-group">
                                    <div class="col-md-2 col-sm-2 col-xs-12"></div>
                                    <div class="col-md-12 col-sm-12 col-xs-12"  style="display:inline-block;">
                                        <img id="profile_pic_modal" src="<?php echo(isset($img)? $img : $file_profile->file); ?>" class="img-thumbnail" alt="Imagen del documento">
                                    </div>
                                </div>
                            <?php echo $modal_content->renderEnd(false);?>  
                            <input type="hidden" required class="form-control" id="mod_id" name="mod_id" value="<?php echo $company->id; ?>">
                        </div><!-- /.box-body -->
                        <div class="box-footer text-right">
                            <label style="color:#999; font-weight:normal;">Registrado por <?php $creator_user=UserData::getById(1); echo $creator_user->name  ?> el <?php echo date("Y-d-m",strtotime($company->created_at));  ?></label>
                            <button type="submit" id="upd_data" class="btn btn-success">Actualizar</button>
                        </div>
                        <div id="result"></div>
                    </div> <!-- /.box -->
                </form>
            </div>
        </div>
    </section>     
</div>
<!-- /.content-wrapper -->
<?php include "res/resources/js.php"; ?>
<script>
    $( "#upd" ).submit(function( event ) {
        debugger;
        $('#upd_data').attr("disabled", true);
        var fd = new FormData($(this)[0]);
        fd.append("profile_pic", $('#profile_pic_image').attr('src'));
        var result = false;
        $.ajax({
            type: "POST",
            url: "./?action=updatecompany",
            data: fd,
            contentType: false,
            processData: false,
             beforeSend: function(objeto){
                $("#result").html("Mensaje: Cargando...");
              },
            success: function(datos){
                $("#result").html(datos);
                $('#upd_data').attr("disabled", false);
                window.setTimeout(function() {
                    $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();});}, 2000);
                result = true;
            }
        });
        event.preventDefault();
        window.setTimeout(function(){
            if (result){
                window.location.href="./?view=company";
            }
        }, 2000);
    })
</script>
<?php else: Core::redir("./"); endif;?> 