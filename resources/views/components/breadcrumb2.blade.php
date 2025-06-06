@props(['items'])
<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
    <ol class="breadcrumb">
        @foreach ($items as $item)
            <li class="breadcrumb-item {{ $item['active'] ?? false ? 'active' : '' }}" 
                {{ $item['active'] ?? false ? 'aria-current="page"' : '' }}>
                @if (!($item['active'] ?? false))
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    {{ $item['label'] }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>