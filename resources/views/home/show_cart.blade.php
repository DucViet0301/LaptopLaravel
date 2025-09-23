<!DOCTYPE html>
<html lang="en">
    <head>
      <base href="/public">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>StoreLapTop</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="home/css/styles.css" rel="stylesheet" />
        <style>
          table td{
            vertical-align: middle;
            text-align: center;
          }
          .div_size{
            width: 150px;
            height: 150px;
          }
          .price_deg{
          width: 150px;
          height: 50px;
          border-radius: 10px;
        }
        @media (max-width: 1000px) {
        .order-cols {
          flex-direction: column !important;
        }
      } 
</style>
    </head>
    <body>
      <div class="hero_area">
        @include('home.header')
         <div class="container mt-5 pt-5">
            <h2 class="mb-4 text-center fs-1">Your Cart</h2>
            <table class="table table-bordered table-striped">
                <tr class="table-primary">
                  <th>Product title</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Image</th>
                  <th>Action</th>
                </tr>
                <?php $total_price = 0 ?>
                @foreach ($cart as $item )
                  <tr id="delete_cart-{{ $item->id }}">
                    <td>{{ $item->product_title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>
                      <img class="div_size" src="/product/{{ $item->image }}">
                    </td>
                    <td>
                      <a 
                      data-id="{{ $item->id }}"
                      class="btn btn-danger delete-cart">
                      Remove Product
                    </a>
                  </td>
                  </tr>
                  <?php $total_price += $item->price ?>
                @endforeach
              </table>   
                <div class="row pt-4 pb-4 text-center">
                  <div class="col-md-6 d-flex justify-content-md-start justify-content-center pb-4">
                    <div class="price_deg bg-success p-2 rounded text-white">
                      <h1>Total Price : {{ $total_price }}</h1>
                    </div>
                  </div>

                  <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    <div>
                      <h1 class="pb-3 fw-bold">Proceed to Order</h1>
                      <a href="{{ url('cash_order') }}" class="btn btn-danger m-1">Cash On Delivery</a>
                      <form action="{{ url('paypal',$total_price) }}" method="POST" style="display: inline;">
                          @csrf
                          <input type="hidden" name="amount" value="{{ $total_price }}">
                          <button type="submit" class="btn btn-primary m-1">
                              <i class="bi bi-credit-card"></i> Pay Using Card
                          </button>
                      </form>
                    </div>
                  </div>
                </div>

          </div>
        </div>
        <!-- Bootstrap core JS-->
        <footer class="py-5 bg-dark {{ $cart_total >= 2 ? '':'fixed-bottom' }}">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        @include('home.js')
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.delete-cart', function(){
  let formdata = $(this).data('id');
  if(confirm('Are you sure want to remove this cart?')){
    $.ajax({
      url:`remove_cart/${formdata}`,
      type: 'DELETE',
      data: {
        _token:"{{ csrf_token() }}"
      },
      success : function(response){
        if(response.status === 'success'){
          $(`#delete_cart-${formdata}`).remove();
          $('#cart-count').text(response.cartCount);
        }
      }

    });
  }
});
</script>