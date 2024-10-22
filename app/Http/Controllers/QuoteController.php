<?php

namespace App\Http\Controllers;

use App\Services\FedExService;
use Illuminate\Http\Request;
use App\Models\Country;
use FFI\CData;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use PhpParser\JsonDecoder;

class QuoteController extends Controller
{
    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    // public function countryDb()
    // {
    //     // $countries = Country::all();
    //     // return view('mainpage.home', compact('countries'));
    //     return view('mainpage.home');
    // }

    public function getToken(Request $request)
    {
        // Fetch and display the OAuth token for debugging
        dd($this->fedExService->getAuthToken());
    }


    public function getQuote(Request $request)
    {
        // Validate form inputs
        $validatedData = $request->validate([
            'fromCountry'                    => 'required|string|max:2',     // Example: US, UK
            'toCountry'                      => 'required|string|max:2',
            'zipcodeFrom'                    => 'required|string|max:10',
            'zipcodeTo'                      => 'required|string|max:10',
            'weight'                         => 'required|numeric|min:1',
            'reFormattedAddress'                => 'required|string|max:255',   // Street address
            'recipientCity'                  => 'required|string|max:100',   // City name
            'recipientstateOrProvinceCode'   => 'required|string|max:10',    // State/Province code
            'shFormattedAddress'                  => 'required|string|max:255',   // Shipper's street address
            'shipperCity'                    => 'required|string|max:100',   // Shipper's city
            'shipperstateOrProvinceCode'     => 'required|string|max:10',    // Shipper's state/province code
            'shipperCountryName'             => 'required|string|max:255',
            'recipientCountryName'           => 'required|string|max:255',
            'weight_unit' => 'required|string|max:255',

        ]);
        // try {
        // Retrieve form data
        $fromCountry = $request->input('fromCountry');
        $toCountry = $request->input('toCountry');
        $fromZip = $request->input('zipcodeFrom');
        $toZip = $request->input('zipcodeTo');
        $weight = $request->input('weight');
        $weight_unit = $request->input('weight_unit');

        // dd($weight_unit);

        $recipientStreet = $request->input('reFormattedAddress');
        $recipientCity = $request->input('recipientCity');
        $recipientstateOrProvinceCode = $request->input('recipientstateOrProvinceCode');
        $shipperStreet = $request->input('shFormattedAddress');
        $shipperCity = $request->input('shipperCity');
        $shipperstateOrProvinceCode = $request->input('shipperstateOrProvinceCode');
        $shipperCountryName = $request->input('shipperCountryName');
        $recipientCountryName = $request->input('recipientCountryName');


        // dd($shipperStreet, $recipientStreet);


        $postalInputShipper = '';
        $postalInputRecipient = '';

        if ($fromZip === "00000") {
            $postalInputShipper = $shipperstateOrProvinceCode;
            // dump($postalInputShipper);
        } else {
            $postalInputShipper = $fromZip;
            // dump($postalInputShipper);
        }

        if ($toZip === "00000") {
            $postalInputRecipient = $recipientstateOrProvinceCode;
            // dump($postalInputRecipient);
        } else {
            $postalInputRecipient = $toZip;
            // dump($postalInputRecipient);
        }


        // dd($request->all());



        // dd($validatedData);

        session([
            'recipientStreet' => $recipientStreet,
            'recipientCity' => $recipientCity,
            'recipientstateOrProvinceCode' => $recipientstateOrProvinceCode,
            'shipperStreet' => $shipperStreet,
            'shipperCity' => $shipperCity,
            'shipperstateOrProvinceCode' => $shipperstateOrProvinceCode,
            'fromCountry' => $fromCountry,
            'toCountry' => $toCountry,
            'weight' => $weight,
            'zipcodeFrom' => $fromZip,
            'zipcodeTo' => $toZip,
            'shipperCountryName' => $shipperCountryName,
            'recipientCountryName' => $recipientCountryName,
            'postalInputShipper' => $postalInputShipper,
            'postalInputRecipient' => $postalInputRecipient,
            'weight_unit' => $weight_unit,
        ]);


        try {
            // Call FedEx service to get rates
            $rates = $this->fedExService->getQuote(
                $fromCountry,
                $toCountry,
                $weight,
                $postalInputRecipient,
                $postalInputShipper,
                $weight_unit
            );
            // dd($rates);

            return view('shipments.reqShipment', compact('rates', 'validatedData'));

        } catch (\Exception $e) {


            $error = json_decode($e->getMessage(),true);

            // dd($error['errors'][0]['code']);

            // if (isset($error['errors'][0]['code'])) {
            //     $jsonErr = $error['errors'][0]['code'];

            //     switch ($jsonErr) {
            //         case "DESTINATION.POSTALCODE.MISSING.ORINVALID":
            //             session()->flash('error', 'Invalid country code. Please double-check.');
            //             break;

            //         case "SERVICE.PACKAGECOMBINATION.INVALID":
            //             session()->flash('error', 'Invalid service and packaging combination. Please update and try again.');
            //             break;

            //         case "RECIPIENT.COUNTRY.UNSERVICED":
            //             session()->flash('error', 'Invalid recipient country code.');
            //             break;
            //         case "SYSTEM.UNAVAILABLE.EXCEPTION":
            //             session()->flash('error', 'We are unable to process this request. Please try again later or contact FedEx Customer Service.');
            //             break;

            //         default:
            //             session()->flash('error', 'An error occurred while requesting data. Please try again!');
            //             break;
            //     }
            // } else {
            //     session()->flash('error', 'An error occurred while processing your request.');
            // }

           return view('shipments.reqShipment', compact('validatedData'));
        }

    }



