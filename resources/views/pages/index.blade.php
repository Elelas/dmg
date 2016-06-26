@extends('template.master')

@section('content')
    <div class="row">
        @include('includes.alerts')
    </div>
    <div class="row">
        @foreach(Storage::disk('images')->allFiles() as $file)
            <div class="col-lg-3 col-md-4 col-xs-6 thumb" data-file="{{$file}}">
                <a class="thumbnail" href="#">
                    <img class="img-responsive" src="/images/{{$file}}">
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Действия <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="" name="delete_button" data-file="{{$file}}">Удалить</a></li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('style')

    <style>
        img {
            max-height: 160px;
        }

        .row {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('script')

    <script>
        $(
                function () {
                    $('a[name="delete_button"]').click(function (event) {
                        var button = $(event.currentTarget);
                        var fileName = button.data('file');
                        var url = '{{route('image.delete')}}/' + fileName;
                        $.post(url, {
                            '_method': 'delete',
                            '_token': '{{csrf_token()}}'
                        }, function (data) {
                            console.log(data);
                            $('div[data-file="' + fileName + '"]').remove();
                        }).fail(function (data) {
                            console.log(data.statusText + '; ' + data.responseJSON);
                        })
                    });
                }
        );
    </script>
@stop