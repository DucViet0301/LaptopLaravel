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
    padding-top:40px; 
  }
  .text{
    font-weight: bold; 
    font-size: 40px;
  }
  .btn-primary{
    height: 42px;
  }
  tr{
    height: 80px;
  }
  .table{
    margin: auto;
    width: 90%;
    margin-top:20px;
    border-collapse: collapse;
    text-align: center;
    color: white;
    border: 2px solid white
  }
  .description-cell {
    width: 300px;
    max-width: 300px;
    white-space: normal !important;  
    word-break: normal !important; 
    overflow-wrap: break-word;       
    vertical-align: top;
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
            <div class="alert alert-success">
              <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
              {{ session()->get('message') }}
            </div>
            @endif
            
            <div class="div_center">
              <h1>Show Product</h1>
              <table class="table">
                <tr class="table-primary">
                  <th>Product Title</th>
                   <th style="width: 250px;">Description</th>
                  <th>Quantity </th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Discount Price</th>
                  <th>Image</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
                @foreach ($product as $item)
                  <tr>
                    <td>{{ $item->title }}</td>
                    <td class="description-cell"><div>{{ $item->description }}</div></td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->discount_price}}</td>
                    <td><img src="/product/{{ $item->image }}"></td>
                    <td>
                      <a href="{{ url('update_product',$item->id) }}" class="btn btn-warning">
                        Edit
                      </a>
                    </td>
                    <td>
                      <a href="{{ url('delete_product',$item->id) }}" class="btn btn-danger">
                        Delete
                      </a>
                    </td>
                    
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
       </div>
      </div>

    </div>
    @include('admin.js')
  </body>
</html>