    public function shipping(Request $request){

        $serviceType = $request->input('serviceType');
        $totalNetCharge = $request->input('totalNetCharge');



        session([
            'serviceType' => $serviceType,
            'totalNetCharge' => $totalNetCharge,
        ]);

        // Retrieve session data
        $data = [
            'fromCountry' => session('fromCountry'),
            'toCountry' => session('toCountry'),
            'weight' => session('weight'),
            'recipientStreet' => session('recipientStreet'),
            'recipientCity' => session('recipientCity'),
            'recipientstateOrProvinceCode' => session('recipientstateOrProvinceCode'),
            'shipperStreet' => session('shipperStreet'),
            'shipperCity' => session('shipperCity'),
            'shipperstateOrProvinceCode' => session('shipperstateOrProvinceCode'),
            'zipcodeFrom' => session('zipcodeFrom'),
            'zipcodeTo' => session('zipcodeTo'),
            'shipperCountryName' => session('shipperCountryName'),
            'recipientCountryName' => session('recipientCountryName'),
            'serviceType' => $serviceType,
            'totalNetCharge' => $totalNetCharge,
        ];



    return view('shipments.shipment', $data);


    }


    public function createdShipment(Request $request)
    {


        try {
           // Retrieve session values
         $inWeight = session('weight');
         $weight = floatval($inWeight);

         $shipperName = $request->input('shipperName');
         $shipperPhone = $request->input('shipperPhone');
         $recipientName = $request->input('recipientName');
         $recipientPhone = $request->input('recipientPhone');
         $labelStockType = 'PAPER_85X11_TOP_HALF_LABEL';
         $labelResponseOptions = 'URL_ONLY';
         $customAmount =100;
         $customQty = 1;

         $request->merge(['residential' => $request->input('residential', false)]);
         $isResidential = $request->input('residential') ;
         $residential = $isResidential ? true : false;

        $description = $request->input('description');
        $length = $request->input('length');
        $width = $request->input('width');
        $height = $request->input('height');
        $dimension_unit = $request->input('dimension_unit');
        $shipDate = $request->input('shipDate');
        $pickupType = $request->input('pickupType');
        $packagingType = $request->input('packagingType');


        session([
            'description' => $description,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'dimension_unit' => $dimension_unit,
            'shipDate' => $shipDate,
            'pickupType' => $pickupType,
            'residential' => $residential,
            'packagingType' => $packagingType,
            'labelStockType' => $labelStockType,
        ]);


        // dd($request->all());

         $data = [
            'fromCountry' => session('fromCountry'),
            'toCountry' => session('toCountry'),
            'weight' => session('weight'),
            'serviceType' => session('serviceType'),
            'totalNetCharge' => session('totalNetCharge'),
            'recipientStreet' => session('recipientStreet'),
            'recipientCity' => session('recipientCity'),
            'recipientstateOrProvinceCode' => session('recipientstateOrProvinceCode'),
            'shipperStreet' => session('shipperStreet'),
            'shipperCity' => session('shipperCity'),
            'shipperstateOrProvinceCode' => session('shipperstateOrProvinceCode'),
            'zipcodeFrom' => session('zipcodeFrom'),
            'zipcodeTo' => session('zipcodeTo'),
            'residential' => session('residential'),
            'description' => session('description'),
            'length' => session('length'),
            'width' => session('width'),
            'height' => session('height'),
            'dimension_unit' => session('dimension_unit'),
            'packagingType' => session('packagingType'),
            'shipDate' => session('shipDate'),
            'shipperCountryName' => session('shipperCountryName'),
            'recipientCountryName' => session('recipientCountryName'),
            'pickupType' => session('pickupType'),
        ];

        dd($data);


        $shipRequest = $this->fedExService->BookNow(
            $shipperName, // $shipperName
            $shipperPhone, // $shipperPhone
            $data['shipperStreet'], // $shipperStreet
            $data['shipperCity'], // $shipperCity
            $data['fromCountry'], // $shipperCountryCode
            $recipientName, // $recipientName
            $recipientPhone, // $recipientPhone
            $data['recipientStreet'], // $recipientStreet
            $data['recipientCity'], // $recipientCity
            $data['toCountry'], // $recipientCountryCode
            $data['shipDate'], // $shipDate
            $data['packagingType'], // $packagingType
            $data['pickupType'], // $pickupType
            $labelStockType, // $labelStockType
            $labelResponseOptions, // $labelResponseOptions
            $weight, // $weight
            $data['serviceType'], // $serviceType
            $data['zipcodeFrom'], // $fromZip
            $data['zipcodeTo'], // $toZip
            $data['shipperstateOrProvinceCode'], // $shipperstateOrProvinceCode
            $data['recipientstateOrProvinceCode'], // $recipientstateOrProvinceCode
            $data['totalNetCharge'], // $totalNetCharge
            $customAmount, // $customAmount
            $customQty, // $customQty
            // $data['length'],
            // $data['width'],
            // $data['height'],
            // $data['dimension_unit'],
        );



            //  dd($shipRequest);
            // dd($shipRequest);

        // dd($shipRequest);





            $value = session('reqErrorResponse');

            if($value =="test"){
            return redirect()->back()->withInput()->withErrors("Error");

            }else{
                session([
                    'trackingId' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['trackingNumber'],
                    'trackingUrl' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['url'],
                    'serviceTyped' => $shipRequest['output']['transactionShipments'][0]['serviceType'],
                ]);



                // return view('shipments.createdShipment', compact('totalWithPackage'));


                $paymentData = [
                    'payment_id' => session('payment_id'),
                    'payer_id' => session('payer_id'),
                    'payer_email' => session('payer_email'),
                    'amount' => session('amount'),
                    'currency' => session('currency'),
                    'status' => session('status'),
                ];


                // dd($paymentData);

                return view('paypal.success', [
                    'payment' => $paymentData, // Pass payment data to the view
                ]);
            }





        } catch (\Exception $e) {
            Log::error('Error fetching FedEx rates: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors('Invalid Credentials',$e->getMessage() );

        }

    }



    public function getFullQuote(Request $request)
    {


        try {
         $shipperName = $request->input('shipperName');
         $shipperPhone = $request->input('shipperPhone');
         $recipientName = $request->input('recipientName');
         $recipientPhone = $request->input('recipientPhone');
         $labelStockType = 'PAPER_85X11_TOP_HALF_LABEL';
         $labelResponseOptions = 'URL_ONLY';
         $customAmount =100;
         $customQty = 1;

         $request->merge(['residential' => $request->input('residential', false)]);
         $isResidential = $request->input('residential') ;
        //  $residential = $isResidential ? true : false;
         $residential = $isResidential ? false : true;

         dd($residential);

        $description = $request->input('description');
        $length = $request->input('length');
        $width = $request->input('width');
        $height = $request->input('height');
        $dimension_unit = $request->input('dimension_unit');
        $shipDate = $request->input('shipDate');
        $pickupType = $request->input('pickupType');
        $packagingType = $request->input('packagingType');
        $customsValueAmount = $request->input('customsValueAmount');


        // $recipientStreet = $request->input('recipientStreet');
        // $shipperStreet = $request->input('shipperStreet');


        // dd($recipientStreet, $shipperStreet);







        session([
            // 'recipientStreet' => $recipientStreet,
            // 'shipperStreet' => $shipperStreet,
            'description' => $description,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'dimension_unit' => $dimension_unit,
            'shipDate' => $shipDate,
            'pickupType' => $pickupType,
            'residential' => $residential,
            'packagingType' => $packagingType,
            'shipperName' => $shipperName,
            'shipperPhone' => $shipperPhone,
            'recipientName' => $recipientName,
            'recipientPhone' => $recipientPhone,
            'labelResponseOptions' => $labelResponseOptions,
            'customAmount' => $customAmount,
            'customQty' => $customQty,
            'customsValueAmount' => $customsValueAmount,
        ]);



         $data = [
            'customQty' => session('customQty'),
            'customAmount' => session('customAmount'),
            'labelResponseOptions' => session('labelResponseOptions'),
            'recipientPhone' => session('recipientPhone'),
            'recipientName' => session('recipientName'),
            'shipperPhone' => session('shipperPhone'),
            'shipperName' => session('shipperName'),
            'fromCountry' => session('fromCountry'),
            'toCountry' => session('toCountry'),
            'weight' => session('weight'),
            'serviceType' => session('serviceType'),
            'totalNetCharge' => session('totalNetCharge'),
            'recipientStreet' => session('recipientStreet'),
            'recipientCity' => session('recipientCity'),
            'recipientstateOrProvinceCode' => session('recipientstateOrProvinceCode'),
            'shipperStreet' => session('shipperStreet'),
            'shipperCity' => session('shipperCity'),
            'shipperstateOrProvinceCode' => session('shipperstateOrProvinceCode'),
            'zipcodeFrom' => session('zipcodeFrom'),
            'zipcodeTo' => session('zipcodeTo'),
            'residential' => session('residential'),
            'description' => session('description'),
            'length' => session('length'),
            'width' => session('width'),
            'height' => session('height'),
            'dimension_unit' => session('dimension_unit'),
            'packagingType' => session('packagingType'),
            'shipDate' => session('shipDate'),
            'shipperCountryName' => session('shipperCountryName'),
            'recipientCountryName' => session('recipientCountryName'),
            'pickupType' => session('pickupType'),
            'postalInputShipper' => session('postalInputShipper'),
            'postalInputRecipient' => session('postalInputRecipient'),
            'customsValueAmount' => session('customsValueAmount'),

        ];


        // dd($data);


        $fullRate = $this->fedExService->getFullQuote(
            $data['fromCountry'],
            $data['toCountry'],
            $data['weight'],
            $data['postalInputRecipient'],
            $data['postalInputShipper'],
            $data['shipDate'],
            $data['residential'],
            $data['serviceType'],
            $data['description'],
            $data['length'],
            $data['width'],
            $data['height'],
            $data['dimension_unit'],
            $data['packagingType'],
            $data['customsValueAmount'],
        );

        // dd($fullRate);

        return view('shipments.getFullQuote', compact('fullRate', 'data'));

    }catch (\Exception $e) {
        $error = json_decode($e->getMessage(),true);

        dd($error);
        return view('shipments.getFullQuote', compact('data'));

    }
}



}
