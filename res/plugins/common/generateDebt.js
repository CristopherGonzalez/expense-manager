var new_debt = '';
function addGenerateDebt() {
    $("#btn_new_debt").removeClass('btn-default');
    $("#btn_new_debt i").removeClass('fa-money');
    $("#btn_new_debt").addClass('btn-success');
    $("#btn_new_debt i").addClass('fa-check');
}
function cargaDatosDeuda() {
    $("#debt_date").val($('#date').val());
    $("#debt_amount").val($('#amount').val());
    $("#debt_description").val($('#description').val());
    $("#btn_new_debt").removeClass('btn-success');
    $("#btn_new_debt i").removeClass('fa-check');
    $("#btn_new_debt").addClass('btn-default');
    $("#btn_new_debt i").addClass('fa-money');
    $('#debt_result').css('display', 'none');
}

function updateSelects() {
    let entity = $('#debt_entity').val();
    if (entity != null && entity != undefined && entity != "0" && entity != "") {
        let entity_json = {
            id: entity
        };
        $.ajax({
            type: "GET",
            url: "./?action=findentity",
            data: entity_json,
            success: function (datos) {
                if (datos != "" && datos != null && datos != " " && datos != undefined) {
                    let options = datos.split(",");
                    $("#debt_type").val(options[0]);
                }
            }
        });
    } else {
        $("#debt_type").val(0);
        $("#debt_type").attr('disabled', true);

    }
}
$(document).ready(function () {

    $("#btn_close_debt").click(function () {
        window.new_debt = '';
    });

    $("#btn_save_debt").click(function (event) {
        let debt_date = document.getElementById("debt_date");
        let debt_amount = document.getElementById("debt_amount");
        let debt_document_number = document.getElementById("debt_document_number");
        let debt_description = document.getElementById("debt_description");
        let debt_payment_fees = document.getElementById("debt_payment_fees");
        let debt_entity = document.getElementById("debt_entity");
        let debt_type = document.getElementById("debt_type");
        if (!debt_date.checkValidity() ||
            !debt_document_number.checkValidity() ||
            !debt_description.checkValidity() ||
            !debt_payment_fees.checkValidity() ||
            !debt_amount.checkValidity() ||
            !debt_entity.checkValidity() ||
            !debt_type.checkValidity()) {   // test for validity
            $('#debt_result').css('display', 'block');
            event.preventDefault();
            event.stopPropagation();
        } else {
            addGenerateDebt();
            let newDebt = {
                date: debt_date.value,
                document_number: debt_document_number.value,
                description: debt_description.value,
                amount: debt_amount.value,
                payment_fees: debt_payment_fees.value,
                entity: $('#debt_entity option:selected').val(),
                type: $('#debt_type option:selected').val(),
                document_image: $('#debt_document_image').attr('src'),
                payment_image: $('#debt_payment_image').attr('src'),
            }
            //console.log(newDebt);
            // let newDebt = new FormData();
            // newDebt.append("date", debt_date.value);
            // newDebt.append("document_number", debt_document_number.value);
            // newDebt.append("description", debt_description.value);
            // newDebt.append("payment_fees", debt_payment_fees.value);
            // newDebt.append("entity", $('#debt_entity option:selected').val());
            // newDebt.append("type", $('#debt_type option:selected').val());
            // newDebt.append("document", $('#debt_document').attr('src'));
            // newDebt.append("payment", $('#debt_payment').attr('src'));
            window.new_debt = newDebt;
            $('#debt_result').css('display', 'none');
            $('#frmdebt').modal('hide');

        }
    });
});
