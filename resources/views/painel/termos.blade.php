@extends('layouts.painel')

@section('content')
    <div class="row" ng-app="termo">
        <div class="col-lg-12 col-md-12">

            <div class="jumbotron">
                <input class="form-control" placeholder="pesquisa" type="text" ng-model="pesquisa">
            </div>
            <table class="table table-bordered">
                <th>Nome</th>
                <th>Descrição</th>
                <th>Criado em</th>
                <th>Atualizado</th>
                <th></th>
                <tbody>
                    <tr ng-controller="listaTelefonica">
                        <td>@{{ teste }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('assets/js/angular.min.js')}}"></script>
    <script src="{{ asset('assets/js/termos.js')}}"></script>
@endsection