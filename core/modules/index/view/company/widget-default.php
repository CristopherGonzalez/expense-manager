<?php 
if(isset($_SESSION["user_id"]) && $_SESSION['user_id']== "1"):
?> 
<?php  
    //Se obtienen datos para llenado de desplegables
    $company=CompanyData::getById($_SESSION["company_id"]);
    $types=BusinessTypeData::getAll();
    $countries = CountryData::getAll();
 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Empresas</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <!-- Se agregan nuevos filtros de mes, año, tipo de gasto y cambio en categoria del gasto -->
                    <div class="col-md-4 form-group">
                        <input type="text"  class="form-control" name="find_name" id="find_name" style="width: 100%;" placeholder="Buscar por Nombre" title="Ingresa algun texto para realizar la busqueda"  onkeyup="load(1);">
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text"  class="form-control" name="find_license" id="find_license" style="width: 100%;" placeholder="Buscar por Licencia MRC" title="Ingresa algun texto para realizar la busqueda"  onkeyup="load(1);">
                    </div>
                    <div class="col-md-4 form-group">
                        <?php 
                            $type_bussiness_find = new SelectList("type_bussiness_find","Tipo de Negocio",$types);
                            $type_bussiness_find->funjs = "onchange='load(1);'";
                            echo $type_bussiness_find->render(); 
                        ?>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-xs-1">
                        <div id="loader" class="text-center"></div>
                    </div>
                    <!-- <div class="col-md-offset-10"> -->
                    <div class=" pull-right">
                        <button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
                        <button class='btn btn-primary' data-toggle='modal' data-target='#formModal'><i class='fa fa-plus'></i> Nuevo</button>   
                        <?php $modal_content = new ModalCategory("Ingreso de Empresa","formModal",UserData::getById($_SESSION['user_id']));
                            echo $modal_content->renderInit();?>
                             <div class="form-group">
                                <?php 
                                    $name_text = new InputText("name","Nombre");
                                    echo $name_text->renderLabel('col-sm-2');
                                ?>
                                <div class="col-sm-10">
                                    <?php echo $name_text->render(); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php 
                                    $pass_text = new InputText("password","Contraseña");
                                    echo $pass_text->renderLabel('col-sm-2');
                                ?>
                                <div class="col-sm-10">
                                    <?php echo $pass_text->render(); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php 
                                    $country_select = new SelectList("country_company","Pais",$countries);
                                    $country_select->funjs = "onchange=load_cities(this.value,'city_company','cities_response','modal')";
                                    $country_select->tag="required";
                                    echo $country_select->renderLabel('col-sm-2');
                                ?>
                                <div class="col-sm-10">
                                    <?php echo $country_select->render(); ?>
                                </div>
                            </div>
                            <div class="form-group" id="cities_response"></div>
                            <div class="form-group">
                                <?php 
                                    $types_bussiness = new SelectList("types_bussiness","Tipo de Negocio",$types);
                                    $types_bussiness->tag="required";
                                    echo $types_bussiness->renderLabel('col-sm-2');
                                ?>
                                <div class="col-sm-10">
                                    <?php echo $types_bussiness->render(); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Correo</label>
                                <div class="col-sm-10">
                                    <input type="email" required class="form-control " name="email" id="email" placeholder="Correo">
                                </div>
                            </div>
                            <div class="form-group">
                                <?php 
                                    $license_text = new InputText("license","Licencia MRC");
                                    $license_text->tag="required";
                                    echo $license_text->renderLabel('col-sm-2');
                                ?>
                                <div class="col-sm-10">
                                    <?php echo $license_text->render(); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="col-md-2 col-sm-2 col-xs-12"></span>
                                <?php $file_profile = new InputFile('profile_pic','Foto','image/*');
                                $file_profile->funjs =  "onchange='load_image(this);'";
                                $file_profile->file = "res/images/companies/default.jpg";
                                echo $file_profile->render('col-sm-6');
                                echo $file_profile->renderImage('col-sm-4');?>
                            </div>
                           
                        <?php echo $modal_content->renderEnd();?>  
                        <!-- End Form Modal -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                Mostrar <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
                                <li  onclick='per_page(25);' id='25'><a href="#">25</a></li>
                                <li onclick='per_page(50);' id='50'><a href="#">50</a></li>
                                <li onclick='per_page(100);' id='100'><a href="#">100</a></li>
                                <li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
                            </ul>
                        </div>
                        <input type='hidden' id='per_page' value='15'>
                        <?php $companies=CompanyData::getAllCount();
                            if($companies->count!=0):
                        ?>
                        <div class="btn-group">
                            <a style="margin-right: 3px" target="_blank" href="reports/reportExpense.php" class="btn btn-default pull-right">
                                <span class="fa fa-file-excel-o"></span> Descargar
                            </a>
                        </div> 
                        <?php endif; ?> 
                    </div>
                </div>
            </div>    
        </div>    
        <br>
        <div id="resultados_ajax"></div><!-- Resultados Ajax -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Empresas</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <div class="outer_div"></div><!-- Datos ajax Final --> 
            </div>
            <!-- /.box-body -->
        </div><!-- /.box -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php   include "res/resources/js.php"; ?>

