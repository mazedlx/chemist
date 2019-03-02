@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <form action="/invoices/{{ $invoice->id }}" method="POST" class="w-1/2 mx-auto">
        @method('PATCH')
        @csrf

        <div class="flex flex-wrap mb-6">
            <label for="date" class="block text-grey-darker text-sm font-bold mb-2">
                {{ __('Für') }}:
            </label>

            <select
                id="category_id"
                type="category_id"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('category_id') ? ' border-red' : '' }}"
                name="category_id"
            >
            @foreach($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ $category->id === $invoice->category_id ? 'selected' : ''}}
                >
                    {{ $category->title}}
                </option>
            @endforeach
            </select>

            @if ($errors->has('category_id'))
                <p class="text-red text-xs italic mt-4">
                    {{ $errors->first('category_id') }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap mb-6">
            <label for="date" class="block text-grey-darker text-sm font-bold mb-2">
                {{ __('Datum') }}:
            </label>

            <input
                id="date"
                type="date"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('date') ? ' border-red' : '' }}"
                name="date"
                value="{{ old('date') ?? $invoice->date->format('Y-m-d') }}"
                required
                autofocus
            >

            @if ($errors->has('date'))
                <p class="text-red text-xs italic mt-4">
                    {{ $errors->first('date') }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap mb-6">
            <label for="amount" class="block text-grey-darker text-sm font-bold mb-2">
                {{ __('Betrag') }}:
            </label>

            <input
                id="amount"
                type="number"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('amount') ? ' border-red' : '' }}"
                name="amount"
                value="{{ old('amount') ?? $invoice->amount }}"
                required
            >

            @if ($errors->has('amount'))
                <p class="text-red text-xs italic mt-4">
                    {{ $errors->first('amount') }}
                </p>
            @endif
        </div>

         <div class="flex flex-wrap">
            <button
                type="submit"
                class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal no-underline text-blue-lightest bg-blue hover:bg-blue-light"
            >
                {{ __('Änderungen speichern') }}
            </button>
        </div>
    </form>
</div>
@endsection
