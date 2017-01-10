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
                            <div class="huge">{{$user}}</div>
                            <div>Usuários</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-desktop fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$informatica}}</div>
                            <div>Equipamentos informática!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-exchange fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$emprestimos}}</div>
                            <div>Emprestados!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$termosnaoasinados}}</div>
                            <div>Termos sem assinar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-exchange fa-fw"></i> Itens Emprestados</h3>
                </div>
                <div class="panel-body">
                    @forelse($ultimosEmprestimos as $emprestimo)
                        <a href="{{url('ativos/')."#".$emprestimo->E670BEM_CODBEM}}" class="list-group-item">
                            <i class="fa fa-fw fa-desktop"></i> {{$emprestimo->E670BEM_CODBEM}}
                            <i class="fa fa-fw fa-calendar"></i> {{$emprestimo->data_saida->format('d/m/Y H:i')}}
                        </a>
                    @empty
                        <a href="#" class="list-group-item">
                            <i class="fa fa-fw fa-warning"></i> Não existem itens emprestados
                        </a>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-exchange fa-fw"></i> Últimos Devolvidos</h3>
                </div>
                <div class="panel-body">
                    @forelse($devolvidos as $devolvido)
                        <a href="{{url('ativos/')."#".$devolvido->E670BEM_CODBEM}}" class="list-group-item">
                            <i class="fa fa-fw fa-desktop"></i> {{$devolvido->E670BEM_CODBEM}}
                            <i class="fa fa-fw fa-calendar"></i> {{$devolvido->data_saida->format('d/m/Y H:i')}}
                        </a>
                    @empty
                        <a href="#" class="list-group-item">
                            <i class="fa fa-fw fa-warning"></i> Não existem itens devolvidos
                        </a>
                    @endforelse

                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Licenças com vencimento próximo</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @forelse($vencimentolicencas as $vencimentolicenca )
                            <a href="{{url('/licencas/licenca/')."/".$vencimentolicenca->id}}" class="list-group-item">
                                <span class="badge">{{$vencimentolicenca->maturity_date->format('d/m/Y')}}</span>
                                <i class="fa fa-fw fa-bookmark"></i> {{$vencimentolicenca->name}}
                                / {{$vencimentolicenca->model}}
                            </a>
                        @empty
                            <a href="#" class="list-group-item">
                                <i class="fa fa-fw fa-calendar"></i> Não exite licenças com vencimentos próximos
                            </a>
                        @endforelse

                    </div>
                    <div class="text-right">
                        <a href="{{url('licencas')}}">Verificar licenças <i
                                    class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Licenças vencidas</h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @forelse($vencidaslicencas as $vencimentolicenca )
                            <a href="{{url('/licencas/licenca/')."/".$vencimentolicenca->id}}" class="list-group-item">
                                <span class="badge">{{$vencimentolicenca->maturity_date->format('d/m/Y')}}</span>
                                <i class="fa fa-fw fa-bookmark"></i> {{$vencimentolicenca->name}}
                                / {{$vencimentolicenca->model}}
                            </a>
                        @empty
                            <a href="#" class="list-group-item">
                                <i class="fa fa-fw fa-calendar"></i> Não exite licenças com vencimentos próximos
                            </a>
                        @endforelse

                    </div>
                    <div class="text-right">
                        <a href="{{url('licencas')}}">Verificar licenças <i
                                    class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

