@extends('template.master')

@section('content')
    <div class="row">
        @include('includes.alerts')
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
                <img src="/images/{{$fileName}}" alt="{{$fileName}}">
                <div class="caption">
                    <h3>{{$fileName}}</h3>
                    <p>Размер: <b>{{$image->getImageWidth()}}x{{$image->getImageHeight()}}</b></p>
                    <p>Количество уникальных цветов: <b>{{$image->getImageColors()}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-8">
            <ul class="nav nav-tabs">
                <li><a data-toggle="tab" href="#reduce-tab">Уменьшение</a></li>
                <li><a data-toggle="tab" href="#remap-tab">Подмена карты</a></li>
                <li><a data-toggle="tab" href="#reduce-remap-tab">Уменьшение + подмена карты</a></li>
                <li><a data-toggle="tab" href="#red-remap-increase-tab">Уменьшение + подмена карты + увеличение</a></li>
            </ul>

            <div class="tab-content">
                <div id="reduce-tab" class="tab-pane fade in active">
                    <h3>Параметры операции</h3>
                    <div class="form-group has-feedback" id="reduce-params-block">
                        {!! Form::label('boxAmount', 'Количество пикселей по ширине и высоте') !!}

                        {!! Form::number('boxAmount', 120, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::button('Выполнить', ['class' => 'btn btn-default', 'id' => 'reduce-result-button']) !!}
                    </div>
                    <div id="reduce-result">
                    </div>
                </div>
                <div id="remap-tab" class="tab-pane fade">
                    <h3>Параметры операции</h3>
                    <div>
                        <div class="form-group" id="reduce-params-block">
                            {!! Form::label('colorMap', 'Карта цветов') !!}

                            {!! Form::file('colorMap', ['class' => 'form-control', 'data-url' => route('image.remap',
                            ['file' => $fileName])]) !!}
                        </div>
                    </div>
                    <div id="remap-result">
                    </div>
                </div>
                <div id="reduce-remap-tab" class="tab-pane fade">
                    <h3>Menu 2</h3>
                    <p>Some content in menu 2.</p>
                </div>
                <div id="red-remap-increase-tab" class="tab-pane fade">
                    <h3>Menu 2</h3>
                    <p>Some content in menu 2.</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var checkBoxAmount = function () {
            var reduceParamsBlock = $('#reduce-params-block');
            if ($('input[name="boxAmount"]').val() == '') {
                reduceParamsBlock.removeClass('has-success');
                reduceParamsBlock.addClass('has-error');
            } else {
                reduceParamsBlock.removeClass('has-error');
                reduceParamsBlock.addClass('has-success');
            }
        };
    </script>

    {{--уменьшение размера--}}
    <script>
        $(
                function () {
                    $('input[name="boxAmount"]').keyup(checkBoxAmount);
                    $('input[name="boxAmount"]').change(checkBoxAmount);

                    $('#reduce-result-button').click(function () {
                        if ($('input[name="boxAmount"]').val() == '') {
                            alert("Нужно заполнить поле 'Количество пикселей поconsole.log(data); ширине и высоте'");
                        } else {
                            var url = '{{route('image.reduce', ['file' => $fileName])}}';
                            $.get(url, {
                                'boxAmount': $('input[name="boxAmount"]').val()
                            }, function (data) {
                                $('#red-result-img').remove();
                                $('#reduce-result').html('<img id="red-result-img" src="' + data
                                        + '?' + new Date().getTime() +
                                        '" class="img-thumbnail" width="{{$image->getImageWidth()}}" height="{{$image->getImageHeight()}}">');
                            }).fail(function (data) {
                                console.log(data.responseJSON);
                            });
                        }
                    });
                }
        );
    </script>

    {{--Подмена карты--}}
    {!! Html::script('/js/jquery.fileupload.js') !!}
    <script>
        $(
                function () {
                    $('#colorMap').fileupload({
                        dataType: 'json',
                        done: function (e, data) {
                            if (data.result.error != undefined) {
                                alert(data.result.error);
                            } else {
                                $('#colorMap').removeAttr('disabled');
                                $('#remap-result').empty();
                                $('#remap-result').html('<img id="red-result-img" src="' + data.result
                                        + '?' + new Date().getTime() +
                                        '" class="img-thumbnail" width="{{$image->getImageWidth()}}" height="{{$image->getImageHeight()}}">');
                            }
                        },
                        submit : function () {
                            $('#colorMap').attr('disabled', 'disabled');
                            $('#remap-result').html('Ждите');
                        }
                    });
                }
        );
    </script>
@stop