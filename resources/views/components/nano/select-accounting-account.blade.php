<select {{ $attributes->merge(['class'=>"form-control"]) }}>
    <option selected>Silahkan Pilih</option>
    @foreach($dataAccount as $row)
        <option value="{{$row->id}}">{{ $row->account_name }}</option>
    @endforeach
</select>
