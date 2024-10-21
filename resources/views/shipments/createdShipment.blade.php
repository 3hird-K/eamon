@extends('layouts.layout')
@section('title', 'Eamon Express | Shipping Rates')
@section('css_content', 'css/style.css')

@section('content')
<div class="container-md mt-5">
    <div class="card text-center shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Billing Document</h3>
        </div>
        <div class="card-body">

            <form action="{{ route('payment') }}" method="POST">
                @csrf

                <h5 class="card-title">Tracking #: <span class="font-weight-bold">{{ session('trackingId') }}</span></h5>
                <p>Total: <span class="fw-bold">$ {{ $totalNetCharge }}</span></p>
                <input type="hidden" name="amount" value="{{ $totalNetCharge }}">
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-success btn-md mx-2">Pay Using Paypal</button>

                </div>
            </form>


        </div>


        <div class="card-footer text-muted">
            Service Type: <strong>{{ $serviceType }}</strong>
        </div>
    </div>
</div>
@endsection

@section('js_content', 'js/register_animate.js')
