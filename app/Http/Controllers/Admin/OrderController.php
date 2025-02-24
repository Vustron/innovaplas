<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $products = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                             ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                             ->leftJoin('users as u', 'u.id', 'orders.user_id')
                             ->leftJoin('profiles as pf', 'pf.user_id', 'u.id')
                             ->where(function ($query) use ($status) {
                                if (!empty($status)) {
                                    $query->where('os.name', str_replace('-', ' ', $status));
                                }
                             })
                             ->select([
                                'orders.*',
                                'p.name as product_name',
                                'os.name as status',
                                DB::raw("CONCAT(pf.name, ' ', pf.surname) as full_name")
                             ]);

            $datatables = datatables()::of($products);

            $datatables->addColumn('actions', function ($item) {
                $view_route = route('admin.order.show', $item->id);
                return "<a href='$view_route' class='text-muted me-3 btn-view text-nowrap'><i class='fa-regular fa-eye me-2'></i>View</a>";
            })->editColumn('total', function ($item) {
                return '₱ ' . number_format($item->total, 2);
            })->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where(function ($sql) use ($keyword) {
                        $sql->where(DB::raw("CONCAT(YEAR(orders.created_at), '-', LPAD(orders.id, 6, '0'))"), 'LIKE', "%$keyword%")
                            ->orWhere('p.name', 'LIKE', "%$keyword%")
                            ->orWhere('orders.quantity', 'LIKE', "%$keyword%")
                            ->orWhere('os.name', 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(orders.total, 2))"), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('CONCAT(pf.name, " ", pf.surname)'), 'LIKE', "%$keyword%")
                            ->orWhere(DB::raw('DATE_FORMAT(orders.created_at, "%b %d, %Y")'), 'LIKE', "%$keyword%");
                    });
                });
            }
            
            return $datatables->rawColumns(['actions'])->make(true);
        }
        
        return view('admin.orders.index', compact('status'));
    }

    public function show($id)
    {
        $order = Order::with(['product' => function ($query) {
                $query->withTrashed();    
            }, 'status', 'user'])->find($id);
        if (empty($order)) {
            abort(404);
        }

        return view('admin.orders.show', compact('order'));
    }

    public function changeStatus(Request $request, $status_key, $id)
    {
        $status = OrderStatus::where('name', str_replace('-', ' ', $status_key))->first();
        $order = Order::find($id);
        if (empty($order) || empty($status)) {
            abort(404);
        }

        if ($status->name == 'On Process') {
            $product = $order->product;
            if (!$product->is_customize) {
                $product->quantity -= $order->quantity;
                $product->save();
            } else {
                foreach ($product->raw_materials as $material) {
                    $raw_material = $material->material;
                    $raw_material->quantity -= ($material->count * $order->quantity);
                    $raw_material->save();
                }
            }

            if ($product->quantity < 100) {
                // DB Notification
                $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
                $message = "The $product->name is low on stock.";
                $link = route('admin.products.list') . '#btn-edit-'. $product->id;
    
                Notification::send(
                    $users, 
                    new DefaultNotification($message, $link)
                );
            }
        }

        if (!empty($request->input('rejection_message'))) {
            $order->rejection_message = $request->input('rejection_message');
        }

        $order->order_status_id = $status->id;
        $order->save();

        // DB Notification
        $message = "The admin has change your order's status.";
        switch ($status_key) {
            case 'to-pay':
                $message = "A payment is required to your order.";
                break;
            case 'rejected':
                $message = "Your order has been rejected.";
                break;
            case 'on-process':
                $message = "Your order is now processing.";
                break;
            case 'to-deliver':
                $message = "Your order has been shipped.";
                break;
        }
        $link = route('user.order.show', $order->id);

        Notification::send(
            $order->user, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Order status change to '. $status->name);
    }
}
