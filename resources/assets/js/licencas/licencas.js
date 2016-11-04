function handleData(data, textStatus, jqXHR) {
    $('#resultOfSearch').empty();
    $.each(data, function (i, item) {
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
//# sourceMappingURL=licencas.js.map
