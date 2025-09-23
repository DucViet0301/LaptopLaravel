<!DOCTYPE html>
<html lang="en">
  <head>
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
              <h1>Add Product</h1>
              <form action="{{ url('add_product') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="pb-2">
                <label>Product Title :</label>
                <input class="text-black" type="text" name="title" placeholder="Write a title" required="">
              </div>

              <div class="pb-2">
                <label>Product Description :</label>
                <input class="text-black " type="text" name="description" placeholder="Write a discription" required="">
              </div>

              <div class="pb-2">
                <label>Product Price :</label>
                <input class="text-black" type="number" name="price" placeholder="Write a price" required="">
              </div>

              <div class="pb-2">
                <label>Discout Price :</label>
                <input class="text-black" type="number" name="dis_price" placeholder="Write a discount price" >
              </div>

              <div class="pb-2">
                <label>Product Quantity :</label>
                <input class="text-black" type="text" name="quantity" min="0" placeholder="Write a quantity" required="">
              </div>

              <div class="pb-2">
                <label>Product Category :</label>
                <select class="text-black" name="category" required="">
                  <option value="" selected="">Add a category here</option>
                  @foreach ($category as $item )
                    <option value="{{ $item->category_name }}">
                      {{ $item->category_name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="pb-2" >
                   <label>Product Image Here :</label>
                  <input type="file" name="image" required=""> 
               </div>

              <div class="pt-3">
                <input type="submit" value="Add Product" class="btn btn-primary">
              </div>

            </form>
            </div>
            
          </div>
      </div>

    </div>
    @include('admin.js')
  </body>
</html>