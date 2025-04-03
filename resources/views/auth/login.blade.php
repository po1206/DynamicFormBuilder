@extends('layouts.app')
@section('title', "New Account Sign In")
@section('custom_css')
<link rel="stylesheet" href="{{asset('/assets/css/signin.css')}}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/8bc50a29d1.js" crossorigin="anonymous"></script>
@endsection
@section('content')
<div class="godaddy_container">
    <header class="godaddy_header">
        <div class="godaddy_header_contact_us"><span style="color: white">Contact Us 24/7<i class="fas fa-chevron-down chevron-down-custom-icon"></i></span></div>
    </header>
    <main class="godaddy_content">
        <div class="godaddy_content-sign-in-form">
            <div class="form_header">Sign In</div>
            <div class="form_sign_up_link"><span>New users?</span><a href="{{url('/signup')}}"><span class="text-2">Create an account</span></a></div>
            <div class="form_body">
                <form action="#">
                    <div class="input-group">
                        <label>
                            <div class="label">Username or customer #</div>
                            <input class="input-item" type="text" name="name" placeholder="">
                        </label>
                    </div>
                    <div class="input-group">
                        <label>
                            <div class="label">Password</div>
                            <input class="input-item" type="text" name="number" placeholder="">
                        </label>
                    </div>
                    <div class="input-group">
                        <div>
                            <input type="checkbox" checked="checked" class="checkbox-keep-signed-in">
                            <span class="keep-me-signed-in">Keep me signed in on this device</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="register" class="submit">Sign in</button>
                    </div>
                </form>
            </div>
            <div class="form_footer">
                Need to find <span class="text-2">your username</span> or <span class="text-2">password</span>?
            </div>
        </div>
    </main>
    <footer class="godaddy_footer">
        <div class="godaddy_footer_text">
            Copyright &#169; 1999 - 2021 Company name, LLC. All Rights Reserved. <span class="privacy-policy">Privacy Policy</span>
        </div>
    </footer>
</div>
@endsection
