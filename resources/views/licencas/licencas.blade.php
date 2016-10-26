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
            <a href="{{url('licencas/empresa')}}" class="btn btn-default">Nova Empresa</a>
            <a href="{{url('licencas/produto')}}" class="btn btn-default">Novo Produto</a>
            <a href="{{url('licencas/licenca')}}" class="btn btn-default">Nova Licença</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top: 15px">
            @if (session('status'))
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif
            <table class="table table-bordered table-hover">
                <thead>
                <th>Empresa</th>
                <th>Produto</th>
                <th>Chave</th>
                <th>Quantidade</th>
                <th>Quant. Uso</th>
                <th>Ações</th>
                </thead>
                <tbody>
                @foreach($licencas as $licenca)
                    <tr {{$licenca->in_use > $licenca->quantity ?"class=danger":""}}>
                        <td class="Empresa-table">{{$licenca->name}}</td>
                        <td class="Model-table">{{$licenca->model}}</td>
                        <td class="Key-table">{{$licenca->key}}</td>
                        <td class="Quantity-table">{{$licenca->quantity}}</td>
                        <td class="InUse-table">{{$licenca->in_use}}</td>
                        <td>
                            <a href="{{url('/licencas/licenca/'.$licenca->keyid)}}"
                               class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> </a>
                            <button class="btn btn-default btn-xs associarkeymodal">
                                <span class="glyphicon glyphicon-resize-small"></span>
                            </button>
                            <span style="display: none" class="licencakeyid">{{$licenca->keyid}}</span>
                        </td>
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
                            <form class="form-inline">
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
        function trataRetorno(data, pat, emp, key) {
            //var pat;
            //var emp;
            //var key;
            if (data.erro == 2) {
                if (confirm(data.msg)) {
                    $.ajax({
                        url: '{{url('/licencas/associar')}}',
                        type: 'post',
                        data: {pat: pat, emp: emp, key: key, conf: 1},
                        dataType: 'json'
                    }).done(trataRetorno);
                }
            } else {
                alert(data.msg);
                location.reload();
            }
        }

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $('form').submit(function (e) {
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
                        trataRetorno(data, pat, emp, key);
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
                    $.ajax({
                        url: '{{url('/licencas/produto/delete')}}',
                        data: {key: key, pat: pat},
                        type: 'delete',
                        success: function (data) {
                            console.log(data);
                        }
                    });
                }
            });
        });
    </script>
@endsection