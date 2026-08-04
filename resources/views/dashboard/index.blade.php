@extends('layouts.app')

@section('content')

@include('partials.tabs')

@include('partials.banner')

@include('partials.stats')

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; margin-top: 24px;">
        
        <div>
            @include('partials.kunjungan-hari-ini')
        </div>

        <div>
            @include('partials.aktivitas-terbaru')
        </div>

    </div>

   <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 24px;">
    <div>
        @include('partials.check-in-tamu')
    </div>

    <div>
        @include('partials.ringkasan-operasional')
    </div>
</div>
@endsection