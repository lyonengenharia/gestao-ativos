function handleData(data, textStatus, jqXHR) {
    $('#resultOfSearch').empty();
    $.each(data.data, function (i, item) {
        var row = "<div class=\"panel panel-default\">" +
            "<div class=\"panel-heading\"><b>" +
            item.CODBEM +
            "</b></div>" +
            "<div class=\"panel-body\">" +
            "<p><b>Data Aquisição:</b> " + item.DATAQI + " </p>" +
            "<p><b>Item:</b> " + item.DESBEM + " </p>" +
            "<p><b>Descrição:</b> " + item.DESESP + " </p>" +
            "<p><b>Empresa:</b> " + item.NOMEMP + " </p>" +
            "<button class=\"btn btn-primary  btn-xs selecao\" type=\"button\" data-toggle=\"collapse\" " +
            "data-target=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">" +
            "Marcar" +
            "</button>" +
            "</div>" +
            "<div style='display: none' class='codemp'>" + item.CODEMP + "</div>" +
            "</div>";
        $('#resultOfSearch').append(row);
    });
    $("#buttonSearch").empty();
    $("#buttonSearch").append("Pesquisar");
}

function trataModalLicenca(data, callback) {
    var LicencasAssociadas = $('#LicencasAssociadas');
    var Empresa_Produto = $('#Empresa-Produto');
    LicencasAssociadas.empty();
    Empresa_Produto.empty();
    if (data.length > 0) {
        $.each(data, function (i, item) {
            linha = "<div class=\"alert alert-warning\" role=\"alert\">" +
                "<p><b>Patrimônio: </b> <span class=\"patrimonio\">" + item.E670BEM_CODBEM + "</span> </p>" +
                "<p><b>Empresa: </b> " + item.iten[0].NOMEMP + "</span> </p>" +
                "<p><b>Centro Custo: </b>" + item.iten[0].DESCCU + "</span> </p>" +
                "<span class='emppatmodal' style='display: none'>" + item.E070EMP_CODEMP + "</span>" +
                "<button href=\"#\" class=\"btn btn-danger remover-associacao\">Remover</button>" +
                "</div>";
            LicencasAssociadas.append(linha);
        });
    } else {
        linha = "<div class=\"alert alert-success\" role=\"alert\">" +
            "<b>Nenhum item associado a essa licença</b>" +
            "</div>";
        LicencasAssociadas.append(linha);
    }
    callback();
}
function ErroConnect(Error) {
    if (Error.status == 401) {
        location.reload();
    }
}
function trataRetorno(data, pat, emp, key, url) {
    if (data.erro == 2) {

        swal({
            title: 'Você tem certeza?',
            text: data.msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, faça isso!',
            cancelButtonText: 'Não.', preConfirm: function () {
                return new Promise(function (resolve) {
                    resolve([
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {pat: pat, emp: emp, key: key, conf: 1},
                            dataType: 'json'
                        }).done(trataRetorno)
                    ]);
                });
            },
            allowOutsideClick: false
        });
    }
    else {
        swal({
            type: 'success',
            title: 'Sucesso!',
            html: data.msg,
            showCloseButton: true,
            onClose: function () {
                location.reload();
            }
        });
    }
}
function Products(data) {
    $.each(data, function (i, item) {
        $('#produto').append(new Option(item.model, item.id));
    });
}
//# sourceMappingURL=licencas.js.map
