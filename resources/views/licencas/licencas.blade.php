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
                                <a href="{{url('/licencas/licenca/'.$licenca->keyid)}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> </a>
                                <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-resize-small"></span> </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p>Total de Registros {{$licencas->total()}}, exibindo {{$licencas->count()}}</p>{{$licencas->links()}}
            </div>
        </div>
    </div>
@endsection