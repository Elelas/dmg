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
                <li><a href="{{route('image.mass.statistics')}}">Общая цветовая статистика</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="" class="list-group-item-danger" id="mass-delete-button">Удалить все</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <h4>Статистика цветов по всем файлам</h4>
        <div class="list-group col-md-3">
            @foreach($statFiles as $statFile)
                <a href="" class="list-group-item" data-filename="{{$statFile}}">{{$statFile}}</a>
            @endforeach
        </div>
        <div class="col-md-offset-3 col-md-6" id="mass-stat-result">

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

    <script>
        $(
                function () {
                    $('.list-group-item').click(function (event) {
                        event.preventDefault();
                        var resultDiv = $('#mass-stat-result');
                        resultDiv.empty();
                        var statItem = $(event.currentTarget);
                        var filename = statItem.data('filename');
                        var url = '{{route('mass.stat.result')}}/' + filename;
                        $.get(url, function (data) {
                            resultDiv.html(data);
                        });
                    })
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