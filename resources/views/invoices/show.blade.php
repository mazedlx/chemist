@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    <div class="flex flex-wrap">
        <div class="mx-1 my-1 px-6 py-4 border hover:bg-grey-light">
            <a href="/invoices/create" class="text-xl no-underline text-black">
                Neue Rechnung
            </a>
        </div>

        <div class=" flex-1 mx-1 my-1 px-6 py-4 border hover:bg-grey-light">
            <div class="text-lg">
                <div class="text-grey">
                    {{ $invoice->date->format('d.m.Y') }}
                </div>

                <div class="text-black">
                    &euro; {{ $invoice->money }}
                    <a class="text-black" href="/invoices/{{ $invoice->id}}/edit">Bearbeiten</a>
                </div>

                <div class="text-sm text-grey">
                    {{ $invoice->category->title }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
