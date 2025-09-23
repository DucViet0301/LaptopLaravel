<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

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
  .table{
    margin-top:20px;
    border-collapse: collapse;
    color: white;
    border: 2px solid white
  }
  .table td, .table th {
   max-width: 150px;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis; 
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
            <div class="div_center">
              <p1  class="text">All order</p1>
            </div>
            <form action="{{ url('search') }}" method="POST">
              @csrf
              <input class="rounded-2 me-1 w-25 text-black" type="text" name="search"  placeholder="Search for something">
              <input  type="submit" value="Search" class="btn btn-primary rounded-2 px-3 py-2"> 
            </form>
              <table class="table mt-5">
              <tr class="table-primary">
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Product Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Payment</th>
                <th>Delivery</th>
                <th>Image</th>
                <th>Delivery</th>
                <th>Print PDF</th>
              </tr>
              @forelse ($order as $item)
                  <tr class="table-dark text-white">
                    <td>{{ $item->name }}</td>
                    <td class="font-size">{{ $item->email }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->product_title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->payment_status }}</td>
                    <td>{{ $item->delivery_status }}</td>
                    <td> <img  src="product/{{ $item->image }}"></td>
                    <td>
                      @if($item->delivery_status == 'delivered')
                      <p class="text-success">Delivered</p>
                      @else
                      <a 
                       href="{{ url('delivered',$item->id) }}" class="btn btn-primary">
                      Delivery
                      </a> 
                      @endif
                      </td>
                      <td><a href= "{{url('print',$item->id) }}" class="btn btn-secondary">Print PDF</a></td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="16">No Data Found</td>
                  </tr>

              @endforelse
            </table>
            
      </div>

    </div>
    @include('admin.js')
  </body>
</html>