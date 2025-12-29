@extends('layouts.shop')

@section('title', 'Shop')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold mb-8 text-center">Shop</h1>

    <div class="lg:grid lg:grid-cols-4 lg:gap-6">
        <!-- Filter column -->
        <x-filter-panel />

        <!-- Perfume cards -->
        <div class="lg:col-span-3 grid grid-cols-1 py-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
            @foreach ($perfumes as $perfume)
                @include('components.perfume-card', ['perfume' => $perfume])
            @endforeach
        </div>
    </div>
</div>
@endsection
