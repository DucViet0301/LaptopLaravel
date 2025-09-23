<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Reply;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class HomeController extends Controller
{
    public function index(){
        $product = Product::paginate(8);
        $comment = Comment::latest()->get();
        $reply = Reply::latest()->get();
        $userId = Auth::id();
        $total_order = Order::where('user_id',$userId)->where('delivery_status','processing')->count();
        $cart_total = Cart::where('user_id',$userId)->sum('quantity');
        return view('home.userpage',compact('product','cart_total','comment','reply','total_order'));
    }

    public function redirect(){
        $typeuser = Auth::user()->usertype;
        
        if($typeuser == '1'){
            $total_product = Product::all()->count();
            $total_order = Order::all()->count();
            $total_user = User::where('usertype','0')->count();
            $order= Order::all();
            $total_revenue = 0;
            foreach($order as $order){
                $total_revenue += $order->price;
            }
            $total_delivered = Order::where('delivery_status','=','delivered')->get()->count();
            $total_procssing = Order::where('delivery_status','=','processing')->get()->count();
            return view('admin.home', compact('total_product','total_order','total_user','total_revenue','total_delivered','total_procssing'));
        }
        else{
            $userId = Auth::id();
            $cart_total = Cart::where('user_id',$userId)->sum('quantity');
            $total_order = Order::where('user_id',$userId)->where('delivery_status','processing')->count();
            $product = Product::paginate(8);
            $comment = Comment::latest()->get();
            $reply = Reply::latest()->get();
            return view('home.userpage',compact('product','cart_total','comment','reply','total_order'));
        }
    }
    public function googlepage(){
        return Socialite::driver('google')->redirect();
    }
    public function googlecallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'usertype' => 0,
                    'phone' => null,
                    'address' => 'Việt Nam',
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(16)),
                ]);
            }
            Auth::login($user);

            return redirect('redirect');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi đăng nhập bằng Google!');
        }
    }

    public function detail_product($id)
    {
        $product = Product::find($id);
        $cart_quantity = Cart::where('product_id', $id)->sum('quantity');
        // $total_quantity = $product->quantity - $cart_quantity;
        // $product->quantity = $total_quantity;
        // $product->save();
        if(Auth::check()) {
            $userId = Auth::id();
            $cart_total = Cart::where('user_id', $userId)->sum('quantity');
            $total_order = Order::where('user_id',$userId)->where('delivery_status','processing')->count();
        } else {
            $cart_total = 0;
        }
        

        
        return view('home.detail_product', compact('product', 'cart_total','total_order'));

    }

    public function add_cart(Request $request, $id){
        if(Auth::id()){
            $user  = Auth:: user();
            $product = Product::find($id);

            $cart = Cart::where('product_id', $id)
            ->where('user_id', $user->id)
            ->first();
            if($cart != null){
                $cart->quantity += $request->quantity;
                if($product->discount_price != null){
                    $cart->price = $product->discount_price * $cart->quantity;
                }
                else{
                    $cart->price = $product->price * $cart->quantity;
                }
            }
            else{
                $cart = new Cart;
                $cart->name = $user->name;
                $cart->email = $user->email;
                $cart->phone = $user->phone;
                $cart->address = $user->address;
                $cart->user_id = $user->id;
                $cart->product_title = $product->title;
                $cart->quantity = $request->quantity;
                if($product->discount_price != null){
                    $cart->price = $product->discount_price;
                }
                else{
                    $cart->price = $product->price;
                }
                $cart->image = $product->image;
                $cart->product_id = $product->id; 
            }
            // $product->quantity -= $request->quantity;
            // $product->save();
            
            $cart->save();
            if($request->ajax()){
                $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
                return response()->json([
                    'success' => true,
                    'message'=>"Đã thêm vao giỏ hàng thành công!",
                    'cartCount' => $cartCount
                ]);
            }
            return redirect()->back();
        }

        else{
            if($request->ajax()){
                return response()->json([
                    'success' => false,
                    'redirect' => url('login')
                ],401);
            }
            return redirect('login');
        }
    }
    public function show_cart(){
        if(Auth::id()){
            $id = Auth::user()->id;
            $cart_total = Cart::where('user_id',$id)->sum('quantity');
            $total_order = Order::where('user_id',$id)->where('delivery_status','processing')->count();
            $cart = Cart::where('user_id',$id)->get();
            return  view('home.show_cart',compact('cart','cart_total','total_order'));
        }
        else{
            return redirect('login');
        }
    }
    public function remove_cart($id,Request $request){
        $cart = Cart::find($id);
        $user = Auth::user();
        $cart->delete();
        if($request->ajax()){
            $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
            return response()->json([
                'status' => 'success',
                'id' => $id,
                'cartCount' => $cartCount
            ]);
        };
        return redirect()->back()->with('message', 'You Are Remove Product Successfull');
    }
    public function cash_order(){
        $userid = Auth::user()->id;
        $cartItem = Cart::where('user_id',$userid)->get();

        foreach($cartItem as $item){
            $order = new Order;  
            $order->name = $item->name;
            $order->email = $item->email;
            $order->phone = $item->phone;
            $order->address = $item->address;
            $order->user_id = $item->user_id;

            $order->product_title = $item->product_title;
            $order->quantity = $item->quantity;
            $order->price = $item->price;
            $order->image = $item->image;
            $order->product_id = $item->product_id;

            $order->delivery_status = 'processing';
            $order->payment_status = 'cash on delivery';
            $order->save();
            $product = Product::find($item->product_id);
            if ($product) {
                if ($product->quantity >= $item->quantity) {
                    $product->quantity -= $item->quantity;
                } else { 
                    $product->quantity = 0;
                }
                $product->save();
            }
        }
        

        Cart::where('user_id',$userid)->delete();
        return redirect()->back();
    }
    
    public function add_comment(Request $request){
        if(Auth::id()){
        $comment = new Comment;
        $user = Auth::user();
        $comment->name = $user->name;
        $comment->user_id = $user->id;
        $comment->comment = $request->comment; 
        $comment->save();
         if($request->ajax()){
            return response()->json([
                'status' => 'success',
                'id' => $comment->id,
                'comment' => $comment,
                'user'=> $user->name
            ]);
         }
        return redirect()->back();
        }
        else{
            return redirect('login');
        }
    }
    public function add_reply(Request $request){
        if(Auth::id()){
          $reply = new Reply;
          $user = Auth::user();
          $reply->name = $user->name;
          $reply->user_id = $user->id;
          $reply->comment_id = $request->commentId;
          $reply->reply = $request->reply;
          $reply->save();
          if($request->ajax()){
            return response()->json([
                'status'=>'success',
                'id' =>$reply->id,
                'reply' =>$reply,
                'user' =>$user->name 
            ]);
          }
          return redirect()->back();
        }
        else{
            return redirect('login');

        }
    }
    public function delete_comment($id,Request $request){
        $comment = Comment::find($id);
        if($comment){
            Reply::where('comment_id',$comment->id)->delete();
            $comment->delete();
            if($request->ajax()){
                return response()->json([
                    'status' => 'success',
                    'id' => $id
                ]);
            }
        }
        return redirect()->back();
    }
    public function delete_reply($id,Request $request){
        $reply = Reply::find($id);
        if($reply){
           $reply->delete();
           if($request->ajax()){
                return response()->json([
                    'status' => 'success',
                    'id' => $id
                ]);
            }
        }
        
        return redirect()->back();
    }
    public function update_reply(Request $request, $id){
        $update = Reply::find($id);
        $update->reply = $request->reply_change;
        $update->save();
        return redirect()->back();
    }
    public function update_comment(Request $request, $id){
        $update = Comment::find($id);
        $update->comment = $request->comment_change;
        $update->save();
        return redirect()->back();
    }
    public function show_order(){
        if(Auth::id()){

            $user = Auth::user();
            $userId = $user->id;
            $cart_total = Cart::where('user_id',$userId)->sum('quantity');
            $order = Order::where('user_id','=',$userId)->get();
            $total_order = Order::where('user_id',$userId)->where('delivery_status','processing')->count();
            return view('home.order',compact('order','cart_total','total_order'));

        }
        else{
            return redirect('login');
        }
    }
    public function cancel_order($id, Request $request){
        $order = Order::find($id);
        if($order){
         $order->delivery_status= 'cancelled';
        $order->save();   
        }
        $user = Auth::user();
        if($request->ajax()){
            $total_order = Order::where('user_id',$user->id)->where('delivery_status','processing')->count();
            return response()->json([
                'status' => 'success',
                'id' => $id,
                'total_order' => $total_order
            ]);
        }
        return redirect()->back();
    }
    public function search_product(Request $request){
        $search_product = $request->search;
        $userId = Auth::user()->id;
        $comment = Comment::latest()->get();
        $reply = Reply::latest()->get();
        $total_order = Order::where('user_id',$userId)->where('delivery_status','processing')->count();
        $cart_total = Cart::where('user_id',$userId)->sum('quantity');
        $product = Product::where('Category','LIKE',"%$search_product%")->paginate(8);
        if($request->ajax()){
            $html = view('home.product_list', compact('product'))->render();
            return response()->json([
                'status' => 'success',
                'html' => $html,
            ]);
        }
         return view('home.userpage',compact('product','comment','reply','total_order','cart_total'));
    }

}