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

    <script src="{{asset('js/jquery.js')}}"></script>
    <div class="row">
        <div class="col-md-12" >
            <div class="panel panel-default" id="search">
                <div class="panel-heading">Busca</div>
                <div class="panel-body">
                    <form>
                        <div class="form-group">
                            <label>Patrimônio</label>
                            <input type="text" id="patrimonio" class="form-control" required>
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
        <div class="col-md-12" id="resultOfSearch">
        </div>


    </div>
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
                        "<p><b>Item:</b> " + item.DESBEM + " </p>" +
                        "<p><b>Descrição:</b> " + item.DESESP + " </p>" +
                        "<p><b>Empresa:</b> " + item.NOMEMP + " </p>" +
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
                if(item.CODTNS == 90804 ){
                    var alert = "<div class=\"alert alert-danger alert-dismissible search\" role=\"alert\">"+
                                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>"+
                                    "<strong>Atenção!</strong> Esse item foi vendido!."+
                                "</div>";
                    $('#search').after(alert);
                }
                $('#historyFinancialList').append(row);
            });


        }


        $(document).ready(function () {

            $('form').submit(function (e) {
                $('.search').remove();
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
            $(document).on('click', '.localizacoes', function () {
                $(".div-load").toggleClass('div-load-hidden');
                var item = $(this).parent().parent().find('.panel-heading').text();
                var panel = $(this).parent().parent();
                if ($('#resultOfSearch').hasClass('col-md-12')) {
                    $('#resultOfSearch').toggleClass('col-md-12');
                    $('#resultOfSearch').toggleClass('col-md-4');

                    $('#historyItem').toggleClass('col-md-4');
                    $('#historyItem').toggleClass('display-localizaoes');

                    $('#historyFinancial').toggleClass('col-md-4');
                    $('#historyFinancial').toggleClass('display-localizaoes');
                    $("#item").text(item);
                } else {
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
        });
    </script>
@endsection
