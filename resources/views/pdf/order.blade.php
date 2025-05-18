@extends('layouts.pdf', ['title' => 'Order Invoice'])

@section('content')
    <table>
        <tr>
            <th style="width: 25%;"></th>
            <th colspan="2" style="border-bottom: 1px solid black; width: 25%;">
                <h2 class="m-0">Order Invoice</h2>
            </th>
            <th style="width: 25%;"></th>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td style="padding: 0;">
                <p class="m-0"><b>Issued To:</b></p>
            </td>
            <td style="padding: 0;"></td>
            <td style="padding: 0;">
                <p class="m-0" style="text-align: right;"><b>Reference No:</b></p>
            </td>
            <td style="padding: 0;">
                <p class="m-0" style="padding-left: 10px;"><b>{{ $order->reference ?? '' }}</b></p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0;">
                <p class="m-0" style="padding-left: 10px;">{{ sprintf('%s %s', !empty($order->name) ? $order->name : ($order->user->profile->name ?? ''), !empty($order->surname) ? $order->surname : ($order->user->profile->surname ?? '')) }}</p>
            </td>
            <td style="padding: 0;"></td>
            <td style="padding: 0;">
                <p class="m-0" style="text-align: right;">Date:</p>
            </td>
            <td style="padding: 0;">
                <p class="m-0" style="padding-left: 10px;">{{ $order->updated_at->format('Y.m.d') }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0;">
                <p class="m-0" style="padding-left: 10px;">{{ $order->street .', '. $order->city .', '. $order->province .', '. $order->region }}</p>
            </td>
            <td colspan="2" style="padding: 0;"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        @if (!empty($order->payment_type) || !empty($order->payment_reference))
            <tr>
                <td style="padding: 0;"><b>Payment Information:</b></td>
                <td colspan="3" style="padding: 0;"></td>
            </tr>
            @if (!empty($order->payment_type))
                <tr>
                    <td style="padding: 0;">
                        <p class="m-0" style="padding-left: 10px;">{{ $order->payment_type ?? '' }}</p>
                    </td>
                    <td colspan="3" style="padding: 0;"></td>
                </tr>
            @endif
            @if (!empty($order->payment_reference))
                <tr>
                    <td style="padding: 0;">
                        <p class="m-0" style="padding-left: 10px;">Reference: {{ $order->payment_reference ?? '' }}</p>
                    </td>
                    <td colspan="3" style="padding: 0;" ></td>
                </tr>
            @endif
            <tr>
                <td colspan="4"></td>
            </tr>
        @endif
        <tr style="border-bottom: 1px solid black">
            <td style="padding-left: 0"><h3 class="m-0">Description</h3></td>
            <td style="padding-left: 0"><h3 class="m-0">Unit Price</h3></td>
            <td style="padding-left: 0"><h3 class="m-0">Quantity</h3></td>
            <td style="padding-left: 0"><h3 class="m-0">Total</h3></td>
        </tr>
        <tr>
            <td>
                <h3 class="m-0">{{ $order->product->name ?? '' }}</h3>
                <div style="padding-left: 30px;">
                    @if ($order->product->is_customize)
                        <p class="m-0"><b>Raw Materials:</b></p>
                        <ul style="padding-left: 20px;">
                            @foreach ($order->product->raw_materials as $material)
                                <li>({{ $material->count }}) {{ $material->material->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if (!empty($order->size))
                        <p class="m-0"><b>Size:</b></p>
                        <p class="m-0" style="padding-left: 10px;">{{ $order->size ?? '' }}</p>
                    @endif
                    @if ($order->product->is_customize)
                        <p class="m-0"><b>Thickness:</b></p>
                        <p class="m-0" style="padding-left: 10px;">{{ $order->thickness ?? '' }}</p>
                        @if (!empty($order->note))
                            <p class="m-0"><b>Note:</b></p>
                            <p class="m-0" style="padding-left: 10px;">{{ $order->note}}</p>
                        @endif
                    @endif
                </div>
            </td>
            <td>
                <p class="m-0">₱ {{ number_format($order->product->price ?? '', 2) }}</p>
            </td>
            <td>
                <p class="m-0">{{ number_format($order->quantity) }} pcs</p>
            </td>
            <td>
                <p class="m-0">₱ {{ number_format($order->total ?? '', 2) }}</p>
            </td>
        </tr>
        <tr style="border-top: 1px solid black">
            <td colspan="2"></td>
            <td style="border-bottom: 1px solid black">
                <h3 class="m-0"  style="text-align: right;">
                    <b>Total</b>
                </h3>
            </td>
            <td style="border-bottom: 1px solid black">
                <h3 class="m-0">₱ {{ number_format($order->total ?? '', 2) }}</h3>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 1px;"></td>
            <td style="border-bottom: 1px solid black; padding: 1px;"></td>
            <td style="border-bottom: 1px solid black; padding: 1px;"></td>
        </tr>
    </table>
@endsection