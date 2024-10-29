<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;
use App\Services\FedExService;

class PaymentController extends Controller
{

    protected $fedExService;
    private $gateway;

    public function __construct(FedExService $fedExService)
    {
        // Initialize FedExService
        $this->fedExService = $fedExService;

        // Initialize the PayPal gateway
        $this->gateway = Omnipay::create("PayPal_Rest");
        $this->gateway->setClientId(env("PAYPAL_CLIENT_ID"));
        $this->gateway->setSecret(env("PAYPAL_CLIENT_SECRET"));
        $this->gateway->setTestMode(true); // Set to false for live mode
    }

    public function processPayment(Request $request)
    {
        // $amount = $request->input("totalNetCharge");

        $amount = number_format($request->input("f_totalNetCharge"), 2, '.', '');


        // $serviceType = $request->input('f_serviceType');
        // dd($request->all());

        session(['f_totalNetCharge' => $amount,
                ]);



        try {
            // Create a payment request
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error'),
            ])->send();

            // dd($response);


        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } elseif ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
        } else {
            // payment failed: display message to customer
            echo $response->getMessage();
        }

        } catch(\Throwable $th){
            return $th->getMessage();
        }

    }

    public function success(Request $request)
{





    if ($request->input('paymentId') && $request->input('PayerID')) {
        $purchaseRequest = $this->gateway->completePurchase(array(
            'payer_id' => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ));

        $response = $purchaseRequest->send();

        if ($response->isSuccessful()) {
            $arr = $response->getData();


            $payment = new Payment();
            $payment->payment_id = $arr['id'];
            $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
            $payment->payer_email = $arr['payer']['payer_info']['email'];
            $payment->amount = $arr['transactions'][0]['amount']['total'];
            $payment->currency = env('PAYPAL_CURRENCY');
            $payment->status = $arr['state'];

            $payment->save();

            // dd($payment);

            session([
                'payment_id' => $payment->payment_id,
                'payer_id' => $payment->payer_id,
                'payer_email' => $payment->payer_email,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,
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
                'f_totalNetCharge' => session('f_totalNetCharge'),
                'f_residential' => session('f_residential'),
                'f_description' => session('f_description'),
                'f_length' => session('f_length'),
                'f_width' => session('f_width'),
                'f_height' => session('f_height'),
                'f_dimension_unit' => session('f_dimension_unit'),
                'f_packagingType' => session('f_packagingType'),
                'f_shipDate' => session('f_shipDate'),
                'f_pickupType' => session('f_pickupType'),
                'f_labelStockType' => session('f_labelStockType'),
                'f_imageType' => session('f_imageType'),

                // Postal Information
                'f_postalInputShipper' => session('postalInputShipper'),
                'f_postalInputRecipient' => session('postalInputRecipient'),
            ];

            // dd($data);


            $shipRequest = $this->fedExService->BookNow(

                $data['f_shipperName'],
                $data['f_shipperPhone'],
                $data['f_shipperStreet'],
                $data['f_shipperCity'],
                $data['f_fromCountry'],
                $data['f_recipientName'],
                $data['f_recipientPhone'],
                $data['f_recipientStreet'],
                $data['f_recipientCity'],
                $data['f_toCountry'],
                $data['f_shipDate'],
                $data['f_packagingType'],
                $data['f_pickupType'],
                $data['f_labelStockType'],
                $data['f_labelResponseOptions'],
                $data['f_weight'],
                $data['f_serviceType'],
                $data['f_postalInputShipper'],
                $data['f_postalInputRecipient'],
                $data['f_shipperstateOrProvinceCode'],
                $data['f_recipientstateOrProvinceCode'],
                $data['f_totalNetCharge'],
                $data['f_customAmount'],
                $data['f_customQty'],
                $data['f_description'],
                $data['f_shipperCompany'],
                $data['f_recipientCompany'],
                $data['f_recipientStreet1'],
                $data['f_recipientStreet2'],
                $data['f_shipperEmail'],
                $data['f_recipientEmail'],
                $data['f_residential'],
                $data['f_length'],
                $data['f_width'],
                $data['f_height'],
                $data['f_dimension_unit'],
                $data['f_imageType'],

            );

            // dd($shipRequest);

            $value = session('reqErrorResponse');

            if($value =="test"){
            return redirect()->back()->withInput()->withErrors("Error");

            }else{
                session([

                    // if(){}else{}
                    'trackingId' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['trackingNumber'],
                    // 'trackingId' => $shipRequest['output']['transactionShipments'][0]['pieceResponses'][0]['masterTrackingNumber'],
                    'trackingUrl' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][1]['url'],
                    // 'trackingUrl' => $shipRequest['output']['transactionShipments'][0]['pieceResponses'][0]['packageDocuments'][0]["url"],
                    'serviceTyped' => $shipRequest['output']['transactionShipments'][0]['serviceType'],
                ]);

            }


            return view('paypal.success', $data);

        } else {
            return redirect()->route('error')->with('error', $response->getMessage());
        }
    } else {
        return redirect()->route('error')->with('error', 'Payment is declined!');
    }
}





    public function error(){
        return view('paypal.error');
    }

}
