@extends('layouts.painel')

@section('content')
    <div class="container">
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
                        <tr>
                            <td>{{$licenca->name}}</td>
                            <td>{{$licenca->model}}</td>
                            <td>{{$licenca->key}}</td>
                            <td>{{$licenca->quantity}}</td>
                            <td>{{$licenca->in_use}}</td>
                            <td>
                                <a href="{{url('/licencas/licenca/'.$licenca->keyid)}}"
                                   class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> </a>
                                <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#associarkey">
                                    <span class="glyphicon glyphicon-resize-small"></span></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p>Total de Registros {{$licencas->total()}}, exibindo {{$licencas->count()}}</p>{{$licencas->links()}}
            </div>
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
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label>Patrimônio</label>
                                    <input type="text" id="patrimonio" class="form-control" required>
                                    <button type="submit" class="btn btn-info" id="buttonSearch">Pesquisar</button>
                                </div>
                                <div class="form-group">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        function handleData(data, textStatus, jqXHR) {
            //console.log(data);
            $('#resultOfSearch').empty();
            $.each(data, function (i, item) {
                //console.log(item);
                var row = "<div class=\"panel panel-default\">" +
                        "<div class=\"panel-heading\">" +
                        item.CODBEM +
                        "</div>" +
                        "<div class=\"panel-body\">" +
                        "<p><b>Data Aquisição:</b> " + item.DATAQI + " </p>" +
                        "<p><b>Descrição:</b> " + item.DESBEM + " </p>" +
                        "</div>" +
                        "<div class='panel-footer'> " +
                        "<button class=\"btn btn-primary localizacoes\" type=\"button\" data-toggle=\"collapse\" " +
                        "data-target=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">" +
                        "Localizações" +
                        "</button>" +
                        "</div>" +
                        "</div>";
                $('#resultOfSearch').append(row);

            });
            $("#buttonSearch").empty();
            $("#buttonSearch").append("Pesquisar");
        }
        $(document).ready(function () {

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

        });
    </script>
@endsection