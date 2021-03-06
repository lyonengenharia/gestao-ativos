@extends('layouts.painel')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$users}}</div>
                            <div>Usuários</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('painel/usuarios')}}">
                    <div class="panel-footer">
                        <span class="pull-left">Gestão</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-upload fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Importação</div>
                            <div>de dados e configuração</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('painel/importacao')}}">
                    <div class="panel-footer">
                        <span class="pull-left">Gestão</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-file fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Termos</div>
                            <div>Termos utilizados</div>
                        </div>
                    </div>
                </div>
                <a href="{{url('painel/termos')}}">
                    <div class="panel-footer">
                        <span class="pull-left">Gestão</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection