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
          .hero_area {
          position: relative;
          min-height: 100%;
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-orient: vertical;
          -webkit-box-direction: normal;
              -ms-flex-direction: column;
                  flex-direction: column;
           }
           .card {
            margin-top: 110px;
           }
           .btn-outline-dark{
            width: 150px;
            height: 50px;
           }
           .btn-outline-dark:hover{
                background-color: red !important;
                color: white !important;
                border: red !important;
            }
           @media (max-width:900px) {
            .card{
              margin: auto;
              width: 80%;
              margin-top: 20%;
              margin-bottom: 10%
            }
              .pc {
                text-align: center;
              }
              img{
                width: 65%;
                margin-left: 20%
              }
              .row{
                padding: 10px;
              }
            }
        </style>
    </head>
    <body>
      <div class="hero_area">
        @include('home.header')
        <div class="col-sm-8 col-md-6 col-lg-6 m-auto ">
          <div class="card shadow border-0 w-80 rounded-4 p-3">
            <div class="d-lg-flex gap-5">
              <div class="img-fluid" >
                <img class="img-fluid w-90" 
                src="product/{{ $product->image }}">
              </div>
              <div class="detail-box">
                <h5 class="fw-bold text-dark fs-1 pc">{{ $product->title }}</h5>
                <p class=" text-dark">{{ $product->description }}</p>
                @if ($product->discount_price != null)
                  <p class="text-danger fw-bold fs-3">Discount Price : {{ $product->discount_price }}</p>
                  <p class="fs-5" style="text-decoration: line-through; color:blue">Price : {{ $product->price }}</p>
                @else
                <p class="fs-3 fw-bold" style="pt-3; color:blue">Price :{{ $product->price }}</p>
                @endif
                <p>Product Quantity : {{ $product->quantity }}</p>
                <form style="margin-top: 10px" data-id="{{ $product->id }}" class="add-to-cart-form">
                  @csrf
                  <div class="row ">
                    <div class="col">
                      <input type="number" name="quantity" value="1" min="1" style="width:100px;">
                    </div>
                    <div class="col">
                      <input type="submit" value="Add To Cart" class="btn btn-outline-dark atc">
                    </div>
                  </div>
                </form>
              </div>
              
            </div>
          </div>

        </div>
      </div>
        
        <footer class="py-5 bg-dark fixed-bottom">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        @include('home.js')
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('submit', '.add-to-cart-form', function(e) {
    e.preventDefault();

    let form = $(this);
    let productId = form.data('id');
    let quantity = form.find('input[name="quantity"]').val();
    let token = form.find('input[name="_token"]').val();

    $.ajax({
        url: '/add_cart/' + productId,
        method: 'POST',
        data: {
            _token: token,
            quantity: quantity
        },
        success: function(res) {
            if (res.success) {
                alert(res.message);
                $('#cart-count').text(res.cartCount);
            } else if (res.redirect) {
                window.location.href = res.redirect;
            }
        },
        error: function() {
            alert("Có lỗi xảy ra!");
        }
    });
});
</script>
