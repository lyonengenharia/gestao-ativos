@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="container">
            <form action="{{url('/licencas/produto/insert')}}" method="post">
                {{  csrf_field() }}
                <div class="col-md-6 col-md-offset-3">
                    @if (session('status'))
                        <div class="alert alert-warning">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group">
                        @if ($errors->has('empresa'))
                            <span class="help-block">
                                <strong>{{ $errors->first('empresa') }}</strong>
                            </span>
                        @endif
                        <label>Empresa</label>
                        <select id="empresa" class="form-control" value="{{old('empresa') }}">
                            <option value=""></option>
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
                        <input type="text" name="key" required class="form-control" value="{{old('key') }}">
                    </div>
                    <div class="form-group">
                        @if ($errors->has('quantity'))
                            <span class="help-block">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                        @endif
                        <label>Quantidade de licenças</label>
                        <input type="number" name="quantity" required class="form-control" value="{{old('quantity')}}"
                               required>

                    </div>
                    <div class="form-group">
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        <label>Descrição</label>
                        <textarea type="text" name="description" required class="form-control" cols="5">
                            {{old('description') }}
                        </textarea>
                    </div>


                    <button type="submit" class="btn btn-info" value="salvar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        $(document).ready(function () {
           $('#empresa').change(function () {
              $.ajax({
                 url:{{url('')}}
              });
           });
        });
    </script>
@endsection