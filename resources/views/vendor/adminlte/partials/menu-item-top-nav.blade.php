@if (is_array($item))
    <li class="{{ $item['top_nav_class'] ?? '' }}">
        <a href="{{ $item['href'] ?? '#' }}"
           @if (!empty($item['submenu'])) class="dropdown-toggle" data-toggle="dropdown" @endif
           @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        >
            <i class="fa fa-fw fa-{{ $item['icon'] ?? 'circle-o' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
            {{ $item['text'] ?? 'Menu' }}
            @if (isset($item['label']))
                <span class="label label-{{ $item['label_color'] ?? 'primary' }}">{{ $item['label'] }}</span>
            @elseif (!empty($item['submenu']))
                <span class="caret"></span>
            @endif
        </a>

        @if (!empty($item['submenu']))
            <ul class="dropdown-menu" role="menu">
                @foreach($item['submenu'] as $subitem)
                    @if (is_string($subitem))
                        @if($subitem == '-')
                            <li role="separator" class="divider"></li>
                        @else
                            <li class="dropdown-header">{{ $subitem }}</li>
                        @endif
                    @else
                        <li class="{{ $subitem['top_nav_class'] ?? '' }}">
                            <a href="{{ $subitem['href'] ?? '#' }}">
                                <i class="fa fa-fw fa-{{ $subitem['icon'] ?? 'circle-o' }} {{ isset($subitem['icon_color']) ? 'text-' . $subitem['icon_color'] : '' }}"></i>
                                {{ $subitem['text'] ?? 'Submenu' }}
                                @if (isset($subitem['label']))
                                    <span class="label label-{{ $subitem['label_color'] ?? 'primary' }}">{{ $subitem['label'] }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
@endif
