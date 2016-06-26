@extends('template.master')

@section('content')
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
                    <h3>HOME</h3>
                    <p>Some content.</p>
                </div>
                <div id="remap-tab" class="tab-pane fade">
                    <h3>Menu 1</h3>
                    <p>Some content in menu 1.</p>
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

@stop