<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FedExService
{
    protected $key;
    protected $password;
    protected $accountNumber;
    protected $meterNumber;
    protected $isSandbox;

    public function __construct()
    {
        $this->key = env('FEDEX_KEY');
        $this->password = env('FEDEX_PASSWORD');
        $this->accountNumber = env('FEDEX_ACCOUNT_NUMBER');
        $this->meterNumber = env('FEDEX_METER_NUMBER');
        $this->isSandbox = env('FEDEX_SANDBOX');
    }

    /**
     * Retrieves the OAuth token from FedEx and caches it for one hour.
     *
     * @return string
     * @throws Exception
     */
    public function getAuthToken()
    {
        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/oauth/token'
            : 'https://apis.fedex.com/oauth/token';

        $response = Http::asForm()->post($endpoint, [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->key,
            'client_secret' => $this->password,
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            // Store the token in the cache (expires in 1 hour)
            Cache::put('fedex_access_token', $accessToken, now()->addHour());

            return $accessToken;
        } else {
            throw new Exception('Failed to retrieve FedEx OAuth token: ' . $response->body());
        }
    }

    /**
     * Retrieves rate and transit information for a shipment.
     *
     * @param string $fromCountry
     * @param string $fromZip
     * @param string $toCountry
     * @param string $toZip
     * @param float $weight
     * @return mixed
     * @throws Exception
     */
    public function getQuote(
        $fromCountry,
        $toCountry,
        $weight,
        $postalInputRecipient,
        $postalInputShipper,
        $weight_unit
    )
    {
        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes'
            : 'https://apis.fedex.com/rate/v1/rates/quotes';

        $authToken = $this->getAuthToken();

        if (!$authToken) {
            throw new Exception("Failed to retrieve access token.");
        }

        $shipmentData = [
            "accountNumber"=> [
                "value"=> $this->accountNumber
            ],
            "rateRequestControlParameters"=> [
                "returnTransitTimes"=> false,
                "servicesNeededOnRateFailure"=> true,
                "variableOptions"=> "FREIGHT_GUARANTEE",
                "rateSortOrder"=> "SERVICENAMETRADITIONAL"
            ],
            "requestedShipment"=> [
                "shipper"=> [
                    "address"=> [
                        "postalCode"=> $postalInputShipper,
                        "countryCode"=> $fromCountry
                    ]
                ],
                "recipient"=> [
                    "address"=> [
                        "postalCode"=> $postalInputRecipient,
                        "countryCode"=> $toCountry
                    ]
                ],
                "shipDateStamp"=> now()->toDateString(),
                "pickupType"=> "USE_SCHEDULED_PICKUP",
                "serviceType"=> "",
                "packagingType" => "YOUR_PACKAGING",
                "rateRequestType"=> ["LIST", "ACCOUNT"],
                "customsClearanceDetail"=> [
                    "dutiesPayment"=> [
                        "paymentType"=> "SENDER",
                        "payor"=> [
                            "responsibleParty"=> null
                        ]
                    ],
                    "commodities"=> [
                        [
                            "description"=> "Package Description",
                            "quantity"=> 1,
                            "quantityUnits"=> "PCS",
                            "weight"=> [
                                "units"=> $weight_unit,
                                "value"=> $weight
                            ],
                            "customsValue"=> [
                                "amount"=> 100,
                                "currency"=> "USD"
                            ]
                        ]
                    ]
                ],
                "requestedPackageLineItems"=> [
                    [
                        "weight"=> [
                            "units"=> $weight_unit,
                            "value"=> $weight
                        ]
                    ]
                ]
            ]
                        ];



        // Make the API request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $authToken,
        ])->post($endpoint, $shipmentData);

        if ($response->successful()) {
            return $response->json();
        } else {
            // throw new Exception('Failed to retrieve FedEx rate quote: ' . $response->body());
            throw new Exception($response->body());
        }
    }



    // public function BookNow($personName, $phoneNo,$streetLines,$city,$stateOrProvinceCode,$postalCode,$countryCode,$shipDatestamp,$serviceType,$packagingType,$pickupType,$labelStockType,$imageType,$weight){
    public function BookNow(
        $f_shipperName,
        $f_shipperPhone,
        $f_shipperStreet,
        $f_shipperCity,
        $f_shipperCountryCode,
        $f_recipientName,
        $f_recipientPhone,
        $f_recipientStreet,
        $f_recipientCity,
        $f_recipientCountryCode,
        $f_shipDate,
        $f_packagingType,
        $f_pickupType,
        $f_labelStockType,
        $f_labelResponseOptions,
        $f_weight,
        $f_serviceType,
        $f_fromZip,
        $f_toZip,
        $f_shipperstateOrProvinceCode,
        $f_recipientstateOrProvinceCode,
        $f_totalNetCharge,
        $f_customAmount,
        $f_customQty,
        $f_description,
        $f_shipperCompany,
        $f_recipientCompany,
        $f_recipientStreet1,
        $f_recipientStreet2,
        $f_shipperEmail,
        $f_recipientEmail,
        $f_residential,
        $f_length,
        $f_width,
        $f_height,
        $f_units,
        $f_imageType,
    ){

        // dd($customAmount, $customQty);

        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/ship/v1/shipments'
            : 'https://apis.fedex.com/ship/v1/shipments';

        $authToken = $this->getAuthToken();

        if (!$authToken) {
            throw new Exception("Failed to retrieve access token.");
        }



        $bookingData = [
            "labelResponseOptions"=> $f_labelResponseOptions,
            "requestedShipment"=> [
                "shipper"=> [
                    "contact"=> [
                        "personName"=> $f_shipperName,
                        "phoneNumber"=> $f_shipperPhone,
                        "companyName"=> $f_shipperCompany,
                        "emailAddress" => $f_shipperEmail
                    ],
                    "address"=> [
                        "streetLines"=> [$f_shipperStreet],
                        "city"=> $f_shipperCity,
                        "stateOrProvinceCode"=> $f_shipperstateOrProvinceCode,
                        "postalCode"=> $f_fromZip,
                        "countryCode"=> $f_shipperCountryCode,
                        "residential"=> $f_residential
                    ]
                ],
                "recipients"=> [
                    [
                        "contact"=> [
                            "personName"=> $f_recipientName,
                            "phoneNumber"=> $f_recipientPhone,
                            "companyName"=> $f_recipientCompany,
                            "emailAddress" => $f_recipientEmail
                        ],
                        "address"=> [
                            "streetLines"=> [
                                $f_recipientStreet,
                                $f_recipientStreet1,
                                $f_recipientStreet2,
                            ],
                            "city"=> $f_recipientCity,
                            "stateOrProvinceCode"=> $f_recipientstateOrProvinceCode,
                            "postalCode"=> $f_toZip,
                            "countryCode"=> $f_recipientCountryCode,
                            "residential"=> $f_residential
                        ]
                    ]
                ],
                "shipDatestamp"=> $f_shipDate,
              "serviceType"=> $f_serviceType,
              "packagingType"=> $f_packagingType, // tobemodified
              "pickupType"=> $f_pickupType,
              "blockInsightVisibility"=> false,
              "shippingChargesPayment"=> [
                "paymentType"=> "SENDER"
              ],
              "labelSpecification"=> [
                "imageType"=> "PDF",
                // "imageType"=> $f_imageType,
                "labelStockType"=> $f_labelStockType
              ],
              "customsClearanceDetail"=> [
                "dutiesPayment"=> [
                  "paymentType"=> "SENDER"
                ],
                "isDocumentOnly"=> true,
                "commodities"=> [
                  [
                    "description"=> $f_description,
                    "countryOfManufacture"=> "US",
                    "quantity"=> $f_customQty,
                    "quantityUnits"=> "PCS",
                    "unitPrice"=> [
                      "amount"=> $f_totalNetCharge,
                      "currency"=> "USD"
                    ],
                    "customsValue"=> [
                      "amount"=> $f_customAmount,
                      "currency"=> "USD"
                    ],
                    "weight"=> [
                      "units"=> session('weight_unit'),
                      "value"=> $f_weight
                    ]
                  ]
                ]
              ],
              "shippingDocumentSpecification"=> [
                "shippingDocumentTypes"=> [
                  "COMMERCIAL_INVOICE"
                ],
                "commercialInvoiceDetail"=> [
                  "documentFormat"=> [
                    "stockType"=> "PAPER_LETTER",
                    "docType"=> "PDF" // fixed to PDF
                    // "docType"=> "PNG"
                  ]
                ]
              ],
              "requestedPackageLineItems"=> [
                [
                  "weight"=> [
                    "units"=> session('weight_unit'),
                    "value"=> $f_weight
                  ],
                //   "dimensions" => [
                //     "length" => "10",
                //     "width" => "10",
                //     "height" =>"10",
                //     "units" => "IN"
                //     // "length" => $f_length,
                //     // "width" => $f_width,
                //     // "height" => $f_height,
                //     // "units" => $f_units
                //   ]

                ]
              ]
            ],
            "accountNumber"=> [
              "value"=> $this->accountNumber,
            ]
            ];

            // dump($bookingData);



            session()->forget('reqErrorResponse');
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authToken,
                ])->post($endpoint, $bookingData);

                if ($response->successful()) {

                    // dd($response->json());
                    return $response->json();
                } else {
                    $reqResponse = $response->body();
                    session(['reqErrorResponse' => "test"]);
                    // $errorHand = $shipRequest['data']['reqResponse']['errors']['code'];


                    $data = [
                        'name' => 'errors',
                        'email' => compact('reqResponse'),
                    ];
                    return  $reqResponse;

                }
            } catch (Exception $e) {
                // Handle any exceptions thrown here
                return view('sample', ['errorMessage' => $e->getMessage()]);
            }

    }


