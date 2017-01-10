@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Pesquisa
                </div>
                <div class="panel-body">
                    <span class="pull-left">Gestão</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12" style="margin-bottom: 5px;">
            <a href="{{url('/painel/usuarios/grupos')}}" class="btn btn-default">Novo grupo <span
                        class="fa fa-group"></span> </a>
            <a href="{{url('/permissoes')}}" class="btn btn-default">Permissões <span class="fa fa-lock"></span> </a>
        </div>
        <div class="col-lg-12 col-md-12">
            <table class="table table-bordered hover">
                <thead>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Permissões</th>
                <th>Ações</th>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>{{$role->label}}</td>
                        <td>
                            @forelse($role->permissions as $permission)
                                <p></p><b>{{$permission->name}}</b>,{{$permission->label}}</p>
                            @empty
                                Sem Permissões
                            @endforelse
                        </td>
                        <td>
                            <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                            <button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-erase"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--<p>Total de Registros {{$users->total()}}, exibindo {{$users->count()}}</p>{{$users->links()}}--}}
        </div>
    </div>
@endsection