<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected function filteredOrdersQuery() {
        $query = Order::with('user');

        if( request()->filled('search') ) {
            $query->where(function ($q) {
                $q->where('order_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('name', 'LIKE', '%' . request('search') . '%');
            });
        }

        if( request()->filled('status') ) {
            $query->where('status', request('status'));
        }

        if( request()->filled('date') ) {
            $query->whereDate('created_at', request('date'));
        }

        return $query;
    }

    public function index() {
        $orders = $this->filteredOrdersQuery()->latest()->paginate(12)->withQueryString();

        return view('admin.orders', compact('orders'));
    }

    public function export() {
        $orders = $this->filteredOrdersQuery()->latest()->get();

        $filename = 'orders-' . now()->format('Y-m-d-His') . '.csv';

        $columns = ['Order Number', 'Customer', 'Email', 'Date', 'Total', 'Payment Method', 'Status'];

        $callback = function () use ($orders, $columns) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $columns, escape: '\\');

            foreach( $orders as $order ) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->name,
                    $order->email,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->total,
                    $order->payment_method,
                    $order->status,
                ], escape: '\\');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function show( Order $order ) {
        $order->load('items.product', 'user');

        return view('admin.order-details', compact('order'));
    }

    public function updateStatus( Request $request, Order $order ) {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated!');
    }

    public function downloadInvoice( Order $order ) {
        $order->load('items');

        $pdf = Pdf::loadView('admin.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
