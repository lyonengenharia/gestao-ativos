function handleData(data) {


    $('#resultOfSearch').empty();
    if (data.data.length > 0) {
        $.each(data.data, function (i, item) {
            var Emprestimo = '';
            var connection = '';
            var state = '';
            var keys = '';
            var Assoc = '';
            var Empres = '';
            if (item.connect != null) {
                connection = "<button type=\"button\" class=\"close glyphicon glyphicon-file termos-modal\" data-dismiss=\"alert\" aria-label=\"Close\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Tooltip on left\"><span aria-hidden=\"true\"></span></button>"
                    + "<p><b>Colaborador:</b>" + item.connect[0].value + " <b>Matrícula:</b>  " + item.connect[0].id + "  <b>Situação:</b> " + item.connect[0].DESSIT + "</p>"
                    + "<div style='display: none'><span class='assoc-numemp'>" + item.connect[0].NUMEMP + "</span><span class='assoc-sitafa'>" + item.connect[0].SITAFA + "</span><span class='assoc-tipcol'>" + item.connect[0].TIPCOL + "</span><span class='assoc-id'>" + item.connect[0].id + "</span> </div> ";
            }
            var History = null;
            if (item.history.length > 0) {
                History = "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
                $.each(item.history, function (i, his) {
                    row = "<div class='panel panel-default'>" +
                        "<div class='panel-heading' role='tab' id='headin" + his.id + "'>" +
                        "<h4 class=\"panel-title\">" +
                        "<a role='button' data-toggle='collapse'  href='#" + his.id + "' aria-expanded='true' aria-controls='" + his.id + "'>" +
                        his.Employed.value +
                        "</a>" +
                        "</h4>" +
                        "</div>" +
                        "<div id='" + his.id + "' class=\"panel-collapse collapsing\" role=\"tabpanel\" aria-labelledby='headin" + his.id + "'>" +
                        "<div class=\"panel-body\">" +
                        "<p>Funcionário: " + his.Employed.value + " Matrícula: " + his.Employed.id + "</p>" +
                        "<p>Data associação: " + his.data_in + " Data dissociação:" + his.data_out + " </p>" +
                        "<p>Observação de associação:" + his.obs_in + "</p>" +
                        "<p>Observação de dissociação:" + his.obs_in + "</p>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                    History += row;
                });
                History += "</div>";
            } else {
                History = "<p>Sem histórico de associações.</p>";
            }
            connection += History;
            if (item.state.length == 0) {
                state =
                    "<button type=\"button\" class=\"close glyphicon glyphicon-pencil status-ben\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\"></span></button>" +
                    "<p><b>Estado:</b>Sem definição</p>" +
                    "<p><b>Descrição:</b>Sem definição</p>";
            } else {
                state =
                    "<button type=\"button\" class=\"close glyphicon glyphicon-pencil status-ben\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\"></span></button>" +
                    "<p><b>Estado:</b>" + item.state[0].state + "</p>" +
                    "<p><b>Descrição:</b>" + item.state[0].description + "</p>" +
                    "<p><b>Observação:</b>" + item.state[0].desc + "</p>";
            }
            if (item.keys.length == 0) {
                keys =
                    "<p>Nenhuma licença associada</p>";
            } else {
                keys = "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
                $.each(item.keys, function (l, key) {
                    vencimento = "Vitalício";
                    if (key.maturity_date != null) {
                        vencimento = DateUsTODateBr(key.maturity_date);
                    }
                    keys +=
                        "<div class='panel panel-default'>" +
                        "<div class='panel-heading' role='tab' id='headin" + key.id + "'>" +
                        "<h4 class=\"panel-title\">" +
                        "<a role='button' data-toggle='collapse'  href='#" + key.id + "' aria-expanded='true' aria-controls='" + key.id + "'>"
                        + key.name + "/" + key.model +
                        "</a>" +
                        "</h4>" +
                        "</div>" +
                        "<div id='" + key.id + "' class=\"panel-collapse collapsing\" role=\"tabpanel\" aria-labelledby='headin" + key.id + "'>" +
                        "<div class=\"panel-body\">"
                        + "<span class='idkey' style='display: none' >" + key.keyid + "</span>" +
                        "<b>Chave:</b>" + key.key + "  - <b>QTD</b> :" + key.quantity + "/<b>Em uso: </b>" + key.in_use + "<b> - Vencimento: </b>" + vencimento
                        + " <button class='btn btn-xs remove-key' title='Dissociar o item da licença.' ><span class='glyphicon glyphicon-resize-full'></span> </button>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                });
                keys += "</div>";
            }
            if (item.HISTEMPRST.length == 0) {
                Emprestimo = "<p>Item não tem hístorico de emprestimo</p>";
            } else {
                Emprestimo = "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
                $.each(item.HISTEMPRST, function (i, his) {
                    Emprestimo += "<div class='panel panel-default'>" +
                        "<div class='panel-heading' role='tab' id='headin" + his.id + "'>" +
                        "<h4 class=\"panel-title\">" +
                        "<a role='button' data-toggle='collapse'  href='#" + his.id + "' aria-expanded='true' aria-controls='headin" + his.id + "'>"
                        + his.employed.NUMCAD + "/" + his.employed.NOMFUN +
                        "</a>" +
                        "</h4>" +
                        "</div>" +
                        "<div id='" + his.id + "' class=\"panel-collapse collapsing\" role=\"tabpanel\" aria-labelledby='headin" + his.id + "'>" +
                        "<div class=\"panel-body\">" +
                        "<p>Data emprestimo: " + DateUsTODateBr(his.data_saida, true) + "</p>" +
                        "<p>Data data devolução: " + DateUsTODateBr(his.data_saida, true) + "</p>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                });
                Emprestimo += "</div>";
            }

            if (item.ASSOC) {
                Assoc += "<button class=\"btn btn-danger dissociar-colaborador\" type=\"button\">" +
                    "<span class='glyphicon glyphicon-user'></span> Desassociar" +
                    "</button>";
            } else if (item.EMPRST == 1) {
                Assoc += "<button class=\"btn btn-default associar-colaborador\" type=\"button\" disabled>" +
                    "<span class='glyphicon glyphicon-user'></span> Associar" +
                    "</button>";
            } else {
                Assoc += "<button class=\"btn btn-default associar-colaborador\" type=\"button\">" +
                    "<span class='glyphicon glyphicon-user'></span> Associar" +
                    "</button>";
            }

            if (item.EMPRST == 0 && item.ASSOC == 0) {
                Empres = "<button class=\"btn btn-success emprestimo\" type=\"button\">" +
                    "<span class='glyphicon glyphicon-transfer'></span> Emprestimo" +
                    "</button>";
            } else if (item.ASSOC == 1) {
                Empres = "<button class=\"btn btn-success emprestimo\" type=\"button\" disabled>" +
                    "<span class='glyphicon glyphicon-transfer'></span> Emprestimo" +
                    "</button>";
            } else {
                Empres = "<button class=\"btn btn-warning devolucao\" type=\"button\">" +
                    "<span class='glyphicon glyphicon-retweet'></span> Devolução" +
                    "</button>";

            }
            var row =
                "<div class=\"panel panel-default\">" +
                "<div class=\"panel-heading cod-bem\">" +
                item.CODBEM +
                "</div>" +
                "<div class='result-emp' style='display: none'>" +
                item.CODEMP +
                "</div>" +
                "<div class=\"panel-body\">" +
                "<p><b>Descrição</b>" +
                "<p><b>Data Aquisição:</b> " + item.DATAQI + " </p>" +
                "<p><b>Item:</b> " + item.DESBEM + " </p>" +
                "<p><b>Descrição:</b> " + item.DESESP + " </p>" +
                "<p><b>Empresa:</b> " + item.NOMEMP + " </p>" +
                "<p><b>Centro Custo:</b> " + item.CODCCU + " - " + item.DESCCU + " </p>" +
                "<hr>"
                + "<p><b>Estado do bem</b>"
                + state
                + "<hr>"
                + "<p><b>Associações</b>"
                + connection
                + "<hr>"
                + "<p><b>Histórico de emprestimos</b>"
                + Emprestimo
                + "<hr>"
                + "<p><b>Licenças associadas</b>"
                + keys +
                "</div>" +
                "<div class='panel-footer'> " +
                "<div class=\"btn-group\">" +
                "<button class=\"btn btn-primary localizacoes\" type=\"button\">" +
                "<span class='glyphicon glyphicon-map-marker'></span>Localizações" +
                "</button>" +
                Assoc +
                Empres +
                "</div>" +
                "</div>" +
                "</div>";
            $('#resultOfSearch').append(row);

        });
        $("#buttonSearch").empty();
        $("#buttonSearch").append("<span class=\"glyphicon glyphicon-search\"></span> Pesquisar");

        Result = "<p>Total:" + data.total + " exibindo:" + data.per_page + "</p>";
        $('.navegation').empty();
        $('.navegation').append(Result);
        $('.navegation').fadeIn();

    } else {
        $("#buttonSearch").empty();
        $("#buttonSearch").append("<span class=\"glyphicon glyphicon-search\"></span> Pesquisar");
        var alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
            "<strong>Atenção!</strong> Nenhum item encontrado!" +
            "</div>";
        $('#resultOfSearch').append(alert);
        $('.navegation').fadeOut();
    }

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
    $('#loading').modal('show');
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
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
        },
        complete: function () {
            $('#loading').modal('hide');
        }
    }).fail(ErroConnect);
}
function Devolucao(url, data) {
    $('#loading').modal('show');
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
        }, complete: function () {
            $('#loading').modal('hide');
        }
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
                "<p><b>Obs saída: </b>" + (response[0].obs_saida == "" ? "Sem observação" : response[0].obs_saida) + "</p>" +
                "</div></div>";
            $('#devolucao-form').before(linha);
        }
    }).fail(ErroConnect);
}
function Associar(url, data) {
    $('#loading').modal('show');
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
        }, complete: function () {
            $('#loading').modal('hide');
        }
    }).fail(ErroConnect);
}
function Dissociar(url, data) {
    $('#loading').modal('show');
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
        }, complete: function () {
            $('#loading').modal('hide');
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
function GetState(url, data, callback) {
    $.ajax({
        url: url,
        type: 'get',
        data: data,
        dataType: 'json',
        success: function (response) {
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
function RemoveKey(url, data, callback) {
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
    }).fail(ErroConnect).always(callback);
    ;
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
function DateUsTODateBr(date, time) {
    var d = new Date(date);
    var hora = time == true ? d.getHours() + ":" + (d.getMinutes() <= 9? '0'+d.getMinutes():d.getMinutes()) : "";
    return d.toLocaleDateString() + " " + hora;
}

function CreateFilterPat(number) {
    CheckFilter = $('#filter').find('#filter-pat');
    if (CheckFilter.length >= 1) {
        $('#filter #filter-pat').remove();
    }
    if (number != '') {
        var row = "<div id=\"filter-pat\" class=\"alert alert-info\" role=\"alert\">" +
            "<b>Patrimônio</b>: <a href=\"#\" class=\"alert-link\">" + number + "</a>" +
            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" +
            "<span aria-hidden=\"true\">&times;</span>" +
            "</button>" +
            "<span id='filter-pat-num' style='display: none'>" + number + "</span>" +
            "</div>";
        $('#filter').append(row);
    } else {
        $('#filter #filter-pat').remove();

    }
}
function PreLoad(from, atualaryURi) {
    atualaryURi = atualaryURi.split('#');
    if (atualaryURi.length > 1) {
        CreateFilterPat(atualaryURi[1]);
        data = {
            qtd: $('#displayNumber').val(),
            pat: atualaryURi[1],
            ccu: $('#filter-costcenter-num').text(),
            employed: {
                usr: $('#filter-nameemployed-usr').text(),
                emp: $('#filter-nameemployed-emp').text(),
                tip: $('#filter-nameemployed-tip').text()
            }
        };
        $.ajax({
            url: from,
            data: data,
            type: 'get',
            dataType: 'json'
        }).done(handleData)
            .fail(ErroConnect);
    }
}
function getTermos(url) {
    item = document.getElementsByClassName('cod-bem');
    codbememp = document.getElementsByClassName('result-emp');
    numemp = document.getElementsByClassName('assoc-numemp');
    sitafa = document.getElementsByClassName('assoc-sitafa');
    tipcol = document.getElementsByClassName('assoc-tipcol');
    numcol = document.getElementsByClassName('assoc-id');
    bem = {
        coditem: item[0].innerHTML,
        codemp: codbememp[0].innerHTML
    };
    employed = {
        numemp: numemp[0].innerHTML,
        sitafa: sitafa[0].innerHTML,
        tipcol: tipcol[0].innerHTML,
        numcol: numcol[0].innerHTML
    };
    $.ajax({
        url: url,
        type: 'get',
        data: {bem, employed},
        datType: 'json',
        success: function (data) {
            $('#list-termos').empty();
            if (data.error==0) {
                $.each(data.data, function (i, item) {
                    enviado = 'Não enviado';
                    if(item.notification.length >0){
                        enviado = 'Enviado dia ' + DateUsTODateBr(item.notification[0].created_at,true);
                    }
                    devolvido = item.signtermo != 0 ? 'Recebido' : 'Não recebido';
                    buttonDevolvidoDisabled = item.signtermo != 0 ? 'disabled' : '';
                    buttonDevolvido = item.signtermo != 0 ? ' <a title="download termo assinado" target="_blank" href="/termos/download/'+item.id+'-'+item.tipotermo_id+'" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-cloud-download"></span></a>' : '';
                    row = "";
                    row += '<div class="panel panel-default" style="margin-bottom: 5px">'
                        + '<div class="panel-body">'
                        + '<span class="id-termo" style="display: none">'+item.id+'</span>'
                        + item.tipoTermo.name + ' /  ' + enviado + ',' + devolvido
                        + '<div class="row">'
                        + '<div class="col-lg-12">'
                        + '<button title="download de termo gerado" class="btn btn-default notification-termo btn-sm" '+buttonDevolvidoDisabled+' ><span class="glyphicon glyphicon-bullhorn"> <span class="badge">'+item.notificationQtd+'</span></span></button> '
                        + '<button title="upload de termo assinado" class="btn btn-default upload-termo btn-sm"><span class="glyphicon glyphicon-cloud-upload"></span></button> '
                        + '<a target="_blank" href="/termos/download/'+item.id+'" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-cloud-download"></span></a> '
                        + buttonDevolvido
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '</div>';
                    $('#list-termos').append(row);
                });
            }else{
                $('#list-termos').append("<p>"+data.msg+"</p>");
            }
        }
    });
    //console.log(bem);
    //console.log(employed);
}


function notificationTermo(termo,url) {
    $.ajax({
        url: url+'/'+termo,
        type: 'get',
        datType: 'json',
        success: function (data) {
           if(data.error){
               swal(
                   'Oops...',
                   data.msg
                   ,
                   'error'
               )
           }else{
               swal(
                   'Good job!',
                   data.msg,
                   'success'
               );
               getTermos('api/ativos/termos');
           }

        }
    });
}

angular.module('ativos', ['ngMessages']);
angular.module('ativos').controller('termo', function ($http, $scope) {
    $scope.gerarTermo = function (termo) {

        item = angular.element(document.getElementsByClassName('cod-bem'));
        codbememp = angular.element(document.getElementsByClassName('result-emp'));
        numemp = angular.element(document.getElementsByClassName('assoc-numemp'));
        sitafa = angular.element(document.getElementsByClassName('assoc-sitafa'));
        tipcol = angular.element(document.getElementsByClassName('assoc-tipcol'));
        numcol = angular.element(document.getElementsByClassName('assoc-id'));
        bem = {
            coditem: item[0].innerHTML,
            codemp: codbememp[0].innerHTML
        };
        employed = {
            numemp: numemp[0].innerHTML,
            sitafa: sitafa[0].innerHTML,
            tipcol: tipcol[0].innerHTML,
            numcol: numcol[0].innerHTML
        };
        if (!$scope.novoTermo.tipo.$error.required) {
            $http.post('ativos/termo/novo', [termo, bem, employed]).then(function successCallback(response) {
                if(response.data.error){
                    angular.element(
                        swal(
                            'Oops...',
                            response.data.msg
                            ,
                            'error'
                        )
                    );
                }else{
                    angular.element(
                        swal(
                            'Good job!',
                            response.data.msg,
                            'success'
                        )
                    );
                    getTermos('api/ativos/termos');

                }
            });
        }
    }
});
angular.module('ativos').controller('dataSearch', function ($http, $scope) {

});
//# sourceMappingURL=ativos.js.map

//# sourceMappingURL=ativos.js.map
