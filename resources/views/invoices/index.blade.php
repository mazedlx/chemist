@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    <div class="flex flex-wrap">
        <div class="mx-1 my-1 px-6 py-4 border hover:bg-grey-light">
            <a href="/invoices/create" class="text-xl no-underline text-black">
                Neue Rechnung
            </a>
        </div>

        @forelse($invoices as $invoice)
        <div class=" flex-1 mx-1 my-1 px-6 py-4 border hover:bg-grey-light">
            <div class="text-lg">
                <a href="/invoices/{{ $invoice->id }}" class="no-underline">
                <div class="text-grey">
                    {{ $invoice->date->format('d.m.Y') }}
                </div>

                <div class="text-black">
                    &euro; {{ $invoice->money }}
                </div>

                <div class="text-sm text-grey">
                    {{ $invoice->category->title }}
                </div>
                </a>
            </div>
        </div>
        @empty
        <h1>Noch keine Rechnungen!</h1>
        @endforelse
    </div>
</div>
@endsection
