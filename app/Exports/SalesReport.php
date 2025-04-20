<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesReport implements FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    private $orders;
    private $sales_type;
    private $payment_type;
    private $date;

    public function __construct(
        $orders,
        $sales_type,
        $payment_type,
        $date
    ) {
        $this->orders = $orders;
        $this->sales_type = $sales_type;
        $this->payment_type = $payment_type;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('admin.reports.sales-export', [
            'orders' => $this->orders,
            'sales_type' => $this->sales_type,
            'payment_type' => $this->payment_type,
            'date' => $this->date
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
