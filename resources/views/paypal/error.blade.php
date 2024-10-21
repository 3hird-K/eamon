@extends('layouts.layout')

@section('title', 'Payment | Error')

@section('css_content', 'css/style.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

@section('content')
<div class="container-md mt-5 d-flex justify-content-center">
    <div class="card shadow-lg" style="width: 900px; border-radius: 15px;">
        <div class="card-header text-center bg-danger text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h4 class="mb-0 text-light">Payment Failed</h4>
        </div>
        <div class="card-body">
            <div class="text-center text-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem;"></i>
                <h5 class="mt-3">Unfortunately, your payment could not be processed.</h5>
            </div>
            <p class="text-muted text-center">There was an error while processing your payment. Please try again or contact support if the issue persists.</p>
            <div class="d-flex justify-content-center mt-4">
                <a href="/" class="btn btn-primary">Return Home</a>
                {{-- <a href="{{ route('payment') }}" class="btn btn-outline-danger ms-3">Retry Payment</a> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content', 'js/locatorRadar.js')
