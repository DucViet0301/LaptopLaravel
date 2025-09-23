<nav class="navbar navbar-expand-lg navbar-light  bg_color_header fixed-top">
    <div class="container  px-4 px-lg-5">
        <a class="navbar-brand text-white " href="{{ url('/') }}">ShopLapTop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active text-white" aria-current="page" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#about-section">About</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white " id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item " href="#product-section">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
            </ul>

            <div class="d-flex  gap-3">
                @php
                    $cart_total = $cart_total ?? 0;
                    $total_order = $total_order ?? 0;
                @endphp
                <a href="{{ url('show_cart') }}" class="btn btn-outline-dark text-white">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span id="cart-count" class="badge bg-dark text-white ms-1 rounded-pill">
                        {{ $cart_total ?? 0 }}
                    </span>
                </a>
                <a href="{{ url('show_order') }}" class="btn btn-outline-dark text-white">
                    <i class="bi bi-backpack2-fill me-2"></i>
                    Order
                    <span id="order-count" class="badge bg-dark text-white ms-1 rounded-pill">
                        {{ $total_order ?? 0 }}
                    </span>
                </a>

                
                <div class="d-flex ms-auto gap-2 ">
                   @if (Route::has('login'))
                    @auth
                        <x-app-layout></x-app-layout>
                    @else
                        <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-success" href="{{ route('register') }}">Register</a>
                    @endauth
                @endif 
                </div>

                
            </div>
        </div>
    </div>
</nav>
