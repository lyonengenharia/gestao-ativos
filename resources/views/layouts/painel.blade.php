<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/all.css')}}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!-- Custom CSS -->
{{--<link href="/css/sb-admin.css" rel="stylesheet">--}}

<!-- Library -->
    {{--<script src="{{asset('assets/js/app.js')}}"></script>--}}
    <script src="{{asset('assets/js/app.js ')}}"></script>
    <script src="{{asset('assets/js/all.js ')}}"></script>


    <!-- Custom Fonts -->
{{--<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">--}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>


<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('/')}}">{{ config('app.name') }}</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="fa fa-user"></i> {{ Auth::user()->name }}</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            <i class="fa fa-fw fa-power-off"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a href="{{url('/home')}}"><i class="fa fa-fw fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="{{ Request::is('ativos') ? 'active' : '' }}">
                    <a href="{{url('/ativos')}}"><i class="fa fa-fw fa-bar-chart-o"></i> Ativos</a>
                </li>
                <li class="{{ Request::is('licencas') ? 'active' : '' }}">
                    <a href="{{url('licencas')}}"><i class="fa fa-fw fa-table"></i> Licenças</a>
                </li>
                <li class="{{ Request::is('painel') ? 'active' : '' }}">
                    <a href="{{url('painel')}}"><i class="fa fa-fw fa-gears"></i> Painel Controle</a>
                </li>
                {{--<li>--}}
                {{--<a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;" data-toggle="collapse" data-target="#demo"><i--}}
                {{--class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>--}}
                {{--<ul id="demo" class="collapse">--}}
                {{--<li>--}}
                {{--<a href="#">Dropdown Item</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="#">Dropdown Item</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>--}}
                {{--</li>--}}
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">

                <h1 class="page-header">
                    {{$page}}
                    <small>{{$explanation}}</small>
                </h1>
                @if(isset($breadcrumbs))
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $rows =>$value)
                            <li class="active">
                                <a href="{{url($value)}}"> {{$rows}}</a>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>

        <!-- /.container-fluid -->
        @yield('content')
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->


<div class="div-load div-load-show div-load-hidden">
    <img src="{{asset("assets/img/load/gears.gif")}}">
    <h1>Carregando</h1>
</div>
<div class="modal fade" id="loading" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Por favor, espere mais alguns instantes...</p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>

</body>

</html>
