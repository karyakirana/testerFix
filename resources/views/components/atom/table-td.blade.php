@props(['type'=>'left'])
<td {{$attributes->merge(['class' => 'text-'.$type.' align-middle'])}}>{{$slot}}</td>
