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
        <div class="emprestimo-option display-emprestismo col-md-8">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <b><span id="statusEmprestimo"</b> <span id="item">Iten</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="panel-body" id="historyFinancialList">
                    <form name="emprestimo">
                        <div class="col-md-3">
                            <label for="tipcol">Tipo:</label>
                            <select class="form-control" name="tipcol">
                                <option value="1">Empregado</option>
                                <option value="2">Terceiro</option>
                                <option value="3">Parceiro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="numcad">Empresa:</label>
                            <select class="form-control" name="emp">
                                @foreach($empresas as $empresa)
                                    <option value="{{$empresa->numemp}}">{{$empresa->apeemp}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numemp">Colaborador(a):</label>
                                <input name="nomemp" id="nomemp" class="form-control" placeholder="Digite um nome para pesquisar"/>
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
                        "<button class=\"btn btn-success emprestimo\" type=\"button\">" +
                        "Emprestimo" +
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


        $(document).ready(function () {
            var availableTags = [
                "ActionScript",
                "AppleScript",
                "Asp",
                "BASIC",
                "C",
                "C++",
                "Clojure",
                "COBOL",
                "ColdFusion",
                "Erlang",
                "Fortran",
                "Groovy",
                "Haskell",
                "Java",
                "JavaScript",
                "Lisp",
                "Perl",
                "PHP",
                "Python",
                "Ruby",
                "Scala",
                "Scheme"
            ];
            $("#nomemp").autocomplete({
                source: function (request,response) {
                    var nome = $("#nomemp").val();
                    var empresa = $('select[name=emp] :selected').val();
                    var tipcol = $('select[name=tipcol] :selected').val();
                    $.ajax({
                        url:'{{url('api/colaboradores/')}}'+'/'+nome+'/'+tipcol+'/'+empresa,
                        dataType: "json",
                        type:'get',
                        success:function (data) {
                            response(data);
                        }



                    })
                },
                focus: function( event, ui ) {
                    console.log(ui);
                    $( "#nomemp" ).val( ui.item.NOMFUN );
                    return false;
                },
            });
            $('form').submit(function (e) {
                e.preventDefault();
                $('.search').remove();
                $('#historyFinancialList').empty();
                $('#historicoLocalizacoes').empty();
                $('#historyItem').addClass('display-localizaoes');
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
//                $(this).prop('disabled',true);
                $(".div-load").toggleClass('div-load-hidden');
                var item = $(this).parent().parent().find('.panel-heading').text();
                var panel = $(this).parent().parent();
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


        });
    </script>
@endsection
