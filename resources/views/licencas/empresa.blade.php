@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="container">

            <form action="{{url('licencas/empresa/insert')}}" method="post">
                {{  csrf_field() }}
                <div class="col-md-6 col-md-offset-3">
                    @if (session('status'))
                        <div class="alert alert-warning">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <label>Nome Empresa</label>
                        <input type="text" name="name" required class="form-control" value="{{old('name') }}">
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