<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="/public">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    @include('admin.css')

<style>
 .div_center{
  text-align: center;
  padding-top: 40px;
 }
 .div_center h1{
  font-weight: bold;
  font-size: 40px;
 }
 form{
  margin-top: 20px;
 }
 label{
  display: inline-block;
  width: 200px;
 }
 .image{
  margin-left: 150px;
 }
 .btn-primary{
  width: 120px;
  height: 40px;
 }
 .size{
  width: 196.4px;
  height: 41.6px;
 }
 

</style>
  </head>
  <body>
    <div class="container-scroller">

      @include('admin.sidebar')
      <div class="container-fluid page-body-wrapper">
      @include('admin.header')
      
      <div class="main-panel">
          <div class="content-wrapper">
            @if(session()->has('message'))
                <div class="alert alert-success ">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="div_center">
              <h1>Update Product</h1>
              <form action="{{ url('update_comfirm_product',$product->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="pb-2">
                <label>Product Title :</label>
                <input class="text-black" type="text" name="title" value="{{ $product->title }}" required="">
              </div>

              <div class="pb-2">
                <label>Product Description :</label>
                <input class="text-black " type="text" name="description" value="{{ $product->description }}"required="">
              </div>

              <div class="pb-2">
                <label>Product Price :</label>
                <input class="text-black" type="number" name="price" value="{{ $product->price}}" required="">
              </div>

              <div class="pb-2">
                <label>Discout Price :</label>
                <input class="text-black" type="number" name="dis_price" value="{{ $product->discount_price }}" >
              </div>

              <div class="pb-2">
                <label>Product Quantity :</label>
                <input class="text-black" type="text" name="quantity" min="0" value="{{ $product->quantity }}" required="">
              </div>

              <div class="pb-2">
                <label>Product Category :</label>
                <select class="text-black size" name="category" >
                  <option  value="{{ $product->category }}">{{ $product->category }}</option>
                  @foreach ($category as $item )
                    <option value="{{ $item->category_name }}" >
                      {{ $item->category_name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="d-flex justify-content-center pb-2 align-items-center">
                  <label class="me-3 col-form-label">Current Product Image :</label>
                  <img src="/product/{{ $product->image }}" width="100" height="100" class="img-thumbnail">
              </div>


               <div class="pb-2 d-inline" >
                   <label>Product Image Here :</label>
                  <input type="file" name="image"  value="{{ $product->image }}" > 
               </div>


              <div class="pt-3 text-center">
                  <button type="submit" class="btn btn-primary">Update Product</button>
              </div>


            </form>
            </div>
            
          </div>
      </div>

    </div>
    @include('admin.js')
  </body>
</html>