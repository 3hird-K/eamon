@extends('layouts.layout')
@section('title', 'Eamon Express | Shipping Rates')
@section('css_content', 'css/style.css')

@section('content')
<div class="container-md">
    <div class="text-center custom_bg_shipping py-5">
        <h3 class="text-white">Explore Our Global Shipping Solutions: Services Available Here.</h3>
    </div>
    <h5 class="text-center my-4 text-dark">From {{ $validatedData['fromCountry'] }} to {{ $validatedData['toCountry'] }} — Weight: {{ $validatedData['weight'] }} {{ session('weight_unit') }}</h5>

    {{-- Check if rates are available --}}
    @if(isset($rates['output']['rateReplyDetails']) && !empty($rates['output']['rateReplyDetails']))
        <div class="container-lg my-4">
            <div class="row">
                @foreach($rates['output']['rateReplyDetails'] as $rateDetail)
                    <div class="col-md-6 mb-4">
                        <div class="card bg-light text-dark shadow">


                        <form action="{{ route('shipPage') }}" method="POST">

                            @csrf

                            <?php
                                // Assuming $rateDetail is already defined and has the required data
                                $finalCharge = json_decode($rateDetail['ratedShipmentDetails'][0]['totalNetFedExCharge'] ?? 'N/A');
                                $additionalCharge = $finalCharge * 0.30; // 30% of totalNetCharge
                                $totalNetCharge = $finalCharge + $additionalCharge; // Total including 30%
                            ?>

                        <div class="card-body d-flex align-items-center px-md-5">
                                <div class="me-3">
                                    <!-- Align logo to the left with margin end -->
                                    <img src="{{ asset('img/logo.png') }}" alt="Service Logo" style="width: 100px;  max-width: 100%; height: auto;" class="img-fluid">
                                </div>
                                <div class="flex-grow-1 text-center">
                                    <!-- Center the service name -->
                                    <h6 class="card-title mb-0">{{ $rateDetail['serviceName'] ?? 'N/A' }}</h6>
                                    <input type="hidden" name="serviceType" value="{{ $rateDetail['serviceType'] ?? 'N/A' }}">
                                    <input type="hidden" name="finalCharge" value="{{ htmlspecialchars($finalCharge, ENT_QUOTES, 'UTF-8') }}">
                                    <input type="hidden" name="totalNetCharge" value="{{ htmlspecialchars($totalNetCharge, ENT_QUOTES, 'UTF-8') }}">
                                </div>
                                <div class="text-end">
                                    <h5 class="text-success">${{ htmlspecialchars($totalNetCharge, ENT_QUOTES, 'UTF-8') }}
                                        {{ $rateDetail['ratedShipmentDetails'][0]['currency'] ?? 'USD' }}</h5>
                                    <button type="submit" class="btn btn-success">Book now</button>

                                </div>
                            </div>



                            <div class="card-footer bg-light text-end">
                                <small class="text-muted">Total Surcharges: ${{ htmlspecialchars(json_decode($rateDetail['ratedShipmentDetails'][0]['shipmentRateDetail']['totalSurcharges'] ?? 'N/A'), ENT_QUOTES, 'UTF-8') }}
                                {{ $rateDetail['ratedShipmentDetails'][0]['currency'] ?? 'USD' }}</small>
                            </div>

                        </form>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
    <div class="alert alert-danger my-4 text-center mx-auto" role="alert">
        {{-- <p class="m-0 py-3">No rates available for the provided shipment details.</p> --}}
        <p class="m-0 py-3">
            We're sorry, but there are no rates available for your selected criteria at the moment. We're always updating our options, so please check back later!</p>
    </div>

        <div class="w-100 text-center my-4">
            <a href="/" class="text-decoration-underline">Return to get rates.</a>
        </div>
    @endif
</div>

@endsection

{{-- @section('js_content', 'js/register_animate.js') --}}
