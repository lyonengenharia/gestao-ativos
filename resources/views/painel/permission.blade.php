@extends('layouts.painel')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                @if (session('status'))
                    <div class="alert alert-warning">
                        {{ session('status') }}
                    </div>
                @endif
                <form action="{{$action}}" method="post">
                    {{  csrf_field() }}
                    @if($action=='update')
                        <input type="hidden" name="idpermission" value="{{$permission->id}}">
                    @endif

                    <div class="form-group">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <label for="name">Nome</label>
                        <input type="text" name="name" class="form-control"
                               value="{{$action=='update'?$permission->name:old('name')}}" required>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                        <label for="description">Descrição</label>
                        <textarea cols="5" name="description"
                                  class="form-control">{{$action=='update'?$permission->label:old('description')}}</textarea>
                    </div>
                    <button class="btn btn-default" type="submit"><span
                                class="glyphicon glyphicon-floppy-disk">Salvar</span></button>
                </form>

            </div>


        </div>
    </div>
@endsection