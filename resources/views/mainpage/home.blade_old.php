@extends('layouts.layout')
@section('title', 'Eamon Express | Home')
@section('css_content', 'css/style.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/home.css">

@section('content')

<Section class="banner-area">
    <div class="container px-5">
        <div class="row">
            <div class="contain-head col-lg-6">
                <div class="contain-headings">
                    <h1>Compare and Book</h1>
                    <h4 class="head-secondary">Low Cost shipping services</h4>
                    <p class="p-center">
                        Receive your packages swiftly and securely with Eamon Express. Track your shipments and get real-time updates on their location.</p>
                    <div class="w-100 btn-getQuote">
                        <a href="#getQuote" class="btn btn-primary btn-lg d-flex justify-content-start px-4 my-2" style="border-radius: 50px; padding: 10px 0; font-size: 1.2rem;">Request Service</a>
                    </div>
                </div>
            </div>
            <div class="contain-img col-lg-6 ">
                <img src="img/Banner-image.png" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</Section>









<section class="calculator-area py-5" id='getQuote'>
    <div class="container-md">
        <div class="container-form p-4 rounded shadow-lg bg-white" style="max-width: 1000px; margin: auto; ">
            <h3 class="text-center text-white bg-primary py-4 rounded">Get a Quote Without Signing Up</h3>

            <form class="d-flex justify-content-center mt-4" id="calc-form" action="{{ route('retrieveShipments') }}" method="get">
                @csrf
                <div class="row g-3 align-items-center justify-content-between">
                    <!-- First Column -->
                    <div class="col-md-12">

                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="shipperStreet" class="fw-bold mb-2">Shippers Address*</label>
                            <input type="text" class="form-control" id="shipperStreet" name="shipperStreet" placeholder="72601 - Enter your postal code here" style='background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;'  required>


                        </div>



                        <!-- From ZIP Code -->
                        {{-- <div class="mb-3"> --}}
                            <input type="hidden" id="inputFromZip"  name="zipcodeFrom" class="form-control" placeholder="Zip / Postal code" id="zipcodeFrom" required style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;">

                            <input type="hidden" class="form-control text-uppercase" id="shipperstateOrProvinceCode" name="shipperstateOrProvinceCode" placeholder="AR (Argentina)" required>

                            <input type="hidden" class="form-control" id="shipperCity" name="shipperCity" placeholder="Green Valley" maxlength="35"  required>

                            <input type="hidden" class="form-control" id="fromCountry" name="fromCountry" placeholder="US - United States" required>

                            <input type="hidden" class="form-control" id="shipperCountryName" name="shipperCountryName" placeholder="Country: United States" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                            <input type="hidden" class="form-control" id="shFormattedAddress" name="shFormattedAddress" placeholder="formattedAdd" required>
                        {{-- </div> --}}
                    </div>

                    <!-- Second Column -->
                    <div class="col-md-12">
                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="recipientStreet" class="fw-bold mb-2">Recipient Address*</label>
                            <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="m1m1m1 - Enter your postal code here"  style='background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem; '  required>
                        </div>

                        <!-- To ZIP Code -->
                        {{-- <div class="mb-3"> --}}
                            <input type="hidden" id="inputToZip" name="zipcodeTo" class="form-control" placeholder="Zip / Postal code" id="zipcodeTo" required style='background-color: #eeeeee;  '>

                            <input type="hidden" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="ON (Ontario)" required >

                            <input type="hidden" class="form-control" id="recipientCity" name="recipientCity" placeholder="Toronto" maxlength="35" required>

                            <input type="hidden" class="form-control" id="toCountry" name="toCountry" placeholder="CA - Canada" required>

                            <input type="hidden" class="form-control" id="recipientCountryName" name="recipientCountryName" placeholder="Country: Canada" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                            <input type="hidden" class="form-control" id="reFormattedAddress" name="reFormattedAddress" placeholder="formattedAdd" required>

                    </div>
                        <!-- Weight Input -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="weight" class="form-label d-flex align-items-center fw-bold">
                                <i class="bi bi-box-seam me-2"></i> Parcel Weight*
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="weight" step="0.01" placeholder="Weight" required style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;">
                                <span class="input-group-text">LBS</span>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-12">
                        <!-- Submit Button -->
                        <div class=" d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary px-4 fw-bold btn-lg" style="border-radius: 50px; padding: 10px 0; font-size: 1.2rem;">Get Quote</button>

                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>
</section>




<Section class="About-eamon-area heading-dy">
    <div class="container px-5">
        <div class="row">
            <div class="contain-head col-lg-6">
                <div class="contain-headings">
                    <h4 class="py-5">What is Eamon Express?</h4>
                    <p class="p-center">Eamon Express is a user-friendly price comparison site for booking shipping services both within the US and internationally.<br></br>

                        Use our smart shipping calculator to get an instant quote, discover discounted rates with leading couriers, and compare prices to find the best deals.</p>
                        <div class="w-100 btn-getQuote">
                            <a href="#getQuote" class="btn btn-primary btn-lg d-flex justify-content-start px-4 my-2" style="border-radius: 50px; padding: 10px 0; font-size: 1.2rem;">Learn More</a>
                        </div>
                </div>
            </div>
            <div class="contain-img col-lg-6 ">
                <img src="img/about-aemon.png" alt="" class="eamon-desc img-fluid">
            </div>
        </div>
    </div>
</Section>
<section class="howitwork heading-dy py-5">
    <div class="container px-5">
        <h4 class="mb-4 text-center">How does Eamon Express work?</h4>
        <div class="row text-center">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="contain-inst">
                    <img src="img/inst1.png" alt="Step 1" class="img-fluid mb-3">
                    <p>Let us know where you're sending your package from and its destination.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="contain-inst">
                    <img src="img/inst2.png" alt="Step 2" class="img-fluid mb-3">
                    <p>Indicate the weight and dimensions of your package so we can find the best deals for you.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="contain-inst">
                    <img src="img/inst3.png" alt="Step 3" class="img-fluid mb-3">
                    <p>Select the courier that suits your needs and pay for your shipping online.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="contain-inst">
                    <img src="img/inst4.png" alt="Step 4" class="img-fluid mb-3">
                    <p>Attach your shipping label and either drop off your package or arrange for it to be picked up.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="update-sec heading-dy py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="contain-img col-lg-6 mb-4 mb-lg-0 text-center text-lg-left">
                <img src="img/update-banner.png" alt="Update Banner" class="img-fluid rounded">
            </div>
            <div class="contain-head col-lg-6 text-center text-lg-left">
                <div class="contain-headings">
                    <h4 class="mb-3 font-weight-bold">Stay Updated With Our Latest News</h4>
                    <p class="mb-4 text-muted p-center">Receive our latest updates, news, blog posts, and more directly in your inbox. Subscribe to our mailing list today.</p>
                    <div class="contain-update-frm">
                        <form action="" method="post" id="updatefrm">
                            <div class="input-group mb-3 w-100">
                                <input type="email" class="form-control email-input" placeholder="Enter your email" required style="padding: 15px; font-size: 1rem;">
                                <button type="submit" class="btn btn-primary" id="button-addon2" style="padding: 15px 25px; font-size: 1rem;">Subscribe</button>
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
