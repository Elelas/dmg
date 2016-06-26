@extends('template.master')

@section('content')
    @include('includes.alerts')
    <div class="row">
        Всего файлов <span class="badge" id="files-amount-badge">{{count($files)}}</span>
    </div>
    <div class="row">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                Действия <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="">Общая цветовая статистика</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="list-group-item-danger" id="mass-delete-button">Удалить все</a></li>
            </ul>
        </div>
    </div>
@stop

@section('script')
    <script>
        $(
                function () {
                    $('#mass-delete-button').click(function (event) {
                        event.preventDefault();
                        var url = '{{route('image.mass.delete')}}';
                        var _token = '{{csrf_token()}}';
                        $.post(url, {
                            '_method': 'delete',
                            '_token': _token
                        }, function (data) {
                            $('#files-amount-badge').html(data);
                        }).fail(function (data) {
                            var resp = data.responseJSON;
                            console.log(data.statusText + '; ' + resp.message);
                            $('#files-amount-badge').html(resp.filesAmount);
                        });
                    });
                }
        );
    </script>
@stop

@section('style')
    <style>
        .row {
            margin-bottom: 10px;
        }
    </style>
@stop