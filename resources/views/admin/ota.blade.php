@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if ($problems)
        <x-core::alert type="warning" :title="trans('torns.ota_problems_title')">
            <ul class="mb-0">
                @foreach ($problems as $problem)
                    <li>{{ $problem }}</li>
                @endforeach
            </ul>
        </x-core::alert>
    @endif

    <div class="row row-cards">
        <div class="col-lg-7">
            @include('admin.ota.status')
        </div>
        <div class="col-lg-5">
            @include('admin.ota.available')
        </div>
    </div>

    @if ($conflicts)
        <x-core::alert type="warning" class="mt-3" :title="trans('torns.ota_conflicts_title')">
            <p>{{ trans('torns.ota_conflicts_text', ['items' => implode(', ', $conflicts)]) }}</p>
            <form method="POST" action="{{ route('torns-ota.use-repository-content') }}" data-confirm="{{ trans('torns.ota_confirm_repository_content') }}">
                @csrf
                <x-core::button type="submit" color="warning">{{ trans('torns.ota_use_repository_content') }}</x-core::button>
            </form>
        </x-core::alert>
    @endif

    @include('admin.ota.history')
@endsection
