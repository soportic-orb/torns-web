<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $row)
        <li>
            <a
                href="{{ url($row->url) }}"
                @if ($row->target !== '_self') target="{{ $row->target }}" rel="noopener" @endif
                @if ($row->active) aria-current="page" @endif
                class="nav-link {{ $row->css_class }} @if ($row->active) nav-link-active @endif"
            >
                {{ $row->title }}
            </a>
        </li>
    @endforeach
</ul>
