@extends('layouts.master')
@section('title', 'Contact Us')
@section('page-title', 'Contact Us')
@section('body-id', 'contact-us-page')
@section('css')
  <style media="screen">
    input[type='email'].form-control {
      text-transform: none !important;
    }
  </style>
@endsection
@section('content')
  <main>
    <div class="container py-5">
      @if (Session::has('success_message'))
        <div class="alert alert-success">
          {{ Session::get('success_message') }}
        </div>
      @elseif (Session::has('error_message'))
        <div class="alert alert-danger">
          {{ Session::get('error_message') }}
        </div>
      @endif
      <div class="row">
        <div class="col-12 col-lg-6 pr-lg-5">
          <form class="d-block mb-3 mb-lg-0" action="{{ route('contactus.store') }}" method="post">
            @csrf
            <div class="form-group">
              <input type="text" name="peopleName" class="form-control" id="peopleName" placeholder=" &nbsp; " @auth value="{{ Auth::user()->name }}" readonly @endauth>
              <label for="peopleName">What is your name</label>
            </div>
            <div class="form-group">
              <input type="email" name="peopleEmail" class="form-control" id="peopleEmail" placeholder=" &nbsp; " @auth value="{{ Auth::user()->email }}" readonly @endauth>
              <label for="peopleEmail">What is your email</label>
            </div>
            <div class="form-group">
              <textarea name="message" id="peopleMessage" rows="7" placeholder=" &nbsp; " class="form-control"></textarea>
              <label for="peopleMessage">Let drop your message</label>
            </div>
            <button type="submit" class="btn bg--cream">Send <i class='bx bx-send ml-3'></i></button>
          </form>
        </div>
        <div class="col-12 col-lg-6 pl-lg-5">
          <ul class="list-contact">
            <li>
              <i class='bx bx-mail-send'></i>
              <a href="mailto:yollamiranda@gmail.com?subject=contact+us">{{ $admin->email }}</a>
            </li>
            <li>
              <i class='bx bxs-phone' ></i>
              <a href="tel:+62811-1257-596">{{ $admin->phone }}</a>
            </li>
            <li>
              <i class='bx bxl-instagram-alt'></i>
              <a href="https://www.instagram.com/bariq.dharmawan/" target="_blank">@insive.id</a>
            </li>
          </ul>
          {!! $admin->address !!}
        </div>
      </div>
    </div>
  </main>
@endsection
@section('script')
  <script>
    $(document).ready(function() {
      $(".alert").delay(400).fadeOut("slow");
    });
  </script>
@endsection
