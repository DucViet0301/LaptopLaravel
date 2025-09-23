<style>
    .card{
        cursor: pointer;
    }
    .fw-bolder a {
        color: #212529; /* màu mặc định (đen) */
        text-decoration: none; /* bỏ gạch chân */
        transition: color 0.3s ease; /* hiệu ứng mượt */
    }
    .fw-bolder a:hover {
        color: blue; 
    }
    .div_input{
        height: 50px;
        width: 30%
    }
    .input_div{
        height: 45px;
        margin-bottom:5px;
        width: 80px
    }
    .btn-outline-dark:hover{
        background-color: red !important;
        color: white !important;
        border: red !important;    
    }
    
    @media (max-width: 900px) {
        .div_center{
            text-align: center;
        }

}

</style>
<section class="py-5 bg_color_section">
            <div class="container px-4 px-lg-5 mt-5" id="product-section">
                  <h1 class="text-center text-white pt-2 mb-4 fw-bold fs-1 ">Our Product</h1>
                  <div class="text-center mb-5">
                    <form id="searchForm" action ="{{ url('search_product') }}" method="GET" >
                        <input class="rounded-2 div_input" type="text" name="search" placeholder="Search for something">
                        <input type="submit" class="btn btn-primary input_div" value="Search">
                    </form>
                  </div>
                 <div id="product-list">
                    
                   <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4  justify-content-center">
                    @foreach ($product as $item )
                        <div class="col mb-5">              
                         <div class="card h-100">
                            <a href="{{ url('detail_product',$item->id) }}">
                            {{-- <a href="javascript:void(0)" data-id="{{ $item->id }}" class="product-link"> --}}
                              <img class="card-img-top" src="product/{{ $item->image }}" alt="{{ $item->title }}" />  
                            </a>
                            
                            <div class="card-body p-4">
                                <div class="text-center">
                                    
                                    <h5 class="fw-bolder">
                                        <a href="{{ url('detail_product',$item->id) }}" >
                                            {{ $item->category }}
                                        </a>
                                    </h5>

                                 
                                    @if( $item->discount_price != null )
                                     <h6>Discount Price: ${{ $item->discount_price }}</h6>
                                     <h6 style="text-decoration: line-through;color:blue">Price: ${{ $item->price }}</h6>
                                     <div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                                     @else
                                     <h6>Price: ${{ $item->price }}</h6>
                                    @endif
                                </div>
                            </div>

                            <form style="margin-bottom: 10px" data-id="{{ $item->id }}" class="add-to-cart-form">
                             {{-- <form style="margin-bottom: 10px" action="{{ url('add_cart',$item->id) }}" method="POST" class="add-to-cart-form">  --}}
                                @csrf
                                <div class="row div_center d-flex gap-5 mx-auto">
                                    <div class="col-md-4 "style="margin-bottom: -30px">
                                    <input type="number" name="quantity" value="1" min="1" style="width:100px;">
                                    </div>
                                    <div class="col-md-4 ">
                                    <input type="submit" value="Add To Cart"  class="btn btn-outline-dark atc">
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-center mt-4">
                        {!! $product->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                 </div> 
             </div> 
                
            </div>
        </section>
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
$(document).on('submit', '#searchForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formdata = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type:'GET',
        data: formdata,
        success: function(response){
            if(response.status === 'success'){
                $('#product-list').html(response.html);
            }
        }
    });
});
// $(document).on('click', '#product-list .pagination a', function(e){
//     e.preventDefault();
//     let url = $(this).attr('href');
//     $.ajax({
//         url: url,
//         type:'GET',
//         success: function(response){
//             if(response.status === 'success'){
//                 $('#product-list').html(response.html);
//             }
//         }
//     });
// });
</script>