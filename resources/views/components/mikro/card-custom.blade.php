@props(['title', 'toolbar', 'footer'])
<div class="card card-custom gutter-b">
    <div class="card-header flex-wrap py-3">
        <div class="card-title">
            <h3 class="card-label">{{ $title ?? '' }}</h3>
        </div>
        <div class="card-toolbar">
            {{ $toolbar ?? '' }}
        </div>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
    <div class="card-footer">
        {{ $footer ?? '' }}
    </div>
</div>
