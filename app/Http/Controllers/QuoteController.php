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

        //  $countries = Country::all();

        // dd($data);


    return view('shipments.shipment',  $data);


    }


    public function getFullQuote(Request $request)
    {


        try {

            // Collect Shipper Inputs
        $f_shipperName = $request->input('shipperName');
        $f_shipperCompany = $request->input('shipperCompany');
        $f_shipperEmail = $request->input('shipperEmail');
        $f_shipperPhone = $request->input('shipperPhone');
        $f_shipperStreet = $request->input('shipperStreet');
        $f_shipperstateOrProvinceCode = $request->input('shipperstateOrProvinceCode');
        $f_shipperCity = $request->input('shipperCity');
        $f_zipcodeFrom = $request->input('zipcodeFrom');
        $f_shipperCountryName = $request->input('shipperCountryName');

        // Collect Recipient Inputs
        $f_recipientName = $request->input('recipientName');
        $f_recipientCompany = $request->input('recipientCompany');
        $f_recipientEmail = $request->input('recipientEmail');
        $f_recipientPhone = $request->input('recipientPhone');
        $f_recipientStreet = $request->input('recipientStreet');
        $f_recipientStreet1 = $request->input('recipientStreet1') ?? "";
        $f_recipientStreet2 = $request->input('recipientStreet2') ?? "";
        $f_recipientstateOrProvinceCode = $request->input('recipientstateOrProvinceCode');
        $f_recipientCity = $request->input('recipientCity');
        $f_zipcodeTo = $request->input('zipcodeTo');
        $f_recipientCountryName = $request->input('recipientCountryName');

        // Collect Other Inputs
        $f_pickupType = $request->input('pickupType');
        $f_description = $request->input('description');
        $f_length = $request->input('length');
        $f_width = $request->input('width');
        $f_height = $request->input('height');
        $f_dimension_unit = $request->input('dimension_unit');
        $f_packagingType = $request->input('packagingType');
        $f_customsValueAmount = $request->input('customsValueAmount');
        $f_customsValueQuantity = $request->input('customsValueQuantity');
        // $f_labelStockType = $request->input('labelStockType');
        $f_labelStockType = "PAPER_85X11_TOP_HALF_LABEL";
        $f_imageType = $request->input('imageType');
        $f_labelResponseOptions = $request->input('labelResponseOptions');
        $f_shipDate = $request->input('shipDate');

        $request->merge(['residential' => $request->input('residential', false)]);
        $f_isResidential = $request->input('residential');
        $f_residential = $f_isResidential ? false : true;

        $request->merge(['shipresidential' => $request->input('shipresidential', false)]);
        $f_shipresidential = $request->input('shipresidential');
        $f_sresidential = $f_shipresidential ? false : true;


            // dd($f_shipresidential);
            // dd($request->all())


        // dd($f_labelStockType);



        $f_postalInputShipper = '';
        $f_postalInputRecipient = '';

        if ($f_zipcodeFrom === "00000") {
            $f_postalInputShipper = $f_shipperstateOrProvinceCode;
            // dump($f_postalInputShipper);
        } else {
            $f_postalInputShipper = $f_zipcodeFrom;
            // dump($f_postalInputShipper);
        }

        if ($f_zipcodeTo === "00000") {
            $f_postalInputRecipient = $f_recipientstateOrProvinceCode;
            // dump($f_postalInputRecipient);
        } else {
            $f_postalInputRecipient = $f_zipcodeTo;
            // dump($f_postalInputRecipient);
        }

        // Store inputs in session
        session([
            // Shipper Data
            'f_shipperName' => $f_shipperName,
            'f_shipperCompany' => $f_shipperCompany ?? "Company Name",
            'f_shipperEmail' => $f_shipperEmail ?? "",
            'f_shipperPhone' => $f_shipperPhone,
            'f_shipperStreet' => $f_shipperStreet,
            'f_shipperstateOrProvinceCode' => $f_shipperstateOrProvinceCode,
            'f_shipperCity' => $f_shipperCity,
            'f_zipcodeFrom' => $f_zipcodeFrom,
            'f_shipperCountryName' => $f_shipperCountryName,

            // Recipient Data
            'f_recipientName' => $f_recipientName,
            'f_recipientCompany' => $f_recipientCompany  ?? "Company Name",
            'f_recipientEmail' => $f_recipientEmail ?? "",
            'f_recipientPhone' => $f_recipientPhone,
            'f_recipientStreet' => $f_recipientStreet,
            'f_recipientStreet1' => $f_recipientStreet1 ?? "Street 2",
            'f_recipientStreet2' => $f_recipientStreet2 ?? "Street 3",
            'f_recipientstateOrProvinceCode' => $f_recipientstateOrProvinceCode,
            'f_recipientCity' => $f_recipientCity,
            'f_zipcodeTo' => $f_zipcodeTo,
            'f_recipientCountryName' => $f_recipientCountryName,

            // Other Inputs
            'f_pickupType' => $f_pickupType,
            'f_description' => $f_description,
            'f_length' => $f_length,
            'f_width' => $f_width,
            'f_height' => $f_height,
            'f_dimension_unit' => $f_dimension_unit,
            'f_packagingType' => $f_packagingType,
            'f_customsValueAmount' => $f_customsValueAmount,
            'f_customsValueQuantity' => $f_customsValueQuantity,
            'f_labelStockType' => $f_labelStockType,
            'f_imageType' => $f_imageType,
            'f_labelResponseOptions' => $f_labelResponseOptions,
            'f_residential' => $f_residential,
            'f_sresidential' => $f_sresidential,
            'f_shipDate' => $f_shipDate,
        ]);



        $data = [
            // Custom and Label Information
            'f_customQty' => session('f_customsValueQuantity'),
            'f_customAmount' => session('f_customsValueAmount'),
            'f_labelResponseOptions' => session('f_labelResponseOptions'),

            // Recipient Information
            'f_recipientPhone' => session('f_recipientPhone'),
            'f_recipientName' => session('f_recipientName'),
            'f_recipientStreet' => session('f_recipientStreet'),
            'f_recipientCity' => session('f_recipientCity'),
            'f_recipientstateOrProvinceCode' => session('f_recipientstateOrProvinceCode'),
            'f_recipientCountryName' => session('f_recipientCountryName'),
            'f_zipcodeTo' => session('f_zipcodeTo'),
            'f_recipientCompany' => session('f_recipientCompany'),
            'f_recipientEmail' => session('f_recipientEmail'),
            'f_recipientStreet1' => session('f_recipientStreet1'),
            'f_recipientStreet2' => session('f_recipientStreet2'),

            // Shipper Information
            'f_shipperPhone' => session('f_shipperPhone'),
            'f_shipperName' => session('f_shipperName'),
            'f_shipperStreet' => session('f_shipperStreet'),
            'f_shipperCity' => session('f_shipperCity'),
            'f_shipperstateOrProvinceCode' => session('f_shipperstateOrProvinceCode'),
            'f_shipperCountryName' => session('f_shipperCountryName'),
            'f_zipcodeFrom' => session('f_zipcodeFrom'),
            'f_shipperCompany' => session('f_shipperCompany'),
            'f_shipperEmail' => session('f_shipperEmail'),


            // Other Inputs
            'f_fromCountry' => session('fromCountry'),
            'f_toCountry' => session('toCountry'),
            'f_weight' => session('weight'),
            'f_serviceType' => session('serviceType'),
            'ff_totalNetCharge' => session('totalNetCharge'),
            'f_residential' => session('f_residential'),
            'f_sresidential' => session('f_sresidential'),
            'f_description' => session('f_description'),
            'f_length' => session('f_length'),
            'f_width' => session('f_width'),
            'f_height' => session('f_height'),
            'f_dimension_unit' => session('f_dimension_unit'),
            'f_packagingType' => session('f_packagingType'),
            'f_shipDate' => session('f_shipDate'),
            'f_pickupType' => session('f_pickupType'),
            'f_labelStockType' => session('f_labelStockType'),

            // Postal Information
            'f_postalInputShipper' => session('postalInputShipper'),
            'f_postalInputRecipient' => session('postalInputRecipient'),
        ];


        // dd($data);




                $fullRate = $this->fedExService->getFullQuote(
                    $data['f_fromCountry'],
                    $data['f_toCountry'],
                    $data['f_weight'],
                    $data['f_postalInputRecipient'],
                    $data['f_postalInputShipper'],
                    $data['f_shipDate'],
                    $data['f_residential'],
                    $data['f_sresidential'],
                    $data['f_serviceType'],
                    $data['f_description'],
                    $data['f_length'],
                    $data['f_width'],
                    $data['f_height'],
                    $data['f_dimension_unit'],
                    $data['f_packagingType'],
                    $data['f_customAmount'],
                    $data['f_customQty'],
                );

                // dump($fullRate);

                return view('shipments.getFullQuote', compact('fullRate', 'data'));

            }catch (\Exception $e) {
                $error = json_decode($e->getMessage(),true);

                dd($error);
                return view('shipments.getFullQuote', compact('data'));

            }
}


    // public function createdShipment(Request $request)
    // {


    //     try {
    //        // Retrieve session values
    //      $inWeight = session('weight');
    //      $weight = floatval($inWeight);

    //      $shipperName = $request->input('shipperName');
    //      $shipperPhone = $request->input('shipperPhone');
    //      $recipientName = $request->input('recipientName');
    //      $recipientPhone = $request->input('recipientPhone');
    //      $labelStockType = 'PAPER_85X11_TOP_HALF_LABEL';
    //      $labelResponseOptions = 'URL_ONLY';
    //      $customAmount =100;
    //      $customQty = 1;

    //      $request->merge(['residential' => $request->input('residential', false)]);
    //      $isResidential = $request->input('residential') ;
    //      $residential = $isResidential ? true : false;

    //     $description = $request->input('description');
    //     $length = $request->input('length');
    //     $width = $request->input('width');
    //     $height = $request->input('height');
    //     $dimension_unit = $request->input('dimension_unit');
    //     $shipDate = $request->input('shipDate');
    //     $pickupType = $request->input('pickupType');
    //     $packagingType = $request->input('packagingType');


    //     session([
    //         'description' => $description,
    //         'length' => $length,
    //         'width' => $width,
    //         'height' => $height,
    //         'dimension_unit' => $dimension_unit,
    //         'shipDate' => $shipDate,
    //         'pickupType' => $pickupType,
    //         'residential' => $residential,
    //         'packagingType' => $packagingType,
    //         'labelStockType' => $labelStockType,
    //     ]);


    //     // dd($request->all());

    //      $data = [
    //         'fromCountry' => session('fromCountry'),
    //         'toCountry' => session('toCountry'),
    //         'weight' => session('weight'),
    //         'serviceType' => session('serviceType'),
    //         'totalNetCharge' => session('totalNetCharge'),
    //         'recipientStreet' => session('recipientStreet'),
    //         'recipientCity' => session('recipientCity'),
    //         'recipientstateOrProvinceCode' => session('recipientstateOrProvinceCode'),
    //         'shipperStreet' => session('shipperStreet'),
    //         'shipperCity' => session('shipperCity'),
    //         'shipperstateOrProvinceCode' => session('shipperstateOrProvinceCode'),
    //         'zipcodeFrom' => session('zipcodeFrom'),
    //         'zipcodeTo' => session('zipcodeTo'),
    //         'residential' => session('residential'),
    //         'description' => session('description'),
    //         'length' => session('length'),
    //         'width' => session('width'),
    //         'height' => session('height'),
    //         'dimension_unit' => session('dimension_unit'),
    //         'packagingType' => session('packagingType'),
    //         'shipDate' => session('shipDate'),
    //         'shipperCountryName' => session('shipperCountryName'),
    //         'recipientCountryName' => session('recipientCountryName'),
    //         'pickupType' => session('pickupType'),
    //     ];

    //     dd($data);


    //     $shipRequest = $this->fedExService->BookNow(
    //         $shipperName, // $shipperName
    //         $shipperPhone, // $shipperPhone
    //         $data['shipperStreet'], // $shipperStreet
    //         $data['shipperCity'], // $shipperCity
    //         $data['fromCountry'], // $shipperCountryCode
    //         $recipientName, // $recipientName
    //         $recipientPhone, // $recipientPhone
    //         $data['recipientStreet'], // $recipientStreet
    //         $data['recipientCity'], // $recipientCity
    //         $data['toCountry'], // $recipientCountryCode
    //         $data['shipDate'], // $shipDate
    //         $data['packagingType'], // $packagingType
    //         $data['pickupType'], // $pickupType
    //         $labelStockType, // $labelStockType
    //         $labelResponseOptions, // $labelResponseOptions
    //         $weight, // $weight
    //         $data['serviceType'], // $serviceType
    //         $data['zipcodeFrom'], // $fromZip
    //         $data['zipcodeTo'], // $toZip
    //         $data['shipperstateOrProvinceCode'], // $shipperstateOrProvinceCode
    //         $data['recipientstateOrProvinceCode'], // $recipientstateOrProvinceCode
    //         $data['totalNetCharge'], // $totalNetCharge
    //         $customAmount, // $customAmount
    //         $customQty, // $customQty
    //         // $data['length'],
    //         // $data['width'],
    //         // $data['height'],
    //         // $data['dimension_unit'],
    //     );



    //         //  dd($shipRequest);
    //         // dd($shipRequest);

    //     // dd($shipRequest);





    //         $value = session('reqErrorResponse');

    //         if($value =="test"){
    //         return redirect()->back()->withInput()->withErrors("Error");

    //         }else{
    //             session([
    //                 'trackingId' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['trackingNumber'],
    //                 'trackingUrl' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['url'],
    //                 'serviceTyped' => $shipRequest['output']['transactionShipments'][0]['serviceType'],
    //             ]);



    //             // return view('shipments.createdShipment', compact('totalWithPackage'));


    //             $paymentData = [
    //                 'payment_id' => session('payment_id'),
    //                 'payer_id' => session('payer_id'),
    //                 'payer_email' => session('payer_email'),
    //                 'amount' => session('amount'),
    //                 'currency' => session('currency'),
    //                 'status' => session('status'),
    //             ];


    //             // dd($paymentData);

    //             return view('paypal.success', [
    //                 'payment' => $paymentData, // Pass payment data to the view
    //             ]);
    //         }





    //     } catch (\Exception $e) {
    //         Log::error('Error fetching FedEx rates: ' . $e->getMessage());
    //         dd($e->getMessage());
    //         return redirect()->back()->withInput()->withErrors('Invalid Credentials',$e->getMessage() );

    //     }

    // }







}