<script>
    $(function() {
        load(1);
        var date = new Date();
        date.getMonth()
        date.getFullYear()
    });
    function load(page){
       //Se obtienen filtros de busqueda
        var type_bussiness = $('#type_bussiness_find option:selected').val();
        var find_text = $('#find_name').val();
        var license_text = $('#find_license').val();

        var per_page=$("#per_page").val();
        var parametros = {
            "page":page,
            'type_bussiness':type_bussiness,
            'text':find_text,
            'license':license_text,
            'per_page':per_page };
        $.get({
            url:"./?action=loadcompanies",
            data:parametros,
            beforeSend: function(data){
                $("#loader").html("<img src='res/images/ajax-loader.gif'>");
            },
            //console.log(data);
            success:function(data){
                $(".outer_div").html(data);
                $("#loader").html("");
            }

        });
    }
    function per_page(valor){
        $("#per_page").val(valor);
        load(1);
        $('.dropdown-menu li' ).removeClass( "active" );
        $("#"+valor).addClass( "active" );
    }
    
    function eliminar(id){
        if(confirm('Esta acción  eliminará de forma permanente la empresa. \n\n Desea continuar?')){
            //Se obtienen filtros de busqueda para recarga y por estandar
            var type_bussiness = $('#type_bussiness_find option:selected').val();
            var find_text = $('#find_name').val();
            var license_text = $('#find_license').val();
            var page=1;
            var per_page=$("#per_page").val();
            var parametros = {
                "page":page,
                'type_bussiness':type_bussiness,
                'text':find_text,
                'license':license_text,
                'per_page':per_page,
                "id":id
             };

            $.get({
                // method: "GET",
                url:'./?action=loadcompanies',
                data: parametros,
                beforeSend: function(objeto){
                $("#loader").html("<img src='res/images/ajax-loader.gif'>");
              },
                success:function(data){
                    $(".outer_div").html(data).fadeIn('slow');
                    $("#loader").html("");
                    window.setTimeout(function() {
                    $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();});}, 5000);
                }
            })
        }
    }
    $( "#add_register" ).submit(function( input ) {
        $('#save_data').attr("disabled", true);
        //Se cambia forma de envio de formulario para soportar envio de imagenes
        var fd = new FormData($(this)[0]);
        fd.append("profile_pic", $('#profile_pic_image').attr('src'));
        $.ajax({
            type: "POST",
            url: "./?action=addcompany",
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function(objeto){
                    $("#resultados_ajax").html("Enviando...");
                },
                success: function(datos){
                    $("#resultados_ajax").html(datos);
                    $('#save_data').attr("disabled", false);
                    load(1);
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function(){
                        $(this).remove();});}, 5000);
                    $('#formModal').modal('hide');
                    clear_modal('add_register');
                    $('#profile_pic_image').attr("src","res/images/companies/default.jpg");
                }
        });
        input.preventDefault();
    })

//Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
    function load_cities(value,name,result,mode_view=""){
        if(value!=null && value!=undefined && name!=null && name!=undefined && result!=null && result!=undefined){
            var country = {
                id:value,
                city_name: name,
                mode:mode_view
            };
            $.ajax({
            type: "GET",
            url: "./?action=loadcities",
            data: country,
                beforeSend: function(objeto){
                    $("#"+result).html("Procesando por favor espere...");
                },
                success: function(datos){
                    if(result!=""){
                        $("#"+result).html(datos);
                    }
                }
            });
        }
    }
</script>
<?php else: Core::redir("./"); endif;?> 
