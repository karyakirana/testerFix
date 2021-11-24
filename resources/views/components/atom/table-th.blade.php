@props(['type'=>'center', 'width'=>''])
<th {{$attributes->merge(['class' => 'text-'.$type])}} scope="col" style="width: {{$width}}">{{$slot}}</th>
