@extends('layouts.app')
@section('title', 'Dashboard')
@section('custom_css')
<link rel="stylesheet" href="{{URL::secure_asset('/assets/css/home.css')}}">
@include('layouts.head')
@endsection
@section('content')
<main class="page">
    <div class="nav__bar"> </div>
    <div class="main__page">
      @include('layouts.navbar')
      <div class="main__text">
        <h1 class="main__title">SIMPLEST CRM IN TOWN</h1>
        <h2 class="main__subtitle">
          Customize the forms the way you want it and remove complexity. <br> Try it for <span id="price">US$5</span> per user.
        </h2>
        <div class="buttons">
          <button type="button" class="btn btn-dark action__btn" onclick="window.location.href=`{{url('/signup')}}`">Sign Up</button>
          <button type="button" class="btn btn-outline-dark action__btn">Try Demo</button>
        </div>
      </div>

      <img class="bck__img" src="{{ URL::secure_asset('/assets/images/home/bck-img.png') }}" alt="background">
    </div>
    @include('layouts.feature')
    @include('layouts.footer')
  </main>

@endsection