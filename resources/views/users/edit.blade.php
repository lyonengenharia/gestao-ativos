@extends('layouts.painel')

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p class="espaco-entrelinhas-xs"><b>Nome de Usuário:</b> {{$user->name}}</p>
                    <p class="espaco-entrelinhas-xs"><b>Usuário:</b> {{$user->username}}</p>
                    <p class="espaco-entrelinhas-xs"><b>E-mail:</b> {{$user->email}}</p>
                    <div id="idUser" style="display: none">{{$user->id}}</div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8">
            <form method="post" action="{{url('usuario/edit')}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Grupos</label>
                                <select class="form-control" id="groups" multiple>
                                    @foreach($roles as $role)
                                        @forelse($user->roles as $roleuser)
                                            @if($roleuser->id==$role->id)
                                                @break
                                            @endif
                                            @if($roleuser->id!=$role->id && $loop->last)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endif
                                        @empty
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforelse
                                    @endforeach
                                </select>
                                <a href="#" class="btn btn-default btn-xs" id="plus"><span
                                            class="glyphicon glyphicon-arrow-right"></span> </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Atual</label>
                                <select class="form-control" name="groups-update" id="groups-update" multiple>
                                    @forelse($user->roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @empty

                                    @endforelse
                                </select>
                                <a href="#" class="btn btn-default btn-xs" id="remove"><span
                                            class="glyphicon glyphicon-arrow-left"></span> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Salvar <span
                            class="glyphicon glyphicon-floppy-disk"></span></button>
            </form>
        </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        function salveForm(url, grupos, usuario) {
            var dados = [];
            console.log(usuario);
            for (i = 0; i < grupos.length; i++) {
                dados.push(grupos.options[i].value)
            }
            $.ajax({
                url: url,
                data: {permissoes: dados, usuario: usuario},
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if (result.erro === 0) {
                        alert(result.msg);
                        location.reload();
                    }
                }
            });
        }
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $('#plus').click(function () {
                $('select[name=groups-update]').append($('#groups :selected'));
            });
            $('#remove').click(function () {
                $('#groups').append($('select[name=groups-update] :selected'));
            });
            $('form').submit(function (e) {
                e.preventDefault();
                grupos = document.getElementById('groups-update');
                usuario = $('#idUser').text();
                salveForm('{{url('usuario/edit')}}', grupos, usuario);
            });
        });
    </script>

@endsection