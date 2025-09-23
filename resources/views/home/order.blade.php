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
            <h2 class="mb-4 text-center fs-1">Your Order</h2>
            <table class="table table-bordered table-striped">
              <tr class="table-primary text-center">
                <th>Product Title</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Payment Status</th>
                <th>Delivery</th>
                <th>Image</th>
                <th>Cencel Order</th>
              </tr>
              @foreach ($order as $item)
                  <tr id="delete-order-{{ $item->id }}">
                    <td>{{ $item->product_title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->payment_status }}</td>
                    <td>{{ $item->delivery_status }}</td>
                    <td class="align-middle">
                      <div class="d-flex justify-content-center">
                        <img src="product/{{ $item->image }}" 
                            class="img-fluid" style="max-width:100px;">
                      </div>
                    </td>
                    <td>
                      @if ($item->delivery_status == 'processing')
                        <a 
                        data-id="{{ $item->id }}" 
                         class="btn btn-danger delete-order">Canel Order</a></td>
                      @else
                       <p class="text-secondary">Not Allowed</p>
                      @endif
                      
                  </tr>
              @endforeach
              </table>   
          </div>
        </div>
        <!-- Bootstrap core JS-->
        <footer class="py-5 bg-dark {{ $total_order > 2? '' : 'fixed-bottom' }} ">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        @include('home.js')
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).on('click', '.delete-order', function(e){
    let formdata = $(this).data('id');
    if(confirm('Are you want to cancel order')){
      $.ajax({
        url: `cancel_order/${formdata}`,
        type:'DELETE',
        data:{
          _token : "{{ csrf_token() }}"
        },
        success: function(response){
          if(response.status === 'success'){
            $(`#delete-order-${formdata}`).remove();
            $('#order-count').text(response.total_order)
          }
        }
      });
    }
  });
</script>