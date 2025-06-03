<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\OrderInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $order;
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    public function save(Request $request)
    {
        return $this->order->saveOrder($request);
    }
    public function index()
    {
        return view('backend.order.index');
    }
    public function getOrders()
    {
        return $this->order->getAllOrders();
    }

    public function detailOrder($id)
    {
         $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.show')->with(compact('data'));

    }
    public function getOrder($id)
    {
         return $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.show')->with(compact('data'));

    }

    public function printOrder($id)
    {
        $data['invoice'] = $this->order->getOrder($id);
        return view('backend.order.order-print')->with(compact('data'));
    }

    public function getCustomerOrders(Request $request)
    {
        return $this->order->getCustomerOrder($request->customer_id);
    }
    public function getOrderProducts(Request $request)
    {
        return $this->order->getOrderProducts($request->order_id);
    }

}
