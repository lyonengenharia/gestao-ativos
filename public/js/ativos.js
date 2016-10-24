/**
 * Created by wfs on 24/10/2016.
 */
function handleData(data, textStatus, jqXHR) {
    $('#resultOfSearch').empty();
    $.each(data, function (i, item) {
        var row = "<div class=\"panel panel-default\">" +
            "<div class=\"panel-heading\">" +
            item.CODBEM +
            "</div>" +
            "<div class='result-emp' style='display: none'>" +
            item.CODEMP +
            "</div>" +
            "<div class=\"panel-body\">" +
            "<p><b>Data Aquisição:</b> " + item.DATAQI + " </p>" +
            "<p><b>Item:</b> " + item.DESBEM + " </p>" +
            "<p><b>Descrição:</b> " + item.DESESP + " </p>" +
            "<p><b>Empresa:</b> " + item.NOMEMP + " </p>" +
            "</div>" +
            "<div class='panel-footer'> " +
            "<div class=\"btn-group\">" +
            "<button class=\"btn btn-primary localizacoes\" type=\"button\">" +
            "<span class='glyphicon glyphicon-map-marker'></span>Localizações" +
            "</button>";
        if (item.ASSOC) {
            row += "<button class=\"btn btn-danger dissociar-colaborador\" type=\"button\">" +
                "<span class='glyphicon glyphicon-user'></span> Desassociar" +
                "</button>";
        } else {
            row += "<button class=\"btn btn-default associar-colaborador\" type=\"button\">" +
                "<span class='glyphicon glyphicon-user'></span> Associar" +
                "</button>";
        }
        if (item.EMPRST == 0 && item.ASSOC == 0) {
            row += "<button class=\"btn btn-success emprestimo\" type=\"button\">" +
                "<span class='glyphicon glyphicon-transfer'></span> Emprestimo" +
                "</button>" +
                "</div>" +
                "</div>" +
                "</div>";
        } else if (item.ASSOC == 1) {
            row += "<button class=\"btn btn-success emprestimo\" type=\"button\" disabled>" +
                "<span class='glyphicon glyphicon-transfer'></span> Emprestimo" +
                "</button>" +
                "</div>" +
                "</div>" +
                "</div>";
        } else {
            row += "<button class=\"btn btn-warning devolucao\" type=\"button\">" +
                "<span class='glyphicon glyphicon-retweet'></span> Devolução" +
                "</button>" +
                "</div>" +
                "</div>" +
                "</div>";
        }

        $('#resultOfSearch').append(row);

    });
    $("#buttonSearch").empty();
    $("#buttonSearch").append("<span class=\"glyphicon glyphicon-search\"></span> Pesquisar");
}
function historyLocations(data, textStatus, jqXHR) {
    $('#historicoLocalizacoes').empty();
    $.each(data.Locations, function (i, item) {
        var row = "<div class=\"panel panel-default\">" +
            "<div class=\"panel-body\">" +
            "<p><b>Data Aquisição:</b> " + item.DATLOC + " </p>" +
            "<p><b>Descrição:</b> " + item.DESLOC + " </p>" +
            "</div>" +
            "</div>";
        $('#historicoLocalizacoes').append(row);
    });
    $('#historyFinancialList').empty();
    $.each(data.MovFinancial, function (i, item) {
        console.log(item);
        var row = "<div class=\"panel panel-default\">" +
            "<div class=\"panel-body\">" +
            "<p><b>Data Movimentação:</b> " + item.DATMOV + " </p>" +
            "<p><b>Descrição:</b> " + item.DESTNS + " </p>" +
            "<p><b>Centro Custo:</b> " + item.CODCCU + " </p>" +
            "</div>" +
            "</div>";
        if (item.CODTNS == 90804) {
            var alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                "<strong>Atenção!</strong> Esse item foi vendido!." +
                "</div>";
            $('#search').after(alert);
        }
        if (item.CODTNS != 90815) {
            $('#historyFinancialList').append(row);
        }
    });


}
function Emprestimo(url, data) {
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        success: function (response) {
            var alert = "";
            if (response.erro == 1) {
                alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg
                "</div>";
            } else if (response.erro == 0) {
                $('.emprestimo-option').addClass('display-emprestismo');
                $('#resultOfSearch').removeClass('col-md-4');
                $('#resultOfSearch').addClass('col-md-12');
                alert = "<div class=\"alert alert-success alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg
                "</div>";

            }
            $('#search').after(alert);
        }
    }).fail(ErroConnect);
}
function Devolucao(url, data) {
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (response) {
            var alert = "";
            if (response.error == 1) {
                alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            } else if (response.error == 0) {
                $('.devolucao-option').addClass('display-emprestismo');
                $('#resultOfSearch').removeClass('col-md-4');
                $('#resultOfSearch').addClass('col-md-12');
                alert = "<div class=\"alert alert-success alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            }
            $('#search').after(alert);
        },
    }).fail(ErroConnect);
    ;


}
function DevolucaoDados(url, data) {
    $.ajax({
        url: url + "/" + data.codbem + "/" + data.codbememp,
        type: 'get',
        data: data,
        dataType: 'json',
        success: function (response) {
            var linha = "<div class='panel panel-default'><div class='panel-body'>" +
                "<p>Data empréstimo:" + response[0].data_saida + "</p>" +
                "</div></div>";
            $('#devolucao-form').before(linha);
        }
    }).fail(ErroConnect);
    ;
}
function Associar(url, data) {
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function (response) {
            var alert = "";
            if (response.error == 1) {
                alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            } else if (response.error == 0) {
                $('.associacao-option').addClass('display-emprestismo');
                $('#resultOfSearch').removeClass('col-md-4');
                $('#resultOfSearch').addClass('col-md-12');
                alert = "<div class=\"alert alert-success alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            }
            $('#search').after(alert);
        }
    }).fail(ErroConnect);
}
function Dissociar(url, data) {
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (response) {
            var alert = "";
            if (response.error == 1) {
                alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            } else if (response.error == 0) {
                alert = "<div class=\"alert alert-success alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg +
                    "</div>";
            }
            $('#search').after(alert);
            $('#modal-desassociar').modal('hide');
        }
    }).fail(ErroConnect);
}
function ErroConnect(Error) {
    if (Error.status == 401) {
        location.reload();
    }
}
//# sourceMappingURL=ativos.js.map
