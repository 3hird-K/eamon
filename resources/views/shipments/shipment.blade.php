@extends('layouts.layout')
@section('title', 'FedEx Shipping')
@section('css_content', 'css/style.css')
<link href="https://js.radar.com/v4.4.2/radar.css" rel="stylesheet">
    <script src="https://js.radar.com/v4.4.2/radar.min.js"></script>

@section('content')

<body style="background-color: #81D0f8">
<div class="container-xl mt-5 p-4 my-5 rounded">
    <h2 class="text-center fw-bold" data-aos="zoom-in-down" data-aos-duration="700">FedEx Shipping Calculator</h2>
    <form action="{{ route('createFullQuote')}}" method="POST">
        @csrf




        <!-- Shipper and Recipient Info Section -->
        <div class="card my-4 p-3 bg-white" data-aos="zoom-in-down" data-aos-duration="700">
            <div class="card-header p-3 d-flex flex-column flex-md-row text-light justify-content-between align-items-center px-3 px-md-5" style="background-color: #4e148ce3; border-radius: 5px 5px 0 0;">
                <div class="text-center text-sm-start mb-3 mb-md-0">
                    <h4 class="sh-header">Shipper & Recipient Information</h4>
                    <small>Fill in the details of the shipper and recipient.</small>
                    <p><span class="fw-bold">{{ $serviceType }}</span></p>
                    <p>Initial Total: <span class="fw-bold">$ {{ $totalNetCharge }}</span></p>

                </div>
                <div class="text-center">
                    <img src="https://www.fedex.com/content/dam/fedex-com/logos/logo.png" alt="fedexLogo" style="max-height: 50px;" class="img-fluid">
                </div>
            </div>



            <div class="card-body px-4">
                <div>
                <h4 class="text-primary sh-header my-4">Shipper Information</h4>
                {{-- <input type="text" name="shipperCountryCode" value="{{ "{$shipperCountryName} - {$zipcodeFrom} ({$shipperstateOrProvinceCode})" }}"
                class="form-control fw-bold mb-3 py-3 text-center" disabled>
                <input type="hidden" name="shipperCountryCode" value="{{ "{$shipperCountryName} - {$zipcodeFrom} ({$shipperstateOrProvinceCode})" }}"
                class="form-control fw-bold"> --}}
                </div>
                <div class="row">

                    {{-- Shipper Name --}}

                <div class="col-md-6 mb-3">
                    <label for="shipperName" class="fw-bold mb-2">Full Name*</label>
                    <input type="text" class="form-control" id="shipperName" name="shipperName" placeholder="Enter Full Name" style="font-size: 15px; padding-block: 0.7rem;" required>
                </div>

                {{-- Company Name --}}
                <div class="col-md-6 mb-3">
                    <label for="shipperCompany" class="fw-bold mb-2">Company Name (Optional)</label>
                    <input type="text" class="form-control" id="shipperCompany" name="shipperCompany" placeholder="Company name" style="font-size: 15px; padding-block: 0.7rem;" >
                </div>

                {{-- Email Add --}}
                <div class="col-md-6 mb-3">
                    <label for="shipperEmail" class="fw-bold mb-2">Email Address (Optional)</label>
                    <input type="email" class="form-control" id="shipperEmail" name="shipperEmail" placeholder="Email Address"style="font-size: 15px; padding-block: 0.7rem;" >
                </div>

                 {{-- Shipper Phone --}}
                <div class="col-md-6 mb-3">
                    <label for="shipperPhone" class="fw-bold mb-2">Phone number.*</label>
                    <input type="tel" class="form-control" id="shipperPhone" name="shipperPhone" placeholder="(604) 555-7890" minlength="10" maxlength="15" style="font-size: 15px; padding-block: 0.7rem;" required>
                    <div class="error-message" id="phone-error" style="display: none; color: red;"></div>
                </div>


                    {{-- Shipper Street --}}

                    <div class="col-md-12 mb-3">
                        <label for="shipperStreet" class="fw-bold mb-2">Shipper Street (Modify)*</label>
                        <input type="text" class="form-control" name="shipperStreet" placeholder="123 Main Street" style="font-size: 15px; padding-block: 0.7rem;" maxlength="35" value="{{ session('shipperStreet', $shipperStreet) }}"
                         required>
                    </div>

                    {{-- Shipper City --}}
                    <div class="col-md-3 mb-3">
                        <label for="shipperCity" class="fw-bold mb-2">City*</label>
                        <input type="text" class="form-control" id="shipperCity" name="shipperCity" placeholder="New York City" maxlength="35" style="font-size: 15px; padding-block: 0.7rem;" value="{{ session('shipperCity', $shipperCity) }}" required >
                    </div>




                    {{-- Shipper Postal --}}
                    <div class="col-md-3 mb-3">
                        <label for="zipcodeFrom" class="fw-bold mb-2">Zip/Postal Code *</label>
                        <input type="text" class="form-control" name="zipcodeFrom" id="zipcodeFrom" value="{{ session('zipcodeFrom', $zipcodeFrom) }}" placeholder="Postal/Zip Code" style="font-size: 15px; padding-block: 0.7rem;" required>
                    </div>

                      {{-- Shipper State --}}
                      <div class="col-md-3 mb-3">
                        <label for="shipperstateOrProvinceCode" class="fw-bold mb-2">Province/State Code</label>
                        <input type="text" class="form-control text-uppercase" id="shipperstateOrProvinceCode" name="shipperstateOrProvinceCode" placeholder="AR (Argentina)" value="{{ old('shipperstateOrProvinceCode', $shipperstateOrProvinceCode ) }}" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                    </div>

                    {{-- Shipper Country --}}
                    <div class="col-md-3 mb-3">
                        <label for="shipperCountryName" class="fw-bold mb-2">Country *</label>
                        <input type="text" class="form-control" name="shipperCountryName" id="shipperCountryName" placeholder="United States" value="{{ session('shipperCountryName', $shipperCountryName) }}" style="font-size: 15px; padding-block: 0.7rem;" required readonly>
                    </div>

                    <div class="col-md-12 my-3">
                        <!-- Residential Address Checkbox -->
                        <div class="form-check my-3 d-flex align-items-center justify-content-start">
                            <input class="form-check-input" type="checkbox" name="shipresidential" id="shipresidential" style="width: 20px; height: 20px; border: 1px solid rgba(44, 44, 44, 0.705); accent-color: black;">
                            <label class="form-check-label mx-2" for="shipresidential" style="font-weight: bold; color: black;">
                                <!--I'm shipping to a residential address-->
                                This is a business address
                            </label>
                        </div>
                    </div>


                </div>

                <h4 class="text-primary mt-4 sh-header my-4">Receiver Information</h4>
                {{-- <input type="text" name="recipientCountryCode" value="{{ "{$recipientCountryName} - {$zipcodeTo} ({$recipientstateOrProvinceCode})" }}"
                class="form-control fw-bold mb-3 py-3 text-center" disabled>
                <input type="hidden" name="recipientCountryCode" vvalue="{{ "{$recipientCountryName} - {$zipcodeTo} ({$recipientstateOrProvinceCode})" }}" class="form-control fw-bold"> --}}
                <div class="row">

                    {{-- Recipient Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="recipientName" class="fw-bold mb-2">Recipient Name*</label>
                        <input type="text" class="form-control" id="recipientName" name="recipientName" placeholder="Enter Full Name" value="{{ old('recipientName') }}" style="font-size: 15px; padding-block: 0.7rem;"  required>
                    </div>

                     {{-- Company Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="recipientCompany" class="fw-bold mb-2">Company Name (Optional)</label>
                        <input type="text" class="form-control" id="recipientCompany" name="recipientCompany" placeholder="Company name" style="font-size: 15px; padding-block: 0.7rem;" >
                    </div>

                    {{-- Email Add --}}
                    <div class="col-md-6 mb-3">
                        <label for="recipientEmail" class="fw-bold mb-2">Email Address (Optional)</label>
                        <input type="recipientEmail" class="form-control" id="recipientEmail" name="recipientEmail" placeholder="Email Address"style="font-size: 15px; padding-block: 0.7rem;" >
                    </div>



                    <div class="col-md-6 mb-3">
                        <label for="recipientPhone" class="fw-bold mb-2">The recipient's phone number*</label>
                        <input type="tel" class="form-control" id="recipientPhone" name="recipientPhone"
                        placeholder="(604) 555-7890" minlength="10" maxlength="15"
                        value="{{ old('recipientPhone') }}" required style="font-size: 15px; padding-block: 0.7rem;">


                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                @if ($error == "Phone Number is wrong!")
                                    <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                                        <p>{{ $error }}</p>
                                    </div>

                            @endif
                            @endforeach
                         @endif
                    </div>

                    {{-- Recipient Street 1 --}}
                    <div class="col-md-12 mb-3">
                        <label for="recipientStreet" class="fw-bold mb-2">Recipient Street* </label>
                        <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="123 Maple St" style="font-size: 15px; padding-block: 0.7rem;" value="{{ session('recipientStreet', $recipientStreet) }}" maxlength="35" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="recipientStreet1" name="recipientStreet1" placeholder="Street 2 (Optional)" style="font-size: 15px; padding-block: 0.7rem;" maxlength="35">
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="recipientStreet2" name="recipientStreet2" placeholder="Street 3 (Optional)" style="font-size: 15px; padding-block: 0.7rem;" maxlength="35">
                    </div>

                    {{-- recipient City --}}
                    <div class="col-md-3 mb-3">
                        <label for="recipientCity" class="fw-bold mb-2">City*</label>
                        <input type="text" class="form-control" id="recipientCity" name="recipientCity" placeholder="New York City" maxlength="35" value="{{ session('recipientCity', $recipientCity) }}" style="font-size: 15px; padding-block: 0.7rem;" required >
                    </div>




                    {{-- recipient Postal --}}
                    <div class="col-md-3 mb-3">
                        <label for="zipcodeTo" class="fw-bold mb-2">Zip/Postal Code *</label>
                        <input type="text" class="form-control" name="zipcodeTo" id="zipcodeTo" placeholder="Postal/Zip Code" value="{{ session('zipcodeTo', $zipcodeTo) }}" style="font-size: 15px; padding-block: 0.7rem;" required>
                    </div>


                    {{-- Shipper State --}}
                    <div class="col-md-3 mb-3">
                        <label for="recipientstateOrProvinceCode" class="fw-bold mb-2">Province/State Code</label>
                        <input type="text" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="AR (Argentina)" value="{{ old('recipientstateOrProvinceCode', $recipientstateOrProvinceCode ) }}" style="font-size: 15px; padding-block: 0.7rem;" required readonly>

                    </div>


                    {{-- recipient Country --}}
                    <div class="col-md-3 mb-3">
                        <label for="recipientCountryName" class="fw-bold mb-2">Country *</label>
                        <input type="text" class="form-control" name="recipientCountryName" id="recipientCountryName" placeholder="United States" value="{{ session('recipientCountryName', $recipientCountryName) }}" style="font-size: 15px; padding-block: 0.7rem;" required readonly>
                    </div>



                    {{-- <div class="col-md-6 mb-3"> --}}
                        {{-- <label for="recipientstateOrProvinceCode" class="fw-bold mb-2">Province/State Code</label> --}}
                        <input type="hidden" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="ON (Ontario)" required value="{{ old('recipientstateOrProvinceCode', $recipientstateOrProvinceCode) }}" max="2" style="font-size: 15px; padding-block: 0.7rem;" readonly>
                    {{-- </div> --}}
                    {{-- <div class="col-md-6 mb-3">
                        <label for="recipientCity" class="fw-bold mb-2">City*</label>
                        <input type="text" class="form-control" id="recipientCity" name="recipientCity" placeholder="Toronto" maxlength="35" style="font-size: 15px; padding-block: 0.7rem;"  required>
                    </div> --}}
                    {{-- <div class="col-md-6 mb-1"> --}}
                        {{-- <label for="zipcodeTo" class="fw-bold mb-2">Postal Code</label> --}}
                        <input type="hidden" class="form-control" id="zipcodeTo" value="{{ session('zipcodeTo', $zipcodeTo) }}" style="font-size: 15px; padding-block: 0.7rem;" readonly>
                    {{-- </div> --}}
                </div>


                <div class="col-md-12 my-3">
                    <!-- Residential Address Checkbox -->
                    <div class="form-check my-3 d-flex align-items-center justify-content-start">
                        <input class="form-check-input" type="checkbox" name="residential" id="residential" style="width: 20px; height: 20px; border: 1px solid rgba(44, 44, 44, 0.705); accent-color: black;">
                        <label class="form-check-label mx-2" for="residential" style="font-weight: bold; color: black;">
                            <!--I'm shipping to a residential address-->
                            This is a business address
                        </label>
                    </div>
                </div>


        </div>




        </div>

        <!-- Shipment Details -->
        <div class="card mb-4 my-4 p-3" data-aos="zoom-in-down" data-aos-duration="700">
            <div class="card-header">
                <h4 class="text-primary mt-4 sh-header">Shipment Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Ship Date Dropdown -->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label for="shipDate" class="mb-2 fw-bold">Ship Date* (When do you want to ship?)</label>
                        <select class="form-control" id="shipDate" name="shipDate" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                            @for ($i = 0; $i <= 8; $i++)
                                <option value="{{ now()->addDays($i)->format('Y-m-d') }}">
                                    {{ now()->addDays($i+1)->format('Y-m-d') }}
                                </option>
                            @endfor
                        </select>
                        </div>
                    </div>


                    <!-- Packaging Type -->

                    {{-- <div class="col-md-6 mb-3">
                        <input type="hidden" id="weightInput" value="{{ session('weight') }}">
                        <label for="packagingType">Packaging Type</label>
                        <select class="form-control" id="packagingType" name="packagingType" required>
                            <option value="YOUR_PACKAGING">Customer Packaging - 150 lbs/68 KG (Express)</option>
                            <option value="YOUR_PACKAGING">Customer Packaging - 70 lbs/32 KG (Ground)</option>
                            <option value="YOUR_PACKAGING">Customer Packaging - 70 lbs/32 KG (Economy)</option>
                            <option value="FEDEX_ENVELOPE" data-weight="1">FedEx Envelope - 1 lbs/0.5 KG</option>
                            <option value="FEDEX_BOX" data-weight="20">FedEx Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_SMALL_BOX" data-weight="20">FedEx Small Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_MEDIUM_BOX" data-weight="20">FedEx Medium Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_LARGE_BOX" data-weight="20">FedEx Large Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_EXTRA_LARGE_BOX" data-weight="20">FedEx Extra Large Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_10KG_BOX" data-weight="22">FedEx 10kg Box - 22 lbs/10 KG</option>
                            <option value="FEDEX_25KG_BOX" data-weight="55">FedEx 25kg Box - 55 lbs/25 KG</option>
                            <option value="FEDEX_PAK" data-weight="20">FedEx Pak - 20 lbs/9 KG</option>
                            <option value="FEDEX_TUBE" data-weight="20">FedEx Tube - 20 lbs/9 KG</option>
                        </select>
                    </div> --}}


                    <!-- Pickup Type -->
                    <div class="col-md-6">
                        <label for="pickupType" class="fw-bold mb-2">Pickup Type</label>
                        <select class="form-control" id="pickupType" name="pickupType" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                            <option value="USE_SCHEDULED_PICKUP">Use Scheduled Pickup</option>
                            <option value="CONTACT_FEDEX_TO_SCHEDULE">Contact FedEx to Schedule</option>
                            <option value="DROPOFF_AT_FEDEX_LOCATION">Dropoff at FedEx Location</option>
                        </select>

                        {{-- <input type="hidden" name="pickupType" id="pickupType" value="DROPOFF_AT_FEDEX_LOCATION" style=" font-size: 15px; padding-block: 0.7rem;" class="form-control" readonly> --}}
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="fw-bold mb-2">Package Description*</label>
                             <input type="text-box" class="form-control" placeholder="Camera - description" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" name="description" required>

                        </div>
                        {{-- <div class="mb-3">
                            <label for="description" class="fw-bold mb-2">Package Description*</label>
                            <textarea id="description" name="description" rows="3" placeholder="Brief description about your goods." class="form-control" style="background-color: #eeeeee;"></textarea> --}}

                        {{-- </div> --}}

                    </div>



                    <div class="col-md-6">
                        <div class=" mb-3">
                            <label class="fw-bold mb-2">Package Value* ($)</label>

                        {{-- <span class="input-group-text">$</span> --}}
                        <input type="number" class="form-control" placeholder="Enter your estimated amount" name="customsValueAmount" min='1' style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>


                    </div>
                    </div>
                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="mb-3"> --}}
                            {{-- <label class="fw-bold mb-2">Image Type*</label> --}}
                            {{-- <select class="form-control" name="imageType" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                                <option value="PDF" selected>PDF</option>
                                <option value="ZPLII">ZPLII</option>
                                <option value="EPL2">EPL2</option>
                                <option value="PNG">PNG</option>
                            </select> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Dimensions* L × W × H (Inches)</label>
                            <div class="d-flex flex-wrap align-items-center dimensions-wrapper">
                                <div class="col-md-3 col-6 px-0 pe-2">
                                    <input type="number" class="form-control dimension-input" name="length" placeholder="10" min="1" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                                </div>
                                <span class="mx-2 d-none d-md-inline">×</span>
                                <div class="col-md-3 col-6 px-0 pe-2">
                                    <input type="number" class="form-control dimension-input" name="width" placeholder="10" min="1" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                                </div>
                                <span class="mx-2 d-none d-md-inline">×</span>
                                <div class="col-md-3 col-6 px-0">
                                    <input type="number" class="form-control dimension-input" name="height" placeholder="10" min="1" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                                </div>
                                <input type="hidden" class="form-control dimension-input" name="dimension_unit" id="dimension_unit" value="IN" style="background-color: #eeeeee; font-size: 15px; padding-block: 0.7rem;" required>
                                <input type="hidden" name="packagingType" value="YOUR_PACKAGING">
                            </div>
                        </div>
                    </div>






                </div>
            </div>
        </div>

                    <input type="hidden" value="1" class="form-control" name="customsValueQuantity" required>
                    <input type="hidden" name="labelStockType" value="PAPER_85X11_TOP_HALF_LABEL">
                    <input type="hidden" name="imageType" value="PDF">
                    <input type="hidden" name="labelResponseOptions" value="URL_ONLY">
                {{-- </div> --}}
            {{-- </div> --}}
            <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-duration="700">
            <button type="submit" class="btn btn-primary btn-lg" id="btn-submit" style="border-radius: 40px; padding-inline: 30px;">Request Quote</button>
        </div>
        </div>


    </form>
</div>
</body>
<script>
    document.getElementById('calc-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission until validation is done
        let shipperPhone = document.getElementById('shipperPhone').value;
        let phoneError = document.getElementById('phone-error');
        phoneError.style.display = 'none'; // Clear previous error message

        // Remove non-digit characters for validation
        let cleanedPhone = shipperPhone.replace(/\D/g, '');

        // Check length and format
        if (cleanedPhone.length < 10 || cleanedPhone.length > 15) {
            phoneError.textContent = 'Phone number must be between 10 and 15 digits long.';
            phoneError.style.display = 'block';
            return;
        }

        // For US and CA: must have exactly 10 digits, with an optional leading country code '1' or '+1'
        if ((cleanedPhone.length === 10 || (cleanedPhone.length === 11 && cleanedPhone.startsWith('1'))) ||
            (cleanedPhone.length === 12 && cleanedPhone.startsWith('+1'))) {
            // Phone number is valid, continue with form submission
            this.submit();
        } else {
            phoneError.textContent = 'For US and CA, a phone number must have exactly 10 digits, plus an optional leading country code of "1" or "+1".';
            phoneError.style.display = 'block';
        }
    });
</script>





@endsection
{{-- @section('js_content', 'js/locatorRadar.js') --}}
{{-- @section('js_content', 'js/locatorRadar.js') --}}


