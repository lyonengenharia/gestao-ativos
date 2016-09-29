@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="container">
            <form action="{{url('/licencas/licenca/update')}}" method="post">
                <input type="hidden" name="keyid" value="{{$key->id}}">
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
                                @if($empresa->id == $produtos->myCompany->id)
                                    <option value="{{$empresa->id}}}" selected>{{$empresa->name}}</option>
                                @else
                                    <option value="{{$empresa->id}}}">{{$empresa->name}}</option>
                                @endif
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
                                <option value="{{$produtos->id}}" selected>{{$produtos->model}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('key'))
                            <span class="help-block">
                                <strong>{{ $errors->first('key') }}</strong>
                            </span>
                        @endif
                        <label>Chave</label>
                        <input type="text" name="key" required class="form-control" value="{{empty(old('key'))?$key->key:old('key') }}" style="text-transform: uppercase">
                    </div>
                    <div class="form-group">
                        @if ($errors->has('quantity'))
                            <span class="help-block">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                        @endif
                        <label>Quantidade de licenças</label>
                        <input type="number" name="quantity" required class="form-control" value="{{empty(old('quantity'))?$key->quantity:old('quantity') }}"
                               required>

                    </div>
                    <div class="form-group">
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        <label>Descrição</label>
                        <textarea type="text" name="description" class="form-control" cols="5">{{empty(old('description'))?$key->description:old('description')}}</textarea>
                    </div>


                    <button type="submit" class="btn btn-info" value="salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        function Products(data) {
            $.each(data, function(i,item){
                $('#produto_id').append(new Option(item.model,item.id));
            });
        }
        $(document).ready(function () {
            $('#empresa').change(function () {
                $('#produto_id').empty();
                if ($(this).val() !=='0') {
                    $.ajax({
                        url: '{{url('/api/produtos')}}' + '/' + $(this).val(),
                        type: 'get',
                        dataType:'json'
                    }).done(Products);
                }
            });
        });
    </script>
@endsection