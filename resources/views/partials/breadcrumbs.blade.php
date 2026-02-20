@php
    $items = $items ?? [];
@endphp
@if(count($items) > 0)
<style>
    html.dark-mode .breadcrumb-item.active {
        color: #ffffff !important;
    }
</style>
<nav aria-label="breadcrumb" class="mb-2">
    <ol class="breadcrumb mb-0 small">
        @foreach($items as $i => $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">{{ $item['label'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
@endif
