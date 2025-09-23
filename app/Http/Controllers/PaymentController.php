<?php
// ============================================
// FILE: app/Http/Controllers/PaymentController.php
// ============================================

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    
    public function pay_cart($totalprice)
   {
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $provider->getAccessToken();
    $data = [
            "intent" => "CAPTURE",
            "application_context" =>[
                "return_url" => route("paypal.success"),
                "cancel_url" => route("paypal.cancel")
            ],
            "purchase_units" => [
              [
                "amount" => [
                    "currency_code" => "USD",
                "value" => $totalprice
                ]         
             ]
        ]
                ];
        $order = $provider->createOrder($data);
        $approvelink = collect($order['links'] ?? [])
        ->where("rel", "approve")
        ->first()['href'] ?? null;
        if($approvelink){
            return redirect()->away($approvelink);
        }else{
            return redirect()->back();
        }
    }

    public function success(Request $request)
    {
       $token = $request->query('token');
       $provider = new PayPalClient;
       $provider->setApiCredentials(config('paypal'));
       $provider->getAccessToken();

       $order = $provider->capturePaymentOrder(($token));
       if(isset($order['status']) && $order['status'] === 'COMPLETED'){
            $user = Auth::user();
            $cartItems = Cart::where('user_id', $user->id)->get();

            foreach($cartItems as $item){
                $order = new Order;
                $order->name = $user->name;
                $order->email = $user->email;
                $order->phone = $user->phone;
                $order->address = $user->address;
                $order->user_id = $user->id;
                $order->product_title = $item->product_title;
                $order->price = $item->price;
                $order->quantity = $item->quantity;
                $order->product_id = $item->product_id;

                $order->payment_status = 'paid';
                $order->delivery_status = 'processing';
                $order->save();
                $cart_id = $item->id;
                $cart= Cart::find($cart_id);
                $cart->delete();
            }

        return redirect()->route('home')->with('success', 'Thanh toán PayPal thành công!');
       }
       return redirect()->route('home')->with('error', 'Thanh toán thất bại!');
    }
    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Bạn đã hủy giao dịch PayPal.');
    }
}
