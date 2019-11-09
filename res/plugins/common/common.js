function upload_image_generic(input) {
    if (input != "" && input != null && input != undefined) {
        var inputFileImage = input.files;
        if (inputFileImage != undefined && inputFileImage != null && inputFileImage != "") {
            var file = inputFileImage[0];
            var data = new FormData();
            data.append("img_file", file);
            $.ajax({
                type: "POST", // Type of request to be send, called as method
                url: "./?action=uploadfile", // Url to which the request is send
                data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function(data) // A function to be called if request succeeds
                    {
                        $("#load_img").html(data);
                    }
            });
        }
    }

}
//Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
function load_image(input) {
    if (input != "" && input != null && input != undefined) {
        var inputFileImage = input.files;
        if (inputFileImage != undefined && inputFileImage != null && inputFileImage != "") {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + input.name + '_image').attr('src', e.target.result);
                    $('#' + input.name + '_modal').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#' + input.name + '_image').attr('src', 'res/images/default_image.jpg');
                $('#' + input.name + '_modal').attr('src', 'res/images/default_image.jpg');

            }
        }
    }
}
//Funcion para recargar imagen cuando se cambia de valor la imagen del documento o del pago
function load_cities(value, name, result, mode_view = "") {
    if (value != null && value != undefined && name != null && name != undefined && result != null && result != undefined) {
        var country = {
            id: value,
            city_name: name,
            mode: mode_view
        };
        $.ajax({
            type: "GET",
            url: "./?action=loadcities",
            data: country,
            beforeSend: function(objeto) {
                $("#" + result).html("Procesando por favor espere...");
            },
            success: function(datos) {
                if (result != "") {
                    $("#" + result).html("");
                    $("#" + result).html(datos);
                }
            }
        });
    }
}
//Funcion para cargar el log de errores dependiendo del id de registro y la tabla
function load_change_log(id_registro, table_name, result) {
    if (id_registro != null && id_registro != undefined && table_name != null && table_name != undefined && result != null && result != undefined) {
        var parametros = {
            id_registro: id_registro,
            table: table_name
        };
        $.ajax({
            type: "GET",
            url: "./?action=loadchange_log",
            data: parametros,
            beforeSend: function(objeto) {
                $("#" + result).html("Procesando por favor espere...");
            },
            success: function(datos) {
                if (result != "") {
                    $("#" + result).html("");
                    $("#" + result).html(datos);
                }
            }
        });
    }
}
//Funcion para cargar select de gasto fijo  
function load_select_fixed_cost() {
    var month_fixed_cost = $('#month_fixed_cost option:selected').val();
    var year_fixed_cost = $('#year_fixed_cost option:selected').val();
    var slt_fixed_cost = [];
    $('#btn_generate').attr("disabled", true);
    $('#md_fixed_cost input:checked').each(function() {
        slt_fixed_cost.push($(this).attr('name'));
    });
    if (month_fixed_cost != null && month_fixed_cost != undefined &&
        year_fixed_cost != null && year_fixed_cost != undefined &&
        slt_fixed_cost != null && slt_fixed_cost != undefined &&
        slt_fixed_cost.length != null && slt_fixed_cost.length > 0) {
        var parameters = {
            month: month_fixed_cost,
            year: year_fixed_cost,
            opt_type: slt_fixed_cost
        };
        $.ajax({
            type: "GET",
            url: "./?action=loadfixed_cost",
            data: parameters,
            beforeSend: function(objeto) {
                $("#result_fixed_cost").html("");
                $("#result_fixed_cost").html("Procesando por favor espere...");
            },
            success: function(data) {
                $("#result_fixed_cost").html("");
                $("#result_fixed_cost").html(data);
                var slt_fixed_cost = $('#slc_fixed_cost option:selected').val();
                if (slt_fixed_cost != undefined && slt_fixed_cost != null && slt_fixed_cost != "") {
                    $('#btn_generate').attr("disabled", false);
                } else {
                    $('#btn_generate').attr("disabled", true);
                }
            }
        });
    } else {
        $("#result_fixed_cost").html("");
        $("#result_fixed_cost").html("<div class='alert alert-info alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><strong>Debes seleccionar al menos un tipo de gasto.</strong></div>");
    }
}
$('#btn_generate').on("click", function() {
    var month_fixed_cost = $('#month_fixed_cost option:selected').val();
    var year_fixed_cost = $('#year_fixed_cost option:selected').val();
    var month_from_cost = $('#month_from_cost option:selected').val();
    var year_from_cost = $('#year_from_cost option:selected').val();
    if (!isNaN(month_fixed_cost)) { month_fixed_cost = parseInt(month_fixed_cost); }
    if (!isNaN(year_fixed_cost)) { year_fixed_cost = parseInt(year_fixed_cost); }
    if (!isNaN(month_from_cost)) { month_from_cost = parseInt(month_from_cost); }
    if (!isNaN(year_from_cost)) { year_from_cost = parseInt(year_from_cost); }

    if (year_fixed_cost > year_from_cost) {
        alert("El año de copia debe ser menor o igual que el año a generar");
        return false;
    } else {
        if (year_fixed_cost == year_from_cost) {
            if (month_fixed_cost >= month_from_cost) {
                alert("La fecha de copia debe ser menor que la fecha a generar");
                return false;
            }
        }
        var expenses = [];
        $('#btn_generate').attr("disabled", true);
        $('#slc_fixed_cost option').each(function() {
            expenses.push($(this).attr('value'));
        });
        var parameters = {
            month: month_from_cost,
            year: year_from_cost,
            expenses: expenses
        };
        $.ajax({
            type: "GET",
            url: "./?action=addfixed_cost",
            data: parameters,
            beforeSend: function(objeto) {
                $("#result_fixed_cost").html("");
                $("#result_fixed_cost").html("Procesando por favor espere...");
            },
            success: function(data) {
                $("#result_fixed_cost").html("");
                $("#result_fixed_cost").html(data);
                $('#btn_generate').attr("disabled", true);
                window.setTimeout(function() {
                    window.location.href = "./?view=expenses";
                }, 2000);
            }
        });
    }

});