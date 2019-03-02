<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;
use App\Category;

class InvoicesController extends Controller
{
    public function index()
    {
        return view('invoices.index', [
            'invoices' => Invoice::with('category')
                ->orderBy('date', 'desc')
                ->latest()
                ->get(),
        ]);
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    public function create()
    {
        return view('invoices.create', [
            'categories' => Category::orderBy('title', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'date|date_format:Y-m-d',
        ]);

        Invoice::create([
            'category_id' => request('category_id'),
            'amount' => request('amount'),
            'date' => request('date'),
        ]);

        return redirect('/invoices');
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', [
            'categories' => Category::orderBy('title', 'asc')->get(),
            'invoice' => $invoice,
        ]);
    }

    public function update(Invoice $invoice, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $invoice->update([
            'amount' => request('amount'),
            'category_id' => request('category_id'),
            'date' => request('date'),
        ]);

        return redirect('/invoices');
    }
}
