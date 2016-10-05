@extends('layouts.painel')

@section('content')
    <div class="container">
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
            <div class="col-lg-12 col-md-12">
                <a href="{{url('/painel/usuarios/grupos')}}" class="btn btn-default">Grupos <span class="fa fa-group"></span> </a>
            </div>
            <div class="col-lg-12 col-md-12">
                <table class="table table-bordered hover">
                    <thead>
                    <th>Usuário</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Grupos</th>
                    <th>Ações</th>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->username}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    {{$role->name}}
                                 @empty
                                     Sem grupos
                                 @endforelse
                            </td>
                            <td>
                                <input type="hidden" name="iduser" value="{{$user->id}}">
                                <a href="{{url("/usuario/$user->id")}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span> </a>
                                <button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-erase"></span> </button>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                <p>Total de Registros {{$users->total()}}, exibindo {{$users->count()}}</p>{{$users->links()}}
            </div>
        </div>
    </div>
@endsection