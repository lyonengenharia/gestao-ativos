@extends('layouts.painel')

@section('content')
    <style>
        .load-button {
            background-image: url('{{asset("img/load/microload.gif")}}');
            background-repeat: repeat-y;
        }

        .display-localizaoes {
            display: none;
        }

        .display-emprestismo {
            display: none;
        }
    </style>

    <script src="{{asset('js/jquery.js')}}"></script>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" id="search">
                <div class="panel-heading">Busca</div>
                <div class="panel-body">
                    <form id="search-iten">
                        <div class="form-group">
                            <label>Patrimônio</label>
                            <input type="text" id="patrimonio" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-lg" id="buttonSearch">Pesquisar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="historyItem" class="display-localizaoes">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <b>Localizações</b> <span id="item">Iten</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="panel-body" id="historicoLocalizacoes">

                </div>
            </div>
        </div>
        <div id="historyFinancial" class="display-localizaoes">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <b>Movimentação Fiscal</b> <span id="item">Iten</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="panel-body" id="historyFinancialList">

                </div>
            </div>
        </div>
        <div class="emprestimo-option display-emprestismo col-md-8">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <b>Empréstimo</b>
                </div>
                <div class="panel-body" id="historyFinancialList">
                    <form id="emprestimo">
                        <div class="col-md-3">
                            <label for="tipcol">Tipo:</label>
                            <select class="form-control" id="tipcol">
                                <option value="1">Empregado</option>
                                <option value="2">Terceiro</option>
                                <option value="3">Parceiro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="numcad">Empresa:</label>
                            <select class="form-control" id="emp">
                                @foreach($empresas as $empresa)
                                    <option value="{{$empresa->numemp}}">{{$empresa->apeemp}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numemp">Colaborador(a):</label>
                                <input name="nomemp" id="nomemp" class="form-control" autocomplete="off"
                                       placeholder="Digite um nome para pesquisar"/>

                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: -10px;margin-bottom: 10px">
                            <div id="log">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observação Emprestimo</label>
                                <textarea class="form-control" id="obsemp" cols="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dataempdev">Data empréstimo</label>
                                <input type="text" class="form-control" name="dataempdev" id="dataempdev"
                                       autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">Emprestar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="devolucao-option display-emprestismo col-md-8">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <b>Devolução</b>
                </div>
                <div class="panel-body" id="historyFinancialList">
                    <form id="devolucao-form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observação Devolução</label>
                                <textarea class="form-control" id="obsdev" cols="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dataempdev">Data Devolução</label>
                                <input type="text" class="form-control" name="datadev" id="datadev"
                                       autocomplete="off" required>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">Devolver</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="resultOfSearch">
        </div>

    </div>


    <script>
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
                        "<button class=\"btn btn-primary localizacoes\" type=\"button\" data-toggle=\"collapse\" " +
                        "data-target=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">" +
                        "Localizações" +
                        "</button>";
                if (item.EMPRST == 0) {
                    row += "<button class=\"btn btn-success emprestimo\" type=\"button\">" +
                            "Emprestimo" +
                            "</button>" +
                            "</div>" +
                            "</div>";
                } else {
                    row += "<button class=\"btn btn-warning devolucao\" type=\"button\">" +
                            "Devolução" +
                            "</button>" +
                            "</div>" +
                            "</div>";
                }
                $('#resultOfSearch').append(row);

            });
            $("#buttonSearch").empty();
            $("#buttonSearch").append("Pesquisar");
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
                        "<p><b>CODTNS:</b> " + item.CODTNS + " </p>" +
                        "</div>" +
                        "</div>";
                if (item.CODTNS == 90804) {
                    var alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" +
                            "<strong>Atenção!</strong> Esse item foi vendido!." +
                            "</div>";
                    $('#search').after(alert);
                }
                $('#historyFinancialList').append(row);
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
            })
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
            });


        }
        function DevolucaoDados(url,data) {
            $.ajax({
                url: url+"/"+data.codbem+"/"+data.codbememp,
                type: 'get',
                data: data,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    var linha = "<div class='panel panel-default'><div class='panel-body'>" +
                         "<p>Data empréstimo:"+response[0].data_saida+"</p>"+
                    "</div></div>";
                    $('#devolucao-form').before(linha);
                }
            });
        }
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $('#dataempdev').datepicker({
                closeText: 'Fechar',
                prevText: '&lt;Anterior',
                nextText: 'Próximo&gt;',
                currentText: 'Hoje',
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
                    'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            });

            $('#datadev').datepicker({
                closeText: 'Fechar',
                prevText: '&lt;Anterior',
                nextText: 'Próximo&gt;',
                currentText: 'Hoje',
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
                    'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            });
            $("#nomemp").autocomplete({
                source: function (request, response) {
                    var nome = $("#nomemp").val();
                    var empresa = $('#emp :selected').val();
                    var tipcol = $('#tipcol :selected').val();
                    $.ajax({
                        url: '{{url('api/colaboradores/')}}' + '/' + nome + '/' + tipcol + '/' + empresa,
                        dataType: "json",
                        type: 'get',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                focus: function (event, ui) {
                    $("#nomemp").val(ui.item.NOMFUN);
                    return false;
                },
                select: function (event, ui) {
                    $('#log').html("<b><span style='color: #761c19;'>Selecionado:</span></b> " + ui.item.value + " - <b>Matrícula:</b> <span id='numcad'>"
                            + ui.item.id + "</span><span id='numemp' style='display: none'>" + ui.item.NUMEMP
                            + "</span><span id='tipcolpesquisa' style='display: none'>"
                            + ui.item.TIPCOL + "</span>");
                }
            });
            $('#emprestimo').submit(function (e) {
                e.preventDefault();
                erro = 0;
                numcad = $('#numcad').text();
                numemp = $('#numemp').text();
                tipcol = $('#tipcolpesquisa').text();
                obsemp = $('#obsemp').val();
                dataempdev = $('#dataempdev').val();

                if (numcad == '') {
                    alert('Favor pesquisar um colaborador!');
                    erro++;
                }
                if (dataempdev == '') {
                    alert('Favor preencher o campo de data!');
                    erro++;
                }

                codbem = $('#resultOfSearch .panel .panel-heading').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                if (erro == 0) {
                    Emprestimo('{{url('ativos/emprestimo/')}}', {
                        numcad: numcad,
                        numemp: numemp,
                        tipcol: tipcol,
                        dataempdev: dataempdev,
                        codbem: codbem,
                        codbememp: codbememp,
                        obsemp: obsemp
                    });
                }


            });
            $('#search-iten').submit(function (e) {
                e.preventDefault();
                $('.search').remove();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('#historyItem').addClass('display-localizaoes');
                $(".devolucao-option").addClass('display-emprestismo');
                $('#historyFinancial').addClass('display-localizaoes');
                if ($('#resultOfSearch').hasClass('col-md-4')) {
                    $('#resultOfSearch').toggleClass('col-md-12');
                    $('#resultOfSearch').removeClass('col-md-4');


                }

                if (!$('.emprestimo-option').hasClass('display-emprestismo')) {
                    $('.emprestimo-option').addClass('display-emprestismo')
                }
                $("#buttonSearch").empty();
                $("#buttonSearch").append("Carregando <img src=\"{{asset("img/load/microload.gif")}}\">");
                $.ajax({
                    url: '{{url('ativos/search')}}',
                    data: {pat: $('#patrimonio').val()},
                    type: 'get',
                    dataType: 'json'
                }).done(handleData);
            });
            $(document).on('click', '.localizacoes', function () {
                $(".div-load").toggleClass('div-load-hidden');
                var item = $(this).parent().parent().find('.panel-heading').text();
                var panel = $(this).parent().parent();
                $(".devolucao-option").addClass('display-emprestismo');
                if (!$('.emprestimo-option').hasClass('display-emprestismo')) {
                    $('.emprestimo-option').addClass('display-emprestismo')
                }
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');

                    $('#historyItem').addClass('col-md-4');
                    $('#historyItem').removeClass('display-localizaoes');

                    $('#historyFinancial').addClass('col-md-4');
                    $('#historyFinancial').removeClass('display-localizaoes');
                    $("#item").text(item);
                } else {
                    $('#historyItem').addClass('col-md-4');
                    $('#historyItem').removeClass('display-localizaoes');

                    $('#historyFinancial').addClass('col-md-4');
                    $('#historyFinancial').removeClass('display-localizaoes');
                    $("#item").text(item);
                }
                $.ajax({
                    url: '{{url('ativos/locations')}}',
                    data: {pat: item},
                    type: 'get',
                    dataType: 'json'
                }).done(function (data) {
                    historyLocations(data);
                    $('#resultOfSearch').empty();
                    panel.removeClass('panel-default');
                    panel.addClass('panel-warning');
                    $('#resultOfSearch').append(panel);
                    $(".div-load").toggleClass('div-load-hidden');
                });
            });
            $(document).on('click', '.emprestimo', function () {

                $('#emprestimo')[0].reset();
                $('#log').empty();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('#historyItem').addClass('display-localizaoes');
                $('#historyFinancial').addClass('display-localizaoes');
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');
                }
                $('.emprestimo-option').removeClass('display-emprestismo');
                var panel = $(this).parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
            });
            $(document).on('click', '.devolucao', function () {
                $('#devolucao-form')[0].reset();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('#historyItem').addClass('display-localizaoes');
                $('#historyFinancial').addClass('display-localizaoes');
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');
                }
                $(".devolucao-option").removeClass('display-emprestismo');
                var panel = $(this).parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
                codbem = $('#resultOfSearch .panel .panel-heading').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                DevolucaoDados('{{url('api/devolucao')}}',{codbem:codbem,codbememp:codbememp});
            });
            $('#devolucao-form').submit(function (e) {
                e.preventDefault();
                codbem = $('#resultOfSearch .panel .panel-heading').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                Devolucao('{{url('/ativos/devolucao')}}',
                        {
                            data: $('#datadev').val(),
                            obs: $('#obsdev').val(),
                            codbem: codbem,
                            codbememp: codbememp
                        }
                );
            });
        });
    </script>
@endsection
