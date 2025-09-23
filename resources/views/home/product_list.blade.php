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
