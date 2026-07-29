<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $row)
        <li>
            <a
                href="{{ url($row->url) }}"
                @if ($row->target !== '_self') target="{{ $row->target }}" rel="noopener" @endif
                @if ($row->active) aria-current="page" @endif
                class="block rounded-lg px-3 py-2.5 text-base font-medium transition hover:bg-torns-primary/5 hover:text-torns-primary {{ $row->css_class }} @if ($row->active) bg-torns-primary/5 text-torns-primary @endif"
            >
                {{ $row->title }}
            </a>
        </li>
    @endforeach
</ul>
