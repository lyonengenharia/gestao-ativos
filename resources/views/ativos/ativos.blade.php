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
                            <button type="submit" class="btn btn-info" id="buttonSearch"><span
                                        class="glyphicon glyphicon-search"></span> Pesquisar
                            </button>
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
        {{--Emprestimo--}}
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
                                <input name="nomemp" id="nomemp" class="form-control nomemp" autocomplete="off"
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
                                <input type="text" class="form-control campo-data" name="dataempdev" id="dataempdev"
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
                                <input type="text" class="form-control campo-data" name="datadev" id="datadev"
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
        {{--Associação--}}
        <div class="associacao-option display-emprestismo col-md-8">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <b>Associação colaborador</b>
                </div>
                <div class="panel-body" id="historyFinancialList">
                    <form id="associacao">
                        <div class="col-md-3">
                            <label for="tipcol">Tipo:</label>
                            <select class="form-control" id="tipcolassoc">
                                <option value="1">Empregado</option>
                                <option value="2">Terceiro</option>
                                <option value="3">Parceiro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="numcad">Empresa:</label>
                            <select class="form-control" id="empassoc">
                                @foreach($empresas as $empresa)
                                    <option value="{{$empresa->numemp}}">{{$empresa->apeemp}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numemp">Colaborador(a):</label>
                                <input name="nomeassoc" id="nomeassoc" class="form-control nomemp"
                                       autocomplete="off"
                                       placeholder="Digite um nome para pesquisar"/>

                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: -10px;margin-bottom: 10px">
                            <div id="logassoc">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observação</label>
                                <textarea class="form-control" id="obs" cols="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dataassoc">Data associação</label>
                                <input type="text" class="form-control campo-data" name="dataassoc" id="dataassoc"
                                       autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gerar Termo</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="gerarTermo"> Sim
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">Associar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="resultOfSearch">
        </div>
    </div>
    {{--Modal desassociar--}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-desassociar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Desassociar item</h4>
                </div>
                <form id="form-desassociar">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control" cols="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Data desassociação</label>
                            <input type="text" class="form-control campo-data"
                                   value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-desassociar-salvar">Salvar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{--Modal Estado--}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-status">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Estado/h4>
                </div>
                <form id="form-state">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" name="status-select" id="status-select">
                                @foreach($states as $state)
                                    <option value="{{$state->id}}">{{$state->state}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control" cols="5"></textarea>
                        </div>
                        <p>Ultima atualização:</p><span id="upddated_at"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-desassociar-salvar">Salvar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" role="dialog" id="dissoc-key">
        <div class="modal-dialog" role="document">
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4>Atenção</h4>
                <p>Deseja realmente dissociar o item da licença ?</p>
                <p>
                    <button type="button" class="btn btn-danger" id="btn-dissoc-key">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não agora</button>
                </p>
                <span id="bem-dissoc-key" style="display: none"></span>
                <span id="emp-dissoc-key" style="display: none"></span>
                <span id="key-dissoc-key" style="display: none"></span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var URLUPDATE = '{{url('ativos/search')}}';
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $('.campo-data').datepicker({
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
                    $('#log').html("<b><span style='color: #761c19;'>Selecionado:</span></b> " + ui.item.value + " - <b>Matrícula:</b> <span id='numcad'>" + ui.item.id + "</span>" +
                            " - <b>Situação:</b>" + ui.item.DESSIT +
                            "<span id='numemp' style='display: none'>" + ui.item.NUMEMP + "</span>" +
                            "<span id='tipcolpesquisa' style='display: none'>" + ui.item.TIPCOL + "</span>" +
                            "<span id='SITAFA' style='display: none'>" + ui.item.SITAFA + "</span>");
                }
            });
            $("#nomeassoc").autocomplete({
                source: function (request, response) {
                    var nome = $("#nomeassoc").val();
                    var empresa = $('#empassoc :selected').val();
                    var tipcol = $('#tipcolassoc :selected').val();
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
                    $("#nomeassoc").val(ui.item.NOMFUN);
                    return false;
                },
                select: function (event, ui) {
                    $('#logassoc').html("<b><span style='color: #761c19;'>Selecionado:</span></b> " + ui.item.value + " - <b>Matrícula:</b> <span id='numcad'>" + ui.item.id + "</span>" +
                            " - <b>Situação:</b>" + ui.item.DESSIT +
                            "<span id='numemp' style='display: none'>" + ui.item.NUMEMP + "</span>" +
                            "<span id='tipcolpesquisa' style='display: none'>" + ui.item.TIPCOL + "</span>" +
                            "<span id='SITAFA' style='display: none'>" + ui.item.SITAFA + "</span>");
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
                if ($('#SITAFA').text() == "7") {
                    alert('Favor verificar a situação do colaborador!');
                    erro++;
                }
                if (numcad == '') {
                    alert('Favor pesquisar um colaborador!');
                    erro++;
                }
                if (dataempdev == '') {
                    alert('Favor preencher o campo de data!');
                    erro++;
                }

                codbem = $('#resultOfSearch .panel .cod-bem').text();
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
                $('.associacao-option').addClass('display-emprestismo');
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
                }).done(handleData).fail(ErroConnect);

            });
            $(document).on('click', '.localizacoes', function () {
                $(".div-load").toggleClass('div-load-hidden');

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
                }).fail(ErroConnect);
                ;
            });
            $(document).on('click', '.emprestimo', function () {
                $('#emprestimo')[0].reset();
                $('#log').empty();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('#historyItem').addClass('display-localizaoes');
                $('.associacao-option').addClass('display-emprestismo');
                $('#historyFinancial').addClass('display-localizaoes');
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');
                }
                $('.emprestimo-option').removeClass('display-emprestismo');
                var panel = $(this).parent().parent().parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
            });
            $(document).on('click', '.devolucao', function () {
                $('#devolucao-form')[0].reset();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('.associacao-option').addClass('display-emprestismo');
                $('#historyItem').addClass('display-localizaoes');
                $('#historyFinancial').addClass('display-localizaoes');
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');
                }
                $(".devolucao-option").removeClass('display-emprestismo');
                var panel = $(this).parent().parent().parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
                codbem = $('#resultOfSearch .panel .cod-bem').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                DevolucaoDados('{{url('api/devolucao')}}', {codbem: codbem, codbememp: codbememp});
            });
            $('#devolucao-form').submit(function (e) {
                e.preventDefault();
                codbem = $('#resultOfSearch .panel .cod-bem').text();
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
            $(document).on('click', '.associar-colaborador', function () {
                $('#associacao')[0].reset();
                $('.emprestimo-option').addClass('display-emprestismo');
                $('#historyItem').addClass('display-localizaoes');
                $(".devolucao-option").addClass('display-emprestismo');
                $('#historyFinancial').addClass('display-localizaoes');
                $('.associacao-option').removeClass('display-emprestismo');
                var panel = $(this).parent().parent().parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').removeClass('col-md-12');
                    $('#resultOfSearch').addClass('col-md-4');
                }
                $('#resultOfSearch').append(panel);
            });
            $('#associacao').submit(function (e) {
                e.preventDefault();
                erro = 0;
                numcad = $('#logassoc #numcad').text();
                numemp = $('#logassoc #numemp').text();
                tipcol = $('#logassoc #tipcolpesquisa').text();
                obsemp = $('#obs').val();
                dataempdev = $('#dataassoc').val();
                gerarTermo = $('#gerarTermo').is(':checked');
                if ($('#SITAFA').text() == "7") {
                    alert('Favor verificar a situação do colaborador!');
                    erro++;
                }
                if (numcad == '') {
                    alert('Favor pesquisar um colaborador!');
                    erro++;
                }
                if (dataempdev == '') {
                    alert('Favor preencher o campo de data!');
                    erro++;
                }

                codbem = $('#resultOfSearch .panel .cod-bem').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                if (erro == 0) {
                    Associar('{{url('/ativos/associar')}}', {
                        numcad: numcad,
                        numemp: numemp,
                        tipcol: tipcol,
                        dataempdev: dataempdev,
                        codbem: codbem,
                        codbememp: codbememp,
                        obsemp: obsemp,
                        gerarTermo: gerarTermo
                    });
                }
            });
            $(document).on('click', '.dissociar-colaborador', function () {
                var panel = $(this).parent().parent().parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
                codbem = $('#resultOfSearch .panel .cod-bem').text();
                $('#modal-desassociar .modal-title').text('Desassociar ' + codbem);
                $('#modal-desassociar textarea').val(" ");
                $('#modal-desassociar').modal('show');
            });
            $('#form-desassociar').submit(function (e) {
                e.preventDefault();
                codbem = $('#resultOfSearch .panel .cod-bem').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                data = $('#modal-desassociar .campo-data').val();
                obs = $('#modal-desassociar textarea').val();
                Dissociar('{{url('ativos/dissociar')}}',
                        {
                            codbem: codbem,
                            codbememp: codbememp,
                            data: data,
                            obs: obs
                        });
            });
            $(document).on('click', '.status-ben', function () {
                $("#form-state")[0].reset();
                var panel = $(this).parent().parent();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
                codbem = $('#resultOfSearch .panel .cod-bem').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                $('#modal-status .modal-title').text('Status ' + codbem);
                GetState('{{url('ativos/state/')}}', {codbem: codbem, codbememp: codbememp});
                $('#modal-status').modal('show');


            });
            $("#form-state").submit(function (e) {
                e.preventDefault();
                codbem = $('#resultOfSearch .panel .cod-bem').text();
                codbememp = $('#resultOfSearch .panel .result-emp').text();
                status = $("#form-state select :selected").val();
                obs = $("#form-state textarea").val();
                data = {
                    codbem: codbem,
                    codbememp: codbememp,
                    status: status,
                    obs: obs
                };
                InsertState('{{url('ativos/state/')}}', data, $('#modal-status').modal('hide'));

            });
            $(document).on('click', '.remove-key', function () {
                var panel = $(this).parent().parent().parent().parent().parent().parent();
                var key = $(this).parent().find('.idkey').text();
                $('#resultOfSearch').empty();
                panel.removeClass('panel-default');
                panel.addClass('panel-warning');
                $('#resultOfSearch').append(panel);
                $("#bem-dissoc-key").text($('#resultOfSearch .panel .cod-bem').text());
                $("#emp-dissoc-key").text($('#resultOfSearch .panel .result-emp').text());
                $("#key-dissoc-key").text(key);
                $('#dissoc-key').modal('show');
            });
            $('#btn-dissoc-key').click(function () {
                pat = $("#bem-dissoc-key").text();
                emp= $("#emp-dissoc-key").text();
                key = $("#key-dissoc-key").text();
                RemoveKey('{{url('licencas/produto/delete')}}',{pat:pat,emp:emp,key:key},$('#dissoc-key').modal('hide'));
            });
        });
    </script>
    <script src="{{asset('js/ativos.js')}}"></script>
@endsection
