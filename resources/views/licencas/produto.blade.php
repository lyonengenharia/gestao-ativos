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
                        <select name="empresa" class="form-control" value="{{old('empresa') }}">
                            @foreach($empresas as $empresa)
                                <option value="{{$empresa->id}}">{{$empresa->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('model'))
                            <span class="help-block">
                                <strong>{{ $errors->first('model') }}</strong>
                            </span>
                        @endif
                        <label>Produto</label>
                        <input name="model" class="form-control" value="{{old('model') }}">
                    </div>
                    <div class="form-group">
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        <label>Descrição</label>
                        <textarea type="text" name="description" required class="form-control"
                                  cols="5">{{old('description') }}</textarea>

                    </div>
                    <button type="submit" class="btn btn-info" value="salvar">Salvar</button>
                </div>
            </form>
        </div>

    </div>
@endsection