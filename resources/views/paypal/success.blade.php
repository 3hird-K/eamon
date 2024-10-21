@extends('layouts.layout')
@section('title', 'Payment | Success')
@section('css_content', 'css/style.css')

@section('content')
<div class="container-md mt-5 d-flex justify-content-center pb-5">
    <div class="card shadow-lg" style="width: 900px; border-radius: 10px;">
        <div class="card-header text-center bg-primary text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h4 class="mb-0 text-light">Payment Receipt</h4>
        </div>
        <div class="card-body p-5">
            <h5 class="card-title">Tracking #: <span class="font-weight-bold">{{ session('trackingId') }}</span></h5>
            <h5 class="card-title">Transaction ID: <span class="font-weight-bold">{{ session('payment_id') }}</span></h5>
            <h5 class="card-title">Payer ID: <span class="font-weight-bold">{{ session('payer_id') }}</span></h5>
            <h5 class="card-title">Payer Email: <span class="font-weight-bold">{{ session('payer_email') }}</span></h5>
            <h5 class="card-title">Amount: <span class="font-weight-bold">{{ session('amount') }} {{ session('currency') }}</span></h5>
            <h5 class="card-title">Status: <span class="font-weight-bold">{{ ucfirst(session('status')) }}</span></h5>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <a href="/" class="btn btn-primary btn-block">Return Home</a>
                <a href="{{ session('trackingUrl') }}" class="btn btn-outline-primary btn-md mx-2" target="_blank">View Receipt</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content', 'js/locatorRadar.js')
