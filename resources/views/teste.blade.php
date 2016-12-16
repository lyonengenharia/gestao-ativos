<!DOCTYPE html>
<html ng-app="listaTelefonica">
<head>
    <title>Laravel</title>
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/all.css')}}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://code.angularjs.org/1.6.0/angular-messages.js"></script>
    <style>
        .selecionado {
            background-color: yellow;
        }
    </style>
</head>
<body>
<div class="container">
    <div ng-controller="listaTelefonica">
        <div class="jumbotron">
            <h3 ng-bind="teste"></h3>
            <input type="text" ng-model="busca" class="form-control" placeholder="Busca">
            <table class="table table-bordered" ng-show="contatos.length >0">
                <thead>
                <th></th>
                <th><a href="#" ng-click="ordenarPor('nome')"> Nome</a></th>
                <th><a href="#" ng-click="ordenarPor('telefone')">Telefone</a></th>
                <th><a href="#" ng-click="ordenarPor('operadora')">Operadora</a></th>
                <th><a href="#" ng-click="ordenarPor('data')">data</a></th>
                </thead>
                <tbody>
                <tr class="" ng-repeat="contato in contatos | filter:busca | orderBy:criterioDeOrdenacao:DirecaoDaOrdenacao" ng-class="{selecionado : contato.selecionado}">
                    <td><input type="checkbox" class="form-control" ng-model="contato.selecionado"></td>
                    <td>@{{contato.nome | uppercase}}</td>
                    <td>@{{contato.telefone}}</td>
                    <td>@{{contato.operadora.nome}}</td>
                    <td>@{{contato.data | date:'dd/MM/yyyy HH:mm' }}</td>
                </tr>
                </tbody>
            </table>
            <div ng-messages="contatoForm.nome.$error">
                <div  ng-message="required" class="alert alert-danger">
                    Por favor o nome
                </div>
                <div ng-message="minlength" class="alert alert-danger">
                    O minimo para o nome é 10
                </div>
            </div>
            <div  ng-show="contatoForm.telefone.$dirty" ng-messages="contatoForm.telefone.$error"  >
                <div  ng-message="required" class="alert alert-danger">
                    Por favor o telefone
                </div>
                <div  ng-message="pattern" class="alert alert-danger">
                    O campo está fora do formato
                </div>
            </div>
            <form name="contatoForm">
                <input type="text" class="form-control" placeholder="Nome" ng-model="contato.nome" ng-name="true"
                       name="nome" ng-minlength="10">
                <input type="text" class="form-control" placeholder="Telefone" ng-model="contato.telefone"
                       ng-required="true" name="telefone" ng-pattern="/^\d{4,5}-\d{4}$/">
                <select class="form-control" ng-model="contato.operadora"
                        ng-options="operadora.nome group by operadora.categoria for operadora in operadoras | lowercase:'nome' | orderBy:'nome'">
                    <option value="">Selecione uma operadora</option>
                </select>
                <button class="btn btn-primary btn-block" ng-click="adicionarContato(contato)"
                        ng-disabled="contatoForm.$invalid">Adicionar
                    Contato
                </button>
                <button class="btn btn-danger btn-block" ng-click="apagarContatos(contatos)"
                        ng-if="isContatosSelecionados(contatos)">Apagar Contatos
                </button>
            </form>

        </div>
    </div>
</div>
<script>
    angular.module('listaTelefonica', ["ngMessages"]);
    angular.module('listaTelefonica').controller('listaTelefonica', function ($scope,$http) {
        $scope.teste = "Lista Telefonica";
        $scope.contatos = [];
        $scope.operadoras = [
            {nome: 'Oi', codigo: 31, categoria: 'Celular'},
            {nome: 'Vivo', codigo: 15, categoria: 'Celular'},
            {nome: 'tim', codigo: 41, categoria: 'Celular'},
            {nome: 'Claro', codigo: 32, categoria: 'Celular'},
            {nome: 'GVT', codigo: 25, categoria: 'Fixo'},
            {nome: 'Embratel', codigo: 21, categoria: 'Fixo'},
        ];
        $scope.adicionarContato = function (contato) {
            $scope.contatos.push(contato);
            delete $scope.contato;
            $scope.contatoForm.$setPristine();
        }
        $scope.apagarContatos = function (contatos) {

            $scope.contatos = contatos.filter(function (contato) {
                if (!contato.selecionado) return contato;
            })
        }
        $scope.isContatosSelecionados = function (contatos) {
            var isContatoSelecionado = contatos.some(function (contato) {
                if (contato.selecionado) return contato.selecionado;
            });
            console.log(isContatoSelecionado);
            return isContatoSelecionado;
        }
        $scope.ordenarPor = function (campo) {
            $scope.criterioDeOrdenacao = campo;
            $scope.DirecaoDaOrdenacao = !$scope.DirecaoDaOrdenacao;
        }
        var carregarContatos = function () {
            $http.get('http://10.30.2.49:3000/').success(function (data,status) {
                $scope.contatos = data;
            });
        }
        $scope.classe = "selecionado";
        carregarContatos();
    });
</script>
</body>
</html>