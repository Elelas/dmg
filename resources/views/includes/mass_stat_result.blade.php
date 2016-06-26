@foreach($colors as $item)
    <div class="col-md-2" style="border: 1px solid black; margin: 5px;">
        <div style="background-color: #{{$item[0]}}; width: 50px; height: 50px"></div>
        {{$item[1]}}
    </div>
@endforeach