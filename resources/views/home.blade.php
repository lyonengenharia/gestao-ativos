@extends('layouts.painel')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @can('acesso')
                <div class="panel panel-default">

                        <div class="panel-heading">Dashboard</div>

                        <div class="panel-body">
                            You are logged in!
                        </div>

                </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
