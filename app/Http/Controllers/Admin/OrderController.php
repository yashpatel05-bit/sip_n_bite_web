<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DeliveryPerson;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'deliveryPerson', 'items.menuItem', 'address']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('order_status', $request->status);
        }

        $orders = $query->latest()->get();
        $deliveryPersons = DeliveryPerson::all();

        return view('admin.orders.index', compact('orders', 'deliveryPersons'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'deliveryPerson', 'items', 'address', 'payment', 'feedback'])->findOrFail($id);
        $deliveryPersons = DeliveryPerson::all();
        return view('admin.orders.show', compact('order', 'deliveryPersons'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'order_status' => 'required|in:Pending,Preparing,Out for Delivery,Delivered,Cancelled',
        ]);

        $order->order_status = $request->order_status;
        
        if ($request->has('delivery_person_id')) {
            $order->delivery_person_id = $request->delivery_person_id;
            
            // If assigned and status is Out for Delivery, update delivery person status
            if ($request->delivery_person_id) {
                $dp = DeliveryPerson::find($request->delivery_person_id);
                if ($dp) {
                    $dp->status = ($request->order_status === 'Delivered' || $request->order_status === 'Cancelled') 
                        ? 'available' 
                        : 'on_delivery';
                    $dp->save();
                }
            }
        }

        if ($request->order_status === 'Delivered' && $order->payment_method === 'COD') {
            $order->payment_status = 'paid';
        }

        $order->save();

        return back()->with('success', 'Order status updated successfully!');
    }
}
