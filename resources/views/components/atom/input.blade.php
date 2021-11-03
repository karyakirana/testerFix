@props(['name'=>''])
<input {{ $attributes }} type="text" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" wire:model.defer="{{ $name }}">
@error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
