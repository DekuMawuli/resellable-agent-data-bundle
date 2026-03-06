<?php

namespace App\Exports;

use App\Http\Customs\CustomHelper;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'Bundle',
            'Network',
            'Phone Number',

        ];
    }
    public function collection()
    {
        $items = [];
        $orders = Order::query()
            ->with(["user", "product", "product.category"])
            ->where("paymentMade", "=", 'Y')
            ->where("status", "=", "processing")
            ->orderByDesc("created_at")
            ->get();
        foreach ($orders as $order){
            $items[] =[
                "Bundle" => CustomHelper::extractDigits($order->product->name),
                "Network" => $order->product->category->name,
                "Phone Number" => $order->phoneNumber,
            ];
            $order->status = "completed";
            $order->save();
        }


        $data = new Collection($items);
        // dd($data);

        return $data;
    }
}
