<?php

namespace App\Http\Controllers;

use App\Services\FedExService;
use Illuminate\Http\Request;
use App\Models\Country;
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
        // Retrieve form data
        $fromCountry = $request->input('fromCountry');
        $toCountry = $request->input('toCountry');
        $fromZip = $request->input('zipcodeFrom');
        $toZip = $request->input('zipcodeTo');
        $weight = $request->input('weight');



        $request->merge(['residential' => $request->input('residential', false)]);
        $isResidential = $request->input('residential') ;
        $residential = $isResidential ? true : false;

        $description = $request->input('description');
        $length = $request->input('length') ?? null;
        $width = $request->input('width') ?? null;
        $height = $request->input('height') ?? null;
        $units = $request->input('dimension_unit') ?? null;
        $packagingType = $request->input('packagingType');
        $shipDate = $request->input('shipDate');

        // dd($fromCountry, $toCountry, $fromZip, $toZip, $weight);

        // Inputs to be passed on shipment view
        $recipientStreet = $request->input('recipientStreet');
        $recipientCity = $request->input('recipientCity');
        $recipientstateOrProvinceCode = $request->input('recipientstateOrProvinceCode');
        $shipperStreet = $request->input('shipperStreet');
        $shipperCity = $request->input('shipperCity');
        $shipperstateOrProvinceCode = $request->input('shipperstateOrProvinceCode');
        $shipperCountryName = $request->input('shipperCountryName');
        $recipientCountryName = $request->input('recipientCountryName');
        $pickupType = $request->input('pickupType');
        // dump($isResidential);
        // dump($residential, );
        // dump($request->all());

        // Validate form inputs
        $validatedData = $request->validate([
            'fromCountry'                    => 'required|string|max:2',     // Example: US, UK
            'toCountry'                      => 'required|string|max:2',
            'zipcodeFrom'                    => 'required|string|max:10',
            'zipcodeTo'                      => 'required|string|max:10',
            'weight'                         => 'required|numeric|min:1',  // Ensure weight is numeric and > 0
            // 'residential'                    => 'required|boolean',
            'description'                    => 'required|string|max:255',   // Optional field
            'length'                         => 'required|numeric|min:1',
            'width'                          => 'required|numeric|min:1',
            'height'                         => 'required|numeric|min:1',
            'dimension_unit'                 => 'required|string|max:10',
            'packagingType'                  => 'required|string|max:50',    // Example: BOX, ENVELOPE
            'shipDate'                       => 'required|string',             // Ensure it's a valid date
            'recipientStreet'                => 'required|string|max:255',   // Street address
            'recipientCity'                  => 'required|string|max:100',   // City name
            'recipientstateOrProvinceCode'   => 'required|string|max:10',    // State/Province code
            'shipperStreet'                  => 'required|string|max:255',   // Shipper's street address
            'shipperCity'                    => 'required|string|max:100',   // Shipper's city
            'shipperstateOrProvinceCode'     => 'required|string|max:10',    // Shipper's state/province code
            'shipperCountryName'             => 'required|string|max:255',
            'recipientCountryName'           => 'required|string|max:255',
            'pickupType'                     => 'required|string|max:255',
        ]);

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
            'residential' => $residential,
            'description' => $description,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'dimension_unit' => $units,
            'packagingType' => $packagingType,
            'shipDate' => $shipDate,
            'shipperCountryName' => $shipperCountryName,
            'recipientCountryName' => $recipientCountryName,
            'pickupType' => $pickupType,
        ]);





        try {
            // Call FedEx service to get rates
            $rates = $this->fedExService->getQuote(
                $fromCountry,
                $fromZip,
                $toCountry,
                $toZip,
                $weight,
                $residential,
                $description,
                $length,
                $width,
                $height,
                $units,
                $packagingType,
                $shipDate,
                $pickupType
            );
            // dump($residential);
            // dd($rates);

            return view('shipments.reqShipment', compact('rates', 'validatedData'));
        } catch (\Exception $e) {


            $error = json_decode($e->getMessage(),true);

            dd($error['errors'][0]['code']);

            if (isset($error['errors'][0]['code'])) {
                $jsonErr = $error['errors'][0]['code'];

                switch ($jsonErr) {
                    case "DESTINATION.POSTALCODE.MISSING.ORINVALID":
                        session()->flash('error', 'Invalid country code. Please double-check.');
                        break;

                    case "SERVICE.PACKAGECOMBINATION.INVALID":
                        session()->flash('error', 'Invalid service and packaging combination. Please update and try again.');
                        break;

                    case "RECIPIENT.COUNTRY.UNSERVICED":
                        session()->flash('error', 'Invalid recipient country code.');
                        break;
                    case "SYSTEM.UNAVAILABLE.EXCEPTION":
                        session()->flash('error', 'We are unable to process this request. Please try again later or contact FedEx Customer Service.');
                        break;

                    default:
                        session()->flash('error', 'An error occurred while requesting data. Please try again!');
                        break;
                }
            } else {
                session()->flash('error', 'An error occurred while processing your request.');
            }

            return view('sample');
        }

    }



    public function shipping(Request $request){

        // $serviceType = $request->input(key: 'serviceType');
        // $totalNetCharge = $request->input(key: 'totalNetCharge');


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

        //  dd($shipperName, $shipperPhone, $recipientName, $recipientPhone);

        //  dd($totalNetCharge, $weight);
        //  dd($serviceType);

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



}
