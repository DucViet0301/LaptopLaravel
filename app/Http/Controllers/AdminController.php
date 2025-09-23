<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    //
    public function category(){
        $data = Category::all();
        return view('admin.category', compact('data'));
    }
    public function add_category(Request $request){
        $data=  new Category;
        $data->category_name = $request->category_name;
        $data->save();
        return redirect()->back()->with('message','You Are Add Category Successfull');
    }
    public function delete_category($id){
        $data = Category::find($id);
        $data->delete();
        return redirect()->back()->with('message','You Are Delete Category Successfull');
    }
    public function view_product(){
        $category = Category::all();
        return view('admin.product',compact('category'));
    }
    public function add_product(Request $request)
{
    $data = new Product;
    $data->title = $request->title;
    $data->description = $request->description;
    $data->category = $request->category;
    $data->quantity = $request->quantity;
    $data->price = $request->price;
    $data->discount_price = $request->dis_price;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('product'), $imagename); 
        $data->image = $imagename;
    }

    $data->save();

    return redirect()->back()->with('message','Product Added Successfully');
}

    public function show_product(){
        $product = Product::all();
        return view('admin.show_product', compact('product'));
    }
    public function delete_product($id){
        $product = Product::find($id);
        $product->delete();
        return redirect()->back()->with('message','You Are Delete Product Successfull');
    }
    public function update_product($id){
        $category = Category::all();
        $product = Product::find($id);
        return view('admin.update',compact('product','category'));
    }
    public function update_confirm_product($id, Request $request){
    $product = Product::find($id);
    $product->title = $request->title;
    $product->description= $request->description;
    $product->category = $request->category;
    $product->price = $request->price;
    $product->discount_price = $request->dis_price;

    if($request->hasFile('image')){
        $image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $image->move('product', $imagename);
        $product->image = $imagename;
    }
    $product->save();
    return redirect()->route('admin.show_product')
        ->with('message', 'Update Product Successful');
    }
    public function order(){
        $order = Order::all();
        return view('admin.order', compact('order'));
    }
    public function delivered($id){
        $order = Order::find($id);
        $order->delivery_status = 'delivered';
        $order->save();
        return redirect()->back();
    }
    public function print($id){
        $order = Order::find($id);
        $pdf = Pdf::loadview('admin.print',compact('order'));
        return $pdf->download('order_detail.pdft');
    }
    public function search(Request $request){
        $searchtext = $request->search;
        $order = Order::where('product_title','LIKE',"%$searchtext%")->get();
        return view('admin.order',compact('order'));
    }

}
