<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $row)
        <li>
            <a
                href="{{ url($row->url) }}"
                @if ($row->target !== '_self') target="{{ $row->target }}" rel="noopener" @endif
                class="footer-link text-sm {{ $row->css_class }}"
            >
                {{ $row->title }}
            </a>
        </li>
    @endforeach
</ul>
