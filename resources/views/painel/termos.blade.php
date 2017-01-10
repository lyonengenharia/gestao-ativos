@extends('layouts.painel')

@section('content')

    <div class="row" ng-app="termo" ng-init="termos={{$termos}}" ng-controller="listaTelefonica">
        <div class="col-lg-12 col-md-12">
            {{--<p>Error: @{{ error }}</p>--}}
            <div id="error" ng-if="error">
                <div class='alert alert-warning alert-dismissible fade in' role='alert' ng-if="error">
                    <div id="error-msg">@{{ errorMsg }}</div>

                </div>
            </div>
            <div class="panel panel-default" ng-if="exibirEditar">
                <div class="col-lg-12">
                    <button type="button" ng-click="cancelarEdit()" class="close" data-dismiss="alert"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="container">
                    <div ng-messages="formTermo.nome.$error" ng-show="formTermo.nome.$dirty">
                        <div ng-message="required" class="alert alert-danger">
                            Por favor preencha o nome...
                        </div>
                    </div>
                    <div ng-messages="formTermo.descricao.$error" ng-show="formTermo.descricao.$dirty">
                        <div ng-message="required" class="alert alert-danger">
                            Por favor preencha a descrição...
                        </div>
                    </div>
                    <p>
                    <h1>Edição</h1></p>
                    <form name="formTermo">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input class="form-control" placeholder="pesquisa" type="text"
                                           ng-model="existingTermo.name" name="nome" ng-required="true">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label>Descrição</label>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Descrição" type="text"
                                              ng-model="existingTermo.description" ng-required="true"
                                              name="descricao"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button class="btn btn-primary" ng-click="editarSalvar(existingTermo)">Salvar
                                    </button>
                                    <button class="btn btn-warning" ng-click="cancelarEdit()">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="panel panel-default" ng-if="exibirNovo">

                <div class="col-lg-12">
                    <button type="button" ng-click="cancelarNovo()" class="close" data-dismiss="alert"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="container">
                    <div ng-messages="formNovoTermo.novoNome.$error" ng-show="formNovoTermo.novoNome.$dirty">
                        <div ng-message="required" class="alert alert-danger">
                            Por favor preencha o nome...
                        </div>
                    </div>
                    <div ng-messages="formNovoTermo.descricaoNovo.$error" ng-show="formNovoTermo.descricaoNovo.$dirty">
                        <div ng-message="required" class="alert alert-danger">
                            Por favor preencha a descrição
                        </div>
                    </div>
                    <p>
                    <h1>Novo</h1></p>
                    <form name="formNovoTermo">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input class="form-control" ng-required="true" type="text"
                                           ng-model="termo.name" name="novoNome">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label>Descrição</label>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Descrição" type="text"
                                              ng-model="termo.description" ng-required="true"
                                              name="descricaoNovo"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button class="btn btn-primary" ng-click="novoSalvar(termo)">Salvar</button>
                                    <button class="btn btn-warning" ng-click="cancelarNovo()">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <table class="table table-bordered">
                <th>Nome</th>
                <th>Descrição</th>
                <th>Criado em</th>
                <th>Atualizado</th>
                <th>
                    <button class="btn btn-block btn-default" ng-click="novoContato()" title="Novo"><span
                                class="glyphicon glyphicon-plus"></span></button>
                </th>
                <tbody>
                <tr ng-repeat="termo in termos">
                    <td>@{{ termo.name }}</td>
                    <td>@{{ termo.description }}</td>
                    <td>@{{termo.created_at | date:'dd/MM/yyyy' }}</td>
                    <td>@{{termo.updated_at | date:'dd/MM/yyyy HH:mm'}}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" ng-click="editar(termo)"><span
                                    class="glyphicon glyphicon-pencil"></span></button>
                        <button class="btn btn-danger btn-xs" ng-click="delete(termo)"><span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('assets/js/angular.min.js')}}"></script>
    <script src="{{ asset('assets/js/termos.js')}}"></script>
@endsection