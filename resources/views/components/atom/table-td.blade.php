@props(['type'=>'left'])
<th {{$attributes->merge(['class' => 'text-'.$type.' align-middle'])}}>{{$slot}}</th>
