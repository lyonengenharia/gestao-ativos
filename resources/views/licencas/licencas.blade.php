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
    </style>
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top: 5px">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="get" action="{{url('licencas')}}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Empresa</label>
                                <select type="text" name="empresa" id="empresa" class="form-control">
                                    <option value="0"></option>
                                    @foreach($empresas as $empresa)
                                        <option value="{{$empresa->id}}">{{$empresa->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Produto</label>
                                <select type="text" name="produto" id="produto" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Chave</label>
                                <input type="text" name="chave" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Data vencimento</label>
                                <input type="text" name="datavencimento" class="form-control data-picker">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <nav class="navbar navbar-inverse">
                                <div class="container-fluid">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav">
                                            <li>
                                                <button type="submit" class="btn btn-default navbar-btn">Pesquisar
                                                </button>
                                            </li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="{{url('licencas/empresa')}}">Nova Empresa</a></li>
                                            <li><a href="{{url('licencas/produto')}}">Novo Produto</a></li>
                                            <li><a href="{{url('licencas/licenca')}}">Nova Licença</a></li>
                                        </ul>
                                    </div><!-- /.navbar-collapse -->
                                </div><!-- /.container-fluid -->
                            </nav>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top: -15px">
            @if (session('status'))
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif
            <table class="table table-bordered table-hover">
                <thead>
                <th>Produto</th>
                <th>Vencimento</th>
                <th>Uso x Qtd</th>
                <th>Ações</th>
                </thead>
                <tbody>
                @foreach($licencas as $licenca)
                    <?php
                    $data = null;
                    if (empty($licenca->maturity_date)) {
                        $data = "Vitalício";
                    } else {
                        $data = new \Carbon\Carbon($licenca->maturity_date);
                        $data = $data->format('d/m/Y');
                    }
                    ?>
                    <tr {{$licenca->in_use > $licenca->quantity ?"class=danger":""}}>
                        <td class="Model-table">{{$licenca->name}} - {{$licenca->model}}</td>
                        <td>{{$data}}</td>
                        <td>{{$licenca->in_use}} de {{$licenca->quantity}}</td>
                        <td>
                            <a href="{{url('/licencas/licenca/'.$licenca->keyid)}}"
                               class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> </a>
                            <button class="btn btn-default btn-xs associarkeymodal">
                                <span class="glyphicon glyphicon-resize-small"></span>
                            </button>
                            <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse"
                                    data-target="#{{$licenca->keyid}}"
                                    aria-expanded="false" aria-controls="collapseExample">
                                <span class="glyphicon glyphicon-list"></span>
                            </button>
                            <span style="display: none" class="licencakeyid">{{$licenca->keyid}}</span>
                        </td>
                    <tr class="collapse" id="{{$licenca->keyid}}">
                        <td colspan="5">
                            <p class="espaco-entrelinhas-xs">Chave: {{$licenca->key}}</p>
                            <p class="espaco-entrelinhas-xs">Data Criação registro: {{$licenca->created_at}}</p>
                            <p class="espaco-entrelinhas-xs">Última Atualização Criação
                                registro: {{$licenca->updated_at}}</p>
                            <p class="espaco-entrelinhas-xs">Descrição:{{$licenca->description}}</p>
                        </td>
                    </tr>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p>Total de Registros {{$licencas->total()}}, exibindo {{$licencas->count()}}</p>{{$licencas->links()}}
        </div>
    </div>

    {{--Modal de associação de licença--}}
    <div class="modal fade" tabindex="-1" role="dialog" id="associarkey">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Atribuir licença</h4>
                    <span id="Title-Empresa-Produto"></span>
                    <p>Chave - <span id="Title-Produto-Key"></span></p>

                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">Associados</div>
                        <div class="panel-body" id="LicencasAssociadas">
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline" id="search-pat">
                                <div class="form-group">
                                    <label>Patrimônio</label>
                                    <input type="text" id="patrimonio" class="form-control" required>

                                    <button type="submit" class="btn btn-info" id="buttonSearch">Pesquisar</button>
                                </div>
                            </form>

                        </div>

                    </div>
                    <div id="resultOfSearch">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        <div id="idkey" style="display: none"></div>
    </div><!-- /.modal -->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/licencas.js')}}"></script>
    <script>


        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $('#search-pat').submit(function (e) {
                if ($('#resultOfSearch').hasClass('col-md-4')) {
                    $('#resultOfSearch').toggleClass('col-md-12');
                    $('#resultOfSearch').toggleClass('col-md-4');


                    $('#historyItem').toggleClass('col-md-4');
                    $('#historyItem').toggleClass('display-localizaoes');

                    $('#historyFinancial').toggleClass('col-md-4');
                    $('#historyFinancial').toggleClass('display-localizaoes');
                }
                e.preventDefault();
                $("#buttonSearch").empty();
                $("#buttonSearch").append("Carregando <img src=\"{{asset("img/load/microload.gif")}}\">");
                $.ajax({
                    url: '{{url('ativos/search')}}',
                    data: {pat: $('#patrimonio').val()},
                    type: 'get',
                    dataType: 'json'
                }).done(handleData);
            });
            $(document).on('click', '.selecao', function () {
                var botaoSelecao = $(this);
                var selecao = $(this).parent().parent();
                selecao.toggleClass('panel-default');
                selecao.toggleClass('panel-warning');
                if (selecao.hasClass('panel-warning')) {
                    var pat = selecao.find('.panel-heading').text();
                    var emp = selecao.find('.codemp').text();
                    botaoSelecao.text('Carregando...')
                    $.ajax({
                        url: '{{url('/api/licencas/associadas')}}/' + pat + "/" + emp,
                        type: 'get',
                        dataType: 'json',
                        success: function (data) {
                            if (data.length > 0) {
                                $.each(data, function (i, item) {
                                    //console.log(item);
                                    var rodape = "<div class='panel-footer'>" +
                                            "<p>Empresa/Produto " + item.name + "/ " + item.model + " </p>" +
                                            "<p style='line-height: 0.5;'><b>Chave:</b> " + item.key + " </p>" +
                                            "</div> "
                                    selecao.append(rodape);
                                });
                                selecao.append("<div class='panel-footer'><button class='btn btn-success btn-xs associarchave'>Associar chave</button></div>");

                            } else {
                                var rodape = "<div class='panel-footer'>" +
                                        "<p>Nenhuma chave associada</p>" +
                                        "<button class='btn btn-success btn-xs associarchave'>Associar chave</button>" +
                                        "</div> "
                                selecao.append(rodape);
                            }

                        }, complete: function () {
                            botaoSelecao.text('Desmarcar')
                        }
                    });
                } else {
                    botaoSelecao.text('Marcar')
                    selecao.find('.panel-footer').remove();
                }
            });
            $(document).on('click', '.associarchave', function () {
                var botao = $(this);
                botao.text("Carregando...");
                var selecao = $(this).parent().parent();
                var pat = selecao.find('.panel-heading').text();
                var emp = selecao.find('.codemp').text();
                var key = $('#associarkey #idkey').text();
                $.ajax({
                    url: '{{url('/licencas/associar')}}',
                    type: 'post',
                    data: {pat: pat, emp: emp, key: key},
                    dataType: 'json',
                    success: function (data) {
                        trataRetorno(data, pat, emp, key, '{{url('/licencas/associar')}}');
                    }, complete: function () {
                        botao.text('Desmarcar')
                    }
                });
            });
            $(document).on('click', '.associarkeymodal', function () {
                $(".div-load").toggleClass('div-load-hidden');
                var idkey = $(this).parent().find('.licencakeyid').text();
                var table_keys = $(this).parent().parent();
                var heading_modal_associarkey = $('#Title-Empresa-Produto');
                var heading_modal_key = $('#Title-Produto-Key');
                $('#associarkey #idkey').text(idkey);
                $('#resultOfSearch').empty();
                heading_modal_associarkey.empty();
                heading_modal_key.empty();
                empresa = table_keys.find('.Empresa-table').text();
                produto = table_keys.find('.Model-table').text();
                key = table_keys.find('.Key-table').text();
                heading_modal_associarkey.text(empresa + ' - ' + produto);
                heading_modal_key.text(key);
                $('#patrimonio').val("");
                $.ajax({
                    url: '{{url('/api/licencas/associadas')}}/' + idkey,
                    type: 'get',
                    dataType: 'json',
                }).done(function (data) {
                    trataModalLicenca(data, function () {
                        $(".div-load").toggleClass('div-load-hidden');
                        $('#associarkey').modal('show');
                    });

                });

            });
            $(document).on('click', '.remover-associacao', function () {
                if (confirm("Deseja realmente remover a associação ?")) {
                    key = $('#associarkey #idkey').text();
                    pat = $(this).parent().find('.patrimonio').text();
                    emp = $(this).parent().find('.emppatmodal').text();
                    $.ajax({
                        url: '{{url('/licencas/produto/delete')}}',
                        data: {key: key, pat: pat, emp: emp},
                        type: 'delete',
                        dataType: 'json',
                        success: function (data) {
                            if (data.error) {
                                alert(data.msg);
                            } else {
                                alert(data.msg);
                                location.reload();
                            }
                        }
                    }).fail(ErroConnect);
                }
            });
            $('#empresa').change(function () {
                $('#produto').empty();
                if ($(this).val() !== '0') {
                    $.ajax({
                        url: '{{url('/api/produtos')}}' + '/' + $(this).val(),
                        type: 'get',
                        dataType: 'json'
                    }).done(Products);
                }
            });
            $('.data-picker').datepicker({
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
        });
    </script>
@endsection