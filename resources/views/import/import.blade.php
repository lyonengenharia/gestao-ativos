@extends('layouts.painel')

@section('content')
    <link href="{{asset('assets/css/fileupload/fileinput.min.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/fileinput/fileinput.js')}}"></script>
    <script src="{{asset('assets/js/fileinput/locales/pt-BR.js')}}"></script>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <table class="table table-responsive">
                <thead>
                <th>Arquivo</th>
                <th>Data inportação</th>
                <th>Processado</th>
                <th></th>
                </thead>
                <tbody>
                @forelse($files as $file)
                    @if($file!='.gitignore')
                        <tr>
                            <td class="name-file">{{str_replace('import/data/','',$file)}}</td>
                            <td>09/11/2016</td>
                            <td>Não</td>
                            <td>
                                <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span>
                                </button>
                                <button class="btn btn-danger btn-xs delete-file"><span class="glyphicon glyphicon-remove"></span>
                                </button>
                                <button class="btn btn-warning btn-xs process-file"><span
                                            class="glyphicon glyphicon-refresh"></span></button>
                                <a href="{{Storage::url(str_replace('import/data/','',$file).'.log')}}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td rowspan="4">
                            Nenhum arquivo importado
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <form enctype="multipart/form-data" id="formimagens">
                {{ csrf_field() }}
                <div class="form-group">
                    <input id="file" name="file[]" type="file" multiple class="file"
                           data-upload-url="{{url('painel/importacao/import')}}" data-preview-file-type="jpg,png,gif">
                </div>
            </form>
        </div>
    </div>

    <script>
        function ProcessFile(url, data) {
            $.ajax({
                url: url,
                data: {data:data},
                type: 'get',
                dataType:'json',
                success: function (request) {
                    if(request.error==0){
                        swal(
                                'Solicitação realizada!',
                                request.msg,
                                'success'
                        );
                    }
                }
            });
        }
        function DeleteFile(url, data) {
            $.ajax({
                url: url,
                data: {data:data},
                type: 'delete',
                dataType: 'json',
                success: function (response) {
                    if(response.error){
                        alert(response.msg)
                    }else{
                        alert(response.msg);
                        location.reload();
                    }
                }
            });
        }
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.process-file').click(function () {
                var file = $(this).parent().parent().find('.name-file').text();
                ProcessFile('{{url('importacao/process')}}', file);
            });

            $('.delete-file').click(function () {
                var file = $(this).parent().parent().find('.name-file').text();
                if(confirm("Deseja realmente apagar esse arquivo ?")){
                    DeleteFile('{{url('importacao/delete')}}', file);
                }

            });
        });


    </script>
@endsection





