@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="container">
            <form action="{{url('/licencas/licenca/insert')}}" method="post">
                {{  csrf_field() }}
                <div class="col-md-6 col-md-offset-3">
                    @if (session('status'))
                        <div class="alert alert-warning">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Empresa</label>
                        <select id="empresa" class="form-control" value="{{old('empresa') }}">
                            <option value="0"></option>
                            @foreach($empresas as $empresa)
                                <option value="{{$empresa->id}}">{{$empresa->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('produto_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('produto_id') }}</strong>
                            </span>
                        @endif
                        <label>Produto</label>
                        <select name="produto_id" id="produto_id" class="form-control" value="{{old('produto_id') }}">
                        </select>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('key'))
                            <span class="help-block">
                                <strong>{{ $errors->first('key') }}</strong>
                            </span>
                        @endif
                        <label>Chave</label>
                        <input type="text" name="key" required class="form-control" value="{{old('key') }}"
                               style="text-transform: uppercase">
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                @if ($errors->has('quantity'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                                @endif
                                <label>Quantidade de licenças</label>
                                <input type="number" name="quantity" required class="form-control"
                                       value="{{old('quantity')}}"
                                       required>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @if ($errors->has('datavencimento'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('datavencimento') }}</strong>
                            </span>
                                @endif
                                <label>Data Vencimento licença</label>
                                <input type="text" name="datavencimento"  class="form-control"
                                       value="{{old('datavencimento')}}" id="datavencimento">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        <label>Descrição</label>
                        <textarea type="text" name="description" class="form-control"
                                  cols="5">{{old('description') }}</textarea>
                    </div>


                    <button type="submit" class="btn btn-info" value="salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        function Products(data) {
            $.each(data, function (i, item) {
                $('#produto_id').append(new Option(item.model, item.id));
            });
        }
        $(document).ready(function () {
            $('#empresa').change(function () {
                $('#produto_id').empty();
                if ($(this).val() !== '0') {
                    $.ajax({
                        url: '{{url('/api/produtos')}}' + '/' + $(this).val(),
                        type: 'get',
                        dataType: 'json'
                    }).done(Products);
                }
            });
            $('#datavencimento').datepicker({
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