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
    debugger;
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