public function getFullQuote(
    $f_fromCountry,
    $f_toCountry,
    $f_weight,
    $f_postalInputRecipient,
    $f_postalInputShipper,
    $f_shipDate,
    $f_residential,
    $f_sresidential,
    $f_serviceType,
    $f_description,
    $f_length,
    $f_width,
    $f_height,
    $f_units,
    $f_packagingType,
    $f_customsValueAmount,
    $f_customQty
)
{
    $endpoint = $this->isSandbox
        ? 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes'
        : 'https://apis.fedex.com/rate/v1/rates/quotes';

    $authToken = $this->getAuthToken();

    if (!$authToken) {
        throw new Exception("Failed to retrieve access token.");
    }

    $shipmentData = [
        "accountNumber"=> [
            "value"=> $this->accountNumber
        ],
        "rateRequestControlParameters"=> [
            "returnTransitTimes"=> false,
            "servicesNeededOnRateFailure"=> true,
            "variableOptions"=> "FREIGHT_GUARANTEE",
            "rateSortOrder"=> "SERVICENAMETRADITIONAL"
        ],
        "requestedShipment"=> [
            "shipper"=> [
                "address"=> [
                    "postalCode"=> $f_postalInputShipper,
                    "countryCode"=> $f_fromCountry,
                    "residential" => $f_sresidential,
                ]
            ],
            "recipient"=> [
                "address"=> [
                    "postalCode"=> $f_postalInputRecipient,
                    "countryCode"=> $f_toCountry,
                    "residential" => $f_residential,
                ]
            ],
            "shipDateStamp"=> $f_shipDate,
            "pickupType"=> "DROPOFF_AT_FEDEX_LOCATION",
            // "pickupType"=> "CONTACT_FEDEX_TO_SCHEDULE",
            "serviceType"=> $f_serviceType,
            "rateRequestType"=> ["LIST", "ACCOUNT"],
            "customsClearanceDetail"=> [
                "dutiesPayment"=> [
                    "paymentType"=> "SENDER",
                    "payor"=> [
                        "responsibleParty"=> null
                    ]
                ],
                "commodities"=> [
                    [
                        "description"=> $f_description,
                        "quantity"=> $f_customQty,
                        "quantityUnits"=> "PCS",
                        "weight"=> [
                            "units"=> session('weight_unit'),
                            "value"=> $f_weight
                        ],
                        "customsValue"=> [
                            "amount"=> $f_customsValueAmount,
                            "currency"=> "USD"
                        ]
                        ],
                    [
                        "description"=> $f_description,
                        "quantity"=> $f_customQty,
                        "quantityUnits"=> "PCS",
                        "weight"=> [
                            "units"=> session('weight_unit'),
                            "value"=> $f_weight
                        ],
                        "customsValue"=> [
                            "amount"=> $f_customsValueAmount,
                            "currency"=> "USD"
                        ]
                    ]
                ]
            ],
            "requestedPackageLineItems"=> [

                [
                    "weight"=> [
                        "units"=> session('weight_unit'),
                        "value"=> $f_weight
                    ],
                  "dimensions" => [
                        "length" => $f_length,
                        "width" => $f_width,
                        "height" => $f_height,
                        "units" => $f_units
                  ],

                ],

            ],
            "packagingType" => $f_packagingType,
        ]
                    ];

                // dd($shipmentData);



    // Make the API request
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $authToken,
    ])->post($endpoint, $shipmentData);

    if ($response->successful()) {
        return $response->json();
    } else {
        // throw new Exception('Failed to retrieve FedEx rate quote: ' . $response->body());
        throw new Exception($response->body());
    }
}



};
