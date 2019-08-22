@extends('layouts.master')
@section('title', 'Our Catalog')
@section('page-title', 'OUR CATALOG')
@section('body-id', 'catalog-page')
@section('content')
  <aside class="show">
    <h1 class="text--cream">Your Cart</h1>
    <a href="javascript:void(0);" class="btn btn-link text--cream btn-close"><i class='bx bx-x'></i></a>
    <form action="" class="py-4" method="post">
      @csrf
      <div class="form-row">
        <div class="form-group col-12">
          <div class="row mx-0 justify-content-between">{{-- tambah element lwt js --}}</div>
        </div>
        <div class="col-12">{{-- tambah button submit lwt js --}}</div>
      </div>
    </form>
  </aside>
  <main>
    <div class="container">
      <form class="row" id="shopCart" action="/post" method="post">
        @csrf
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 19,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 20,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 19,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 30,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 16,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 19,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 19,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <figure class="product">
            <img src="{{ asset('img/product.png') }}" alt="Our Product">
            <figcaption>
              <p class="text--cream">Nama Produk</p>
              <var class="text--cream">Rp. 19,999</var>
              <div class="product__action">
                <a href="javascript:void(0);" class="btn bg--cream"><i class='bx bx-plus'></i> Add To Cart</a>
              </div>
            </figcaption>
          </figure>
        </div>
      </form>
      <div class="row justify-content-center justify-content-md-end my-5">
        <div class="col-auto">
          <button type="submit" form="shopCart" class="btn">Go To Shopping Chart <i class='bx bx-caret-right'></i></button>
        </div>
      </div>
    </div>
  </main>
@endsection
@section('script')
  <script>
    $(document).ready(function(){
      defaultValue = 1;
      $("main .product__action .btn").one('click', function() {
        var productnya = $(this).parents(".product").clone();
        $("aside .form-group .row").append(productnya);
        $("aside .form-group .row .product__action .btn").replaceWith('<a href="javascript:void(0);" class="product__button product__button--increase"><i class="bx bx-plus"></i></a><input type="number" name="jumlah_beli" min="0" value="1" required><a href="#" class="product__button product__button--decrease"><i class="bx bx-minus"></i></a>');
        $("aside").addClass('show');
        $("header, footer, main").addClass("aside-showed");
        if ($("aside .form-row > div:last-child button").length === 0) {
          $("aside .form-row > div:last-child").append('<button type="submit" class="btn bg--cream w-100 text-center float-right">Proceed Checkout</button>');
        }
      });
      //tambahin jumlah beli
      $(document).on('click', '.product__button--increase', function() {
        $(this).next('input').val(parseInt($(this).next('input').val(), 10) + 1);
        defaultValue += parseInt($(this).next('input').val(), 10) + 1;
      });
      //kurangin jumlah beli
      $(document).on('click', '.product__button--decrease', function(){
        $(this).prev('input').val(parseInt($(this).prev('input').val(), 10) - 1);
        defaultValue += parseInt($(this).prev('input').val(), 10) - 1;
    	});
      $(".btn-close").click(function() {
        $(this).parent().removeClass("show");
        $("header, footer, main").removeClass("aside-showed");
      });
      $("#cartBtn").click(function() {
        $("aside").toggleClass("show");
        $("header, footer, main").toggleClass("aside-showed");
      });
      //perkecil width element saat aside nongol
      $("aside.show").siblings("main, header, footer").addClass("aside-showed");
    });
  </script>
@endsection
