@extends('layouts.layout')
@section('title', 'Eamon Express | Contact')
@section('css_content', 'css/contact.css')
{{-- @section('css_content', 'css/style.css') --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">


@section('content')
<div class="row-one">
    <div class="column left">
      <h2 class="title"><b>Contact us</b></h2>
      <p class="info">Need assistance with your shipping?<br>Simply reach out to us—we're here to help.</p>
    </div>
    <div class="column right">
      <img src="img/contact/customer-service.png" class="img">
    </div>
</div>


  <div class="row-two-title">
    <h2><b>Here's how to get in touch with us...</b></h2>
    <p>To contact us, you can either chat with a customer support representative via Live Chat or send a secure message through your account. We’ll respond as quickly as possible!</p>
  </div>

  <div class="row-two">
    <div class="column-two">
      <div class="card-left">
        <img src="img/contact/chatting_3820136.png" class="img1 img-fluid">
        <h2 class="title-one"><b>Connect with our support team</b></h2>
        <p>Our Live Chat is available from 8 AM to 8 PM CST (Central Time Zone) Monday through Friday.</p>
      </div>
    </div>

    <div class="column-two">
      <div class="card-right">
        <img src="img/contact/placeholder_1132398.png" class="img1">
        <h2 class="title-one"><b>Send us a message</b></h2>
        <p>Sign in to your account to send us a message.</p>
      </div>
    </div>
  </div>

  <div class="row-three">
    <h2><b>Ready to send a package?</b></h2>
      <div class="btn">
        <button type="submit">Yes! Get me a quote</button>
      </div>
  </div>

@endsection
