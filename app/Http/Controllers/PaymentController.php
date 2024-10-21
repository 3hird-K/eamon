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
                //  'f_serviceType' => $serviceType,
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
                'totalNetCharge' => session('f_totalNetCharge'),
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
                'labelStockType' => session('labelStockType'),

            ];

            // dd($data);

            $labelStockType = 'PAPER_85X11_TOP_HALF_LABEL';

            $shipRequest = $this->fedExService->BookNow(

                $data['shipperName'],
                $data['shipperPhone'],
                $data['shipperStreet'],
                $data['shipperCity'],
                $data['fromCountry'],
                $data['recipientName'],
                $data['recipientPhone'],
                $data['recipientStreet'],
                $data['recipientCity'],
                $data['toCountry'],
                $data['shipDate'],
                $data['packagingType'],
                $data['pickupType'],
                $labelStockType,
                $data['labelResponseOptions'],
                $data['weight'],
                $data['serviceType'],
                $data['postalInputShipper'],
                $data['postalInputRecipient'],
                $data['shipperstateOrProvinceCode'],
                $data['recipientstateOrProvinceCode'],
                $data['totalNetCharge'],
                $data['customAmount'],
                $data['customQty'],
                $data['description'],
                // $data['residential']
                // $data['length'],
                // $data['width'],
                // $data['height'],
                // $data['dimension_unit'],
            );

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
