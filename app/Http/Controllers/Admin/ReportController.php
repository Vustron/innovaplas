<?php

namespace App\Http\Controllers\Admin;

use stdClass;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\ProductRawMaterial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];
            $start = $request->start;
            $end = $request->end;
            $sales_type = $request->sales_type;
            
            $orders = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                             ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                             ->leftJoin('users as u', 'u.id', 'orders.user_id')
                             ->leftJoin('profiles as pf', 'pf.user_id', 'u.id')
                             ->where('os.name', 'Completed')
                             ->where(function ($query) use ($start, $end) {
                                if (!empty($start)) {
                                    $query->whereDate('orders.created_at', '>=', Carbon::createFromFormat('m/d/Y', $start)->format('Y-m-d'));
                                }
                                if (!empty($end)) {
                                    $query->whereDate('orders.created_at', '<=', Carbon::createFromFormat('m/d/Y', $end)->format('Y-m-d'));
                                }
                             })
                             ->where(function ($query) use ($sales_type) {
                                if (!empty($sales_type)) {
                                    $query->where('p.is_customize', $sales_type !== 'generic');
                                }
                             })
                             ->select([
                                'orders.*',
                                'p.name as product_name',
                                'os.name as status',
                                DB::raw("CONCAT(pf.name, ' ', pf.surname) as customer")
                             ]);

            $datatables = datatables()::of($orders);

            $datatables->addColumn('total_price', function ($item) {
                return '₱ ' . number_format($item->total, 2);
            })->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where('p.name', 'LIKE', "%$keyword%")
                            ->orWhere('orders.quantity', 'LIKE', "%$keyword%")
                            ->orWhere('orders.thickness', 'LIKE', "%$keyword%")
                            ->orWhere('orders.size', 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2))"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT(pf.name, ' ', pf.surname)"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('DATE_FORMAT(orders.created_at, "%b %d, %Y")'), 'LIKE', "%$keyword%");;
                    });
                });
            }
            
            return $datatables->make(true);
        }
        
        return view('admin.reports.sales');
    }

    public function salesExport(Request $request)
    {
        $data = json_decode($request->data);
        
        $export = [];

        foreach ($data as $key => $item) {
            $export[$key]['Product Name'] = $item->product_name;
            $export[$key]['Quantity'] = $item->quantity;
            $export[$key]['Total Price'] = $item->total_price;
            $export[$key]['Thickness'] = $item->thickness ?? '';
            $export[$key]['Size'] = $item->size ?? '';
            $export[$key]['Customer'] = $item->customer ?? '';
            $export[$key]['Ordered At'] = $item->created_at ?? '';
        }

        return (new FastExcel($export))->download('sales.xlsx');
    }

    public function inventory(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $materials = RawMaterial::query();
            $datatables = datatables()::of($materials);

            $datatables->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('m/d/Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where("id", $keyword)
                        ->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('batch_number', 'like', "%$keyword%")
                        ->orWhere('quantity', 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }
        
        return view('admin.reports.inventory');
    }

    public function inventoryExport(Request $request)
    {
        $data = json_decode($request->data);
        
        $export = [];

        foreach ($data as $key => $item) {
            $export[$key]['Batch Number'] = $item->batch_number;
            $export[$key]['Material Type'] = $item->name;
            $export[$key]['Quantity'] = $item->quantity;
            $export[$key]['Last Update'] = $item->updated_at;
        }

        return (new FastExcel($export))->download('inventory.xlsx');
    }
}
