
function handleData(data, textStatus, jqXHR) {
    console.log(data);
    $('#resultOfSearch').empty();
    if (data.length > 0) {
        $.each(data, function (i, item) {
            var row = "<div class=\"panel panel-default\">" +
                "<div class=\"panel-heading cod-bem\">" +
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
                "<hr>";
            if (item.state.length == 0) {
                row +=
                    "<p><b>Estado:</b>Sem definição</p>" +
                    "<p><b>Descrição:</b>Sem definição</p>";
            } else {
                row +=
                    "<button type=\"button\" class=\"close glyphicon glyphicon-pencil status-ben\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\"></span></button>"+
                    "<p><b>Estado:</b>" + item.state[0].state + "</p>" +
                    "<p><b>Descrição:</b>" + item.state[0].description + "</p>";
            }
            row +="<hr>";
            if (item.keys.length == 0) {
                row +=
                    "<p>Nenhuma licença associada</p>";
            } else {
                row += "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
                $.each(item.keys,function (l,key) {
                    vencimento = "Vitalício";
                    if(key.maturity_date!=null){
                        vencimento = DateUsTODateBr(key.maturity_date);
                    }
                    row +=
                        "<div class='panel panel-default'>"+
                            "<div class='panel-heading' role='tab' id='headin"+key.id+"'>"+
                                "<h4 class=\"panel-title\">"+
                                    "<a role='button' data-toggle='collapse'  href='#"+key.id+"' aria-expanded='true' aria-controls='"+key.id+"'>"
                                        +key.name+"/"+key.model+
                                    "</a>"+
                                "</h4>"+
                            "</div>"+
                            "<div id='"+key.id+"' class=\"panel-collapse collapsing\" role=\"tabpanel\" aria-labelledby='headin"+key.id+"'>"+
                                "<div class=\"panel-body\">"
                                    +"<span class='idkey' style='display: none' >"+key.keyid+"</span>"+
                                    "<b>Chave:</b>"+key.key + "  - <b>QTD</b> :"+key.quantity+"/<b>Em uso: </b>"+key.in_use+"<b> - Vencimento: </b>"+ vencimento
                                    +" <button class='btn btn-xs remove-key' title='Dissociar o item da licença.' ><span class='glyphicon glyphicon-resize-full'></span> </button>"+
                                "</div>" +
                            "</div>" +
                        "</div>" ;
                });
                row +=
                    "</div>"+
                    "</div>";

            }
            row +=
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
    }else {
        var alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
            "<strong>Atenção!</strong> Nenhum item encontrado!" +
            "</div>";
        $('#resultOfSearch').append(alert);
    }
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
            url = url.replace('emprestimo', 'search');
            updatesearch(url, {pat: data.codbem, emp: data.codbememp});
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
            url = url.replace('devolucao', 'search');
            updatesearch(url, {pat: data.codbem, emp: data.codbememp});
            $('#search').after(alert);
        },
    }).fail(ErroConnect);

}
function DevolucaoDados(url, data) {
    $.ajax({
        url: url + "/" + data.codbem + "/" + data.codbememp,
        type: 'get',
        data: data,
        dataType: 'json',
        success: function (response) {
            $('.info-devolucao').remove();
            var linha = "<div class='panel panel-default info-devolucao'><div class='panel-body'>" +
                "<p><b>Data empréstimo: </b>" + response[0].data_out + "</p>" +
                "<p><b>Colaborador: </b>" + response[0].colaborador.value + "</p>" +
                "<p><b>Situação do colaborador: </b>" + response[0].colaborador.DESSIT + "</p>" +
                "</div></div>";
            $('#devolucao-form').before(linha);
        }
    }).fail(ErroConnect);

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
            url = url.replace('associar', 'search');
            updatesearch(url, {pat: data.codbem, emp: data.codbememp});
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
            url = url.replace('dissociar', 'search');
            updatesearch(url, {pat: data.codbem, emp: data.codbememp});
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

function InsertState(url, data, callback) {
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function (response) {
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
            url = url.replace('state', 'search');
            updatesearch(url, {pat: data.codbem, emp: data.codbememp});
            $('#search').after(alert);
        }
    }).fail(ErroConnect).always(callback);
}
function GetState(url, data,callback) {
    $.ajax({
        url: url,
        type: 'get',
        data: data,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.length > 0) {
                $("#form-state select").val(response[0].state_id);
                $("#form-state textarea").val(response[0].description);
                $('#upddated_at').text(response[0].updated);
            } else {
                $("#form-state")[0].reset();
                $('#upddated_at').text("Não existe atualizações");
            }

        }
    }).fail(ErroConnect).always(callback);
}
function RemoveKey(url,data,callback) {
    $.ajax({
        url: url,
        type: 'delete',
        data: data,
        dataType: 'json',
        success: function (response) {
            var alert = "";
            if (response.error == 1) {
                alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg
                "</div>";
            } else if (response.error == 0) {
                $('.emprestimo-option').addClass('display-emprestismo');
                $('#resultOfSearch').removeClass('col-md-4');
                $('#resultOfSearch').addClass('col-md-12');
                alert = "<div class=\"alert alert-success alert-dismissible search\" role=\"alert\">" +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                    "<strong>Atenção!</strong> " + response.msg
                "</div>";
            }
            url = url.replace('licencas', 'ativos');
            url = url.replace('produto', 'search');
            url = url.replace('delete', '');
            updatesearch(url, {pat: data.pat, emp: data.emp});
            $('#search').after(alert);

        }
    }).fail(ErroConnect).always(callback);;
}
function updatesearch(url, data) {
    $.ajax({
        url: url,
        data: data,
        type: 'get',
        dataType: 'json'
    }).done(handleData)
      .fail(ErroConnect);
}
function DateUsTODateBr(date) {
    var d = new Date(date);
    return d.toLocaleDateString();

}
//# sourceMappingURL=ativos.js.map
