<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $buyer_email = $request->get('buyer_email');
        $status = $request->get('status');

        $orders = Order::with(['user', 'books']);

        if ($buyer_email) {
            $orders->whereHas('user', function ($query) use ($buyer_email) {
               $query->where("email", "LIKE", "%$buyer_email%");
            });
        }

        if ($status) {
            $orders->where('status', $status);
        }

        $orders = $orders->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);

        return view('orders.edit', compact('order'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $update_order = $request->only('status');

            $order = Order::find($id);
            $order->fill($update_order);
            $order->save();

            return redirect()->route('orders.edit', $id)->with('status', 'Order status successfully updated');

        } catch (\Exception $error) {
            return redirect()->route('orders.edit', $id)->with('status', $error->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
