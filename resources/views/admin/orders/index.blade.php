@extends('layouts.admin')

@section('title', 'Order Management — Sip N Bite Admin')
@section('page_header', 'Order Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm rounded-pill {{ !request('status') || request('status') == 'all' ? 'btn-danger' : 'btn-outline-secondary' }}">All Orders</a>
        <a href="{{ route('admin.orders.index', ['status' => 'Pending']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'Pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">Pending</a>
        <a href="{{ route('admin.orders.index', ['status' => 'Preparing']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'Preparing' ? 'btn-info text-white' : 'btn-outline-info' }}">Preparing</a>
        <a href="{{ route('admin.orders.index', ['status' => 'Out for Delivery']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'Out for Delivery' ? 'btn-primary' : 'btn-outline-primary' }}">Out for Delivery</a>
        <a href="{{ route('admin.orders.index', ['status' => 'Delivered']) }}" class="btn btn-sm rounded-pill {{ request('status') == 'Delivered' ? 'btn-success' : 'btn-outline-success' }}">Delivered</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Assigned Staff</th>
                        <th class="pe-4 text-end">Update / Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $ord)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $ord->order_number }}</td>
                            <td>
                                <div>
                                    <strong class="text-dark d-block">{{ $ord->user ? $ord->user->name : 'Customer' }}</strong>
                                    <span class="small text-secondary">{{ $ord->user ? $ord->user->phone : '' }}</span>
                                </div>
                            </td>
                            <td class="fw-bold text-danger">₹{{ number_format($ord->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-light text-dark border me-1">{{ $ord->payment_method }}</span>
                                <span class="badge bg-{{ $ord->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $ord->payment_status }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $ord->order_status === 'Delivered' ? 'success' : ($ord->order_status === 'Cancelled' ? 'danger' : 'warning') }} text-uppercase px-3 py-1">
                                    {{ $ord->order_status }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.update-status', $ord->id) }}" method="POST" id="dp_form_{{ $ord->id }}">
                                    @csrf
                                    <input type="hidden" name="order_status" value="{{ $ord->order_status }}">
                                    <select name="delivery_person_id" class="form-select form-select-sm rounded-pill border-secondary" style="min-width: 140px;" onchange="this.form.submit()">
                                        <option value="">Unassigned</option>
                                        @foreach($deliveryPersons as $dp)
                                            <option value="{{ $dp->id }}" {{ $ord->delivery_person_id == $dp->id ? 'selected' : '' }}>
                                                {{ $dp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.orders.update-status', $ord->id) }}" method="POST" class="d-inline-flex gap-1 align-items-center">
                                    @csrf
                                    @if($ord->delivery_person_id)
                                        <input type="hidden" name="delivery_person_id" value="{{ $ord->delivery_person_id }}">
                                    @endif
                                    <select name="order_status" class="form-select form-select-sm rounded-pill" style="width: 130px;" onchange="this.form.submit()">
                                        <option value="Pending" {{ $ord->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Preparing" {{ $ord->order_status == 'Preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="Out for Delivery" {{ $ord->order_status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                        <option value="Delivered" {{ $ord->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Cancelled" {{ $ord->order_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-light text-danger rounded-circle ms-1" title="View Details & Assign"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('admin.invoices.show', $ord->id) }}" class="btn btn-sm btn-light text-dark rounded-circle" target="_blank" title="Invoice"><i class="fa-solid fa-file-invoice"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
