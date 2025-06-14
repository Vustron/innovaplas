<table>
    <thead>
        <tr>
            <th style="border: 1px solid black; background-color: #acacff; font-weight: 800; text-align: center;" colspan="7">Innovaplas Packaging Corporation</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid black; font-weight: 800;">Exported By</th>
            <td style="border: 1px solid black;">{{ !empty(Auth::user()->profile) ? (Auth::user()->profile->name ." ". Auth::user()->profile->surname) : Auth::user()->email }}</td>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid black; font-weight: 800;">Current Date</th>
            <td style="border: 1px solid black;">{{ now()->format('M d, Y') }}</td>
            <th></th>
            <th style="border: 1px solid black; font-weight: 800;">Product Type</th>
            <td style="border: 1px solid black;">{{ ucwords($sales_type ?? '') }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid black; font-weight: 800;">Filtered Date</th>
            <td style="border: 1px solid black;">{{ $date ?? '' }}</td>
            <th></th>
            <th style="border: 1px solid black; font-weight: 800;">Payment Type</th>
            <td style="border: 1px solid black;">{{ ucwords($payment_type ?? '') }}</td>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Completed At</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Product Name</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Thickness</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Size</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Quantity</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Price</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($orders as $key => $order)
            <tr>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->date_complete ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->name ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->thickness ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->size ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ !empty($order->quantity) ? number_format($order->quantity) . ' pcs' : '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->price ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $order->total ?? '' }}</td>
            </tr>

            @php
                $total += (float) str_replace(['₱ ', ','], '', $order->total);
            @endphp

            @if ($loop->last)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total:</td>
                    <td>₱ {{ number_format($total, 2) }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

@include('components.export-signature', ['column' => 7])