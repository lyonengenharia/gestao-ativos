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
            <a href="{{url('/permission')}}" class="btn btn-default">Nova Permissão <span class="fa fa-lock"></span>
            </a>
        </div>
        <div class="col-lg-12 col-md-12">
            @if (session('status'))

                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif
            <table class="table table-bordered hover">
                <thead>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Grupos</th>
                <th>Data Criação</th>
                <th>Ações</th>
                </thead>
                <tbody>
                @foreach($Permissions as $permission)
                    <tr>
                        <td>{{$permission->name}}</td>
                        <td>{{$permission->label}}</td>
                        <td>
                            @forelse($permission->roles as $role)
                                {{$role->name}}
                            @empty
                                Não existem grupos
                            @endforelse
                        </td>
                        <td>{{$permission->created_at}}</td>
                        <td>
                            <a href="{{url('permission')."/".$permission->id}}" class="btn btn-default btn-xs"><span
                                        class="glyphicon glyphicon-pencil"></span>
                            </a>
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