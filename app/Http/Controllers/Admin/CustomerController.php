<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    protected function filteredCustomersQuery() {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total');

        if( request()->filled('search') ) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            });
        }

        if( request('status') === 'active' ) {
            $query->where('status', 1);
        } elseif( request('status') === 'blocked' ) {
            $query->where('status', 0);
        }

        match (request('sort')) {
            'oldest' => $query->oldest(),
            'spent' => $query->orderByDesc('orders_sum_total'),
            default => $query->latest(),
        };

        return $query;
    }

    public function index() {
        $customers = $this->filteredCustomersQuery()->paginate(12)->withQueryString();

        return view('admin.customers', compact('customers'));
    }

    public function export() {
        $customers = $this->filteredCustomersQuery()->get();

        $filename = 'customers-' . now()->format('Y-m-d-His') . '.csv';

        $columns = ['ID', 'Name', 'Email', 'Orders', 'Total Spent', 'Status', 'Joined'];

        $callback = function () use ($customers, $columns) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $columns, escape: '\\');

            foreach( $customers as $customer ) {
                fputcsv($handle, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->orders_count,
                    number_format($customer->orders_sum_total ?? 0, 2),
                    $customer->status ? 'Active' : 'Blocked',
                    $customer->created_at->format('Y-m-d'),
                ], escape: '\\');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function show( User $customer ) {
        abort_unless($customer->role === 'customer', 404);

        $orders = $customer->orders()->latest()->paginate(10);

        return view('admin.customer-details', compact('customer', 'orders'));
    }
}
