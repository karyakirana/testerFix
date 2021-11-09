@props(['title', 'footer', 'closeModal'=>null])
<div {{ $attributes }} class="modal fade" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $title ?? '' }}</h5>
                <button type="button" class="close" @if($closeModal) wire:click.prevent="{{$closeModal}}" @endif data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                {{ $footer ?? '' }}
            </div>
        </div>
    </div>
</div>
