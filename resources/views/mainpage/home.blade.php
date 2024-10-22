@extends('layouts.layout')
@section('title', 'Eamon Express')
@section('css_content', 'css/style.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/home.css">


@section('content')

<Section class="banner-area">
    <div class="container px-5">
        <div class="row">
            <div class="contain-head col-lg-6">
                <div class="contain-headings" data-aos="fade-right" data-aos-duration="700">
                    <h1>Compare and Book</h1>
                    <h4 class="head-secondary">Low cost shipping services</h4>
                    <p class="p-center">
                        Receive your packages swiftly and securely with Eamon Express. Track your shipments and get real-time updates on their location.</p>

                    <!--<button type="submit" onclick="#" class="btn-one">Request a Service</button>-->
                    <!--<div class="request_service">-->
                    <!--    <a href="#getQuote" class="btn btn-primary btn-one" style="border-radius: 50px; padding-block: 0.6rem">Request a Service</a>-->
                    <!--</div>-->
                    <div class="request_service d-flex justify-content-center justify-content-lg-start" style="width: 100%;">
                        <a href="#getQuote" class="btn btn-primary btn-one" style="border-radius: 50px; padding-block: 0.6rem; padding-inline: 2rem;">Request Service</a>
                    </div>

                </div>
            </div>
            <div class="contain-img col-lg-6 " data-aos="fade-left" data-aos-duration="700">
                <img src="img/Banner-image.png" alt="" class="img">
            </div>
        </div>
    </div>
</Section>

<section class="calculator-area py-5" id='getQuote'>
    <div class="container-lg">
        <div class="container-form p-2 rounded shadow-lg bg-white" style="max-width: 1200px; margin: auto; ">
            <h3 class="text-center text-white bg-primary py-4 rounded mb-3">Get a Quote Without Signing Up</h3>

            <form class=" row d-flex w-100 justify-content-between align-items-center mx-auto mt-2 px-3" id="calc-form" action="{{ route('retrieveShipments') }}" method="get">
                @csrf
                    <!-- First Column -->
                    <div class="col-md-4">

                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="shipperStreet" class="fw-bold mb-2">Sender Address*</label>
                            <input type="text" class="form-control" id="shipperStreet" name="shipperStreet" placeholder="Enter Zip code or full address" style='background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem; max-width: 100%;'  required>


                        </div>



                        <!-- From ZIP Code -->
                            <input type="hidden" id="inputFromZip"  name="zipcodeFrom" class="form-control" placeholder="Zip / Postal code" id="zipcodeFrom" required style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;">

                            <input type="hidden" class="form-control text-uppercase" id="shipperstateOrProvinceCode" name="shipperstateOrProvinceCode" placeholder="AR (Argentina)" required>

                            <input type="hidden" class="form-control" id="shipperCity" name="shipperCity" placeholder="Green Valley" maxlength="35"  required>

                            <input type="hidden" class="form-control" id="fromCountry" name="fromCountry" placeholder="US - United States" required>

                            <input type="hidden" class="form-control" id="shipperCountryName" name="shipperCountryName" placeholder="Country: United States" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                            <input type="hidden" class="form-control" id="shFormattedAddress" name="shFormattedAddress" placeholder="formattedAdd" required>
                    </div>

                    <!-- Second Column -->
                    <div class="col-md-4">
                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="recipientStreet" class="fw-bold mb-2">Reciever Address*</label>
                            <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="Enter Zip code or full address"  style='background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem; '  required>
                        </div>

                        <!-- To ZIP Code -->
                            <input type="hidden" id="inputToZip" name="zipcodeTo" class="form-control" placeholder="Zip / Postal code" id="zipcodeTo" required style='background-color: #eeeeee;  '>

                            <input type="hidden" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="ON (Ontario)" required >

                            <input type="hidden" class="form-control" id="recipientCity" name="recipientCity" placeholder="Toronto" maxlength="35" required>

                            <input type="hidden" class="form-control" id="toCountry" name="toCountry" placeholder="CA - Canada" required>

                            <input type="hidden" class="form-control" id="recipientCountryName" name="recipientCountryName" placeholder="Country: Canada" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                            <input type="hidden" class="form-control" id="reFormattedAddress" name="reFormattedAddress" placeholder="formattedAdd" required>

                    </div>
                        <!-- Weight Input -->
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="weight" class="form-label d-flex align-items-center fw-bold">
                                <i class="bi bi-box-seam me-2"></i> Weight*
                            </label>
                            <!--<div class="input-group">-->
                            <!--    <input type="number" class="form-control" name="weight" step="0.01" placeholder="Weight" required style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;">-->
                            <!--    <span class="input-group-text">LBS</span>-->
                            <!--</div>-->
                            <div class="input-group row">
                                <input type="number" class="form-control col-md-10" min="1" name="weight" step="0.01" placeholder="Weight" required style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;">
                                <select class="form-select col-md-2" aria-label="Weight unit" name="weight_unit" style="max-width: 80px">
                                    <option value="LB" selected>LBS</option>
                                    <option value="KG">KG</option>
                                </select>
                            </div>

                        </div>


                    </div>
                    <div class="col-md-1 mt-3 mt-sm-0">
                        <!-- Submit Button -->
                        <div class=" d-flex justify-content-center align-items-center mt-3 mb-sm-3">
                            <button type="submit" class="btn btn-primary  px-4 " style="padding: 10px 0; border-radius: 50px;">GetQuote</button>
                            <!--<button type="submit" class="btn btn-primary btn-one" style="border-radius: 50px; padding-block: 0.6rem">GetQuote</button>-->

                        </div>
                    </div>


                 </form>
        </div>
    </div>
</section>




<Section class="About-eamon-area heading-dy">
    <div class="container px-5">
        <div class="row">
            <div class="contain-head col-lg-6" data-aos="fade-up-right" data-aos-duration="700">
                <div class="contain-headings">
                    <h2>What is Eamon Express?</h2>
                    <p class="p-center">Eamon Express is a user-friendly price comparison site for booking shipping services both within the US and internationally.<br></br>

                        Use our smart shipping calculator to get an instant quote, discover discounted rates with leading couriers, and compare prices to find the best deals.</p>
                        <!--<button type="submit" onclick="window.location.href='{{ url('/about') }}'" class="btn-two">Learn more</button>-->
                         <div class="request_service d-flex justify-content-center justify-content-lg-start" style="width: 100%;">
                        <a href="{{ url('/about') }}"  class="btn btn-primary btn-one" style="border-radius: 50px; padding-block: 0.6rem; padding-inline: 2rem;">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="contain-img col-lg-6 " data-aos="fade-up-left" data-aos-duration="700">
                <img src="img/about-aemon.png" alt="" class="img">
            </div>
        </div>
    </div>
</Section>
<section class="howitwork heading-dy py-5">
    <div class="container px-5">
        <h2 data-aos="zoom-in">How does Eamon Express work?</h2>
        <div class="contain-instructions">
            <div class="contain-inst" data-aos="zoom-in-up" data-aos-duration="700">
                <img src="img/inst1.png" alt="" class="img2">
                <p>Let us know where you're sending your package from and its destination.</p>
            </div>
            <div class="contain-inst" data-aos="zoom-in-up" data-aos-duration="700">
                <img src="img/inst2.png" alt="" class="img2">
                <p>Indicate the weight and dimensions of your package so we can find the best deals for you.</p>
            </div>
            <div class="contain-inst" data-aos="zoom-in-up" data-aos-duration="700">
                <img src="img/inst3.png" alt="" class="img2">
                <p>Select the courier that suits your needs and pay for your shipping online.</p>
            </div>
            <div class="contain-inst" data-aos="zoom-in-up" data-aos-duration="700">
                <img src="img/inst4.png" alt="" class="img2">
                <p>Attach your shipping label and either drop off your package or arrange for it to be picked up.</p>
            </div>
        </div>
    </div>
</section>

<section class="update-sec heading-dy py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="contain-img col-lg-6 mb-4 mb-lg-0 text-center text-lg-left" data-aos="fade-right">
                <img src="img/update-banner.png" alt="Update Banner" class="img">
            </div>
            <div class="contain-head col-lg-6 text-center text-lg-left" data-aos="fade-left" data-aos-duration="700">
                <div class="contain-headings">
                    <h2>Stay Updated With Our Latest News</h2>
                    <p>Receive our latest updates, news, blog posts, and more directly in your inbox. Subscribe to our mailing list today.</p>
                    <div class="contain-update-frm">
                        <form action="" method="post" id="updatefrm">
                            <div class="input-group mb-3 w-100">
                                <input type="email" class="form-control email-input" placeholder="Enter your email" required style="padding: 15px; font-size: 1rem;">
                                <button type="submit" class="btn btn-primary" id="button-addon2" style="padding: 15px 15px; font-size: 1rem;">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js_content', 'js/locateAddressWithPostal.js')
{{-- @section('js_content2', 'js/validatePostal.js') --}}
