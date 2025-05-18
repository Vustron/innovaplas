@extends('layouts.pdf', ['title' => 'Order Details'])

@section('content')
    <table>
        <tr>
            <th colspan="2">Product Information</th>
        </tr>
        <tr>
            <td style="width: 100%;">
                <p class="m-0"><b>Name:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ $order->product->name ?? '' }}</p>
            </td>
            <td style="width: 100%;">
                <p class="m-0"><b>Description:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ $order->product->description ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <p class="m-0"><b>Price:</b></p>
                <p class="m-0" style="padding-left: 10px;">₱ {{ number_format($order->product->price ?? '', 2) }}</p>
            </td>
            @if ($order->product->is_customize)
                <td style="width: 100%;">
                    <p class="m-0"><b>Raw Materials:</b></p>
                    <ul style="padding-left: 20px;">
                        @foreach ($order->product->raw_materials as $material)
                            <li>({{ $material->count }}) {{ $material->material->name }}</li>
                        @endforeach
                    </ul>
                </td>
            @endif
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2">Order Information</th>
        </tr>
        <tr>
            <td colspan="2">
                <p class="m-0"><b>Order Reference:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ $order->reference ?? '' }}</p>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <p class="m-0"><b>Customer Name:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ sprintf('%s %s', !empty($order->name) ? $order->name : ($order->user->profile->name ?? ''), !empty($order->surname) ? $order->surname : ($order->user->profile->surname ?? '')) }}</p>
            </td>
            <td style="width: 100%;">
                <p class="m-0"><b>Delivery Address:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ $order->street .', '. $order->city .', '. $order->province .', '. $order->region }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="{{ !empty($order->size) ? '1' : '2' }}" style="width: 100%;">
                <p class="m-0"><b>Quantity:</b></p>
                <p class="m-0" style="padding-left: 10px;">{{ number_format($order->quantity) }} pcs</p>
            </td>
            @if (!empty($order->size))
                <td style="width: 100%;">
                    <p class="m-0"><b>Size</b></p>
                    <p class="m-0" style="padding-left: 10px;">{{ $order->size ?? '' }}</p>
                </td>
            @endif
        </tr>
        @if ($order->product->is_customize)
            <tr>
                <td colspan="{{ !empty($order->note) ? '1' : '2' }}" style="width: 100%;">
                    <p class="m-0"><b>Thickness:</b></p>
                    <p class="m-0" style="padding-left: 10px;">{{ $order->thickness ?? '' }}</p>
                </td>
                @if (!empty($order->note))
                    <td style="width: 100%;">
                        <p class="m-0"><b>Note:</b></p>
                        <p class="m-0" style="padding-left: 10px;">{{ $order->note}}</p>
                    </td>
                @endif
            </tr>
        @endif
    </table>
    
    @if (!empty($order->payment_reference) || !empty($order->payment_type))
        <table>
            <tr>
                <th colspan="2">Payment Information</th>
            </tr>
            <tr>
                @if (!empty($order->payment_reference))
                    <td colspan="{{ !empty($order->payment_type) ? '1' : '2' }}" style="width: 100%;">
                        <p class="m-0"><b>Reference No:</b></p>
                        <p class="m-0" style="padding-left: 10px;">{{ $order->payment_reference ?? '' }}</p>
                    </td>
                @endif
                @if (!empty($order->payment_type))
                    <td colspan="{{ !empty($order->payment_reference) ? '1' : '2' }}" style="width: 100%;">
                        <p class="m-0"><b>Payment Type:</b></p>
                        <p class="m-0" style="padding-left: 10px;">{{ $order->payment_type ?? '' }}</p>
                    </td>
                @endif
            </tr>
            <tr>
                <td colspan="2" style="width: 100%;">
                    <p class="m-0"><b>Total Payment:</b></p>
                    <p class="m-0" style="padding-left: 10px;">₱ {{ number_format($order->total ?? '', 2) }}</p>
                </td>
            </tr>
        </table>
    @endif
@endsection