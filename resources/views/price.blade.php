@extends('layouts.app')
@section('title', 'Dashboard')
@section('custom_css')
<link rel="stylesheet" href="{{asset('/assets/css/home.css')}}">
@include('layouts.head')
@endsection
@section('content')
<main class="page">
    <div class="nav__bar"> </div>
    <div class="main__page">
      @include('layouts.navbar')
      <div class="main__text">
        <h1 class="main__title">Simple CRM with simple pricing</h1>
        <h2 class="main__subtitle">
          <span id="price">US$5</span> per user/per month for all features. Cancel anytime.
        </h2>
      </div>
      <img class="bck__img -mt-2" src="{{asset('/assets/images/price/house.png')}}" alt="background">
    </div>
    @include('layouts.feature')
    @include('layouts.footer')
  </main>

@endsection