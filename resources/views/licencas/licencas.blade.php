@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{url('licencas/empresa')}}" class="btn btn-default" >Nova Empresa</a>
            <a href="{{url('licencas/produto')}}" class="btn btn-default">Novo Produto</a>
            <a href="{{url('licencas/licenca')}}" class="btn btn-default">Nova Licença</a>
        </div>
    </div>
@endsection