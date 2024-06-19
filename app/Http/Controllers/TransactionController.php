<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function initiateCheckout()
    {
        $client = new Client(['verify' => false]);
        $merchantId = 'TEST222201363002';
        $uniqueOrderId = uniqid('test-001');

        $payload = [
            "apiOperation" => "INITIATE_CHECKOUT",
            "interaction" => [
                "operation" => "PURCHASE",
                "merchant" => [
                    "name" => "Your merchant name",
                    "address" => [
                        "line1" => "200 Sample St",
                        "line2" => "1234 Example Town"
                    ]
                ]
            ],
            "order" => [
                "currency" => "USD",
                "id" => $uniqueOrderId,
                "amount" => 5,
                "description" => "ordered goods"
            ]
        ];

        $username = "merchant.$merchantId";
        $password = "75a406e173782600f3b303d94e0f5fa8";
        $authHeader = base64_encode("$username:$password");

        try {
            $response = $client->post("https://epayment.areeba.com/api/rest/version/78/merchant/$merchantId/session", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false, 
            ]);
    
            $body = json_decode($response->getBody()->getContents());

            return view('checkout', [
                'sessionId' => $body->session->id,
                'merchantId' => $merchantId,
                'orderId' => $uniqueOrderId
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in initiateCheckout: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function retrieveOrder(Request $request)
    {
        $orderId = $request->query('orderId');
        $merchantId = $request->query('merchantId');

        $client = new Client(['verify' => false]);

        $username = "merchant.$merchantId";
        $password = "75a406e173782600f3b303d94e0f5fa8";
        $authHeader = base64_encode("$username:$password");

        try {
            $response = $client->get("https://epayment.areeba.com/api/rest/version/81/merchant/$merchantId/order/$orderId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'verify' => false,
            ]);
    
            $orderDetails = json_decode($response->getBody()->getContents(), true);

            return view('thankYou', [
                'orderDetails' => $orderDetails
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in retrieveOrder: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function initiateRefund(Request $request)
    {
        $client = new Client(['verify' => false]);
        $merchantId = 'TEST222201363002';
        $orderId = 'test-001666ae4499a39c'; 
        $transactionId = '2'; 
    
        $payload = [
            "apiOperation" => "REFUND",
            "transaction" => [
                "amount" => 5,
                "currency" => "USD"
            ]
        ];
    
        $username = "merchant.$merchantId";
        $password = "75a406e173782600f3b303d94e0f5fa8";
        $authHeader = base64_encode("$username:$password");
    
        try {
            $response = $client->put("https://ap-gateway.mastercard.com/api/rest/version/81/merchant/$merchantId/order/$orderId/transaction/$transactionId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);
    
            $responseBody = json_decode($response->getBody()->getContents(), true);
    
            return response()->json($responseBody);
    
        } catch (RequestException $e) {
            Log::error('RequestException in initiateRefund: ' . $e->getMessage());
            return response()->json(['error' => 'RequestException: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Exception in initiateRefund: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function initiateVoid(Request $request)
    {
        try {
            $client = new Client(['verify' => false]);

            $merchantId = 'TEST222201363002';
            $orderId = 'test-001666ae4499a39c'; 
            $transactionId = '2'; 

            $payload = [
                "apiOperation" => "VOID",
                "transaction" => [
                    "targetTransactionId" => $transactionId
                ]
            ];

            $username = "merchant.$merchantId";
            $password = "75a406e173782600f3b303d94e0f5fa8";
            $authHeader = base64_encode("$username:$password");

            $response = $client->put("https://ap-gateway.mastercard.com/api/rest/version/81/merchant/$merchantId/order/$orderId/transaction/$transactionId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseBody);
        } catch (RequestException $e) {
            Log::error('RequestException in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => 'RequestException: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Exception in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function initiateVerify(Request $request)
    {
        $client = new Client(['verify' => false]);
        $merchantId = 'TEST222201363002';
        $password = "75a406e173782600f3b303d94e0f5fa8";
        $orderId = 'test-001666ae4499a39c'; 
        $transactionId = '2'; 
    
        $payload = [
            "apiOperation" => "VERIFY",
            "order" => [
                "amount" => "5.00",                           
                "currency" => "USD"                             
            ],
            "sourceOfFunds" => [
                "provided" => [
                    "card" => [
                        "number" => "5123450000000008",          
                        "expiry" => [
                            "month" => "01",                    
                            "year" => "2039"                    
                        ],
                        "securityCode" => "123"                 
                    ]
                ]
            ],
            "merchant" => [
                "merchantId" => $merchantId,             
                "password" => $password                    
            ]
        ];
        
        $username = "merchant.$merchantId";
        $authHeader = base64_encode("$username:$password");
    
        try {
            $response = $client->put("https://ap-gateway.mastercard.com/api/rest/version/81/merchant/$merchantId/order/$orderId/transaction/$transactionId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);
    
            $responseBody = json_decode($response->getBody()->getContents(), true);
    
            return response()->json($responseBody);
    
        } catch (RequestException $e) {
            Log::error('RequestException in initiateRefund: ' . $e->getMessage());
            return response()->json(['error' => 'RequestException: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Exception in initiateRefund: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function initiateAuthorize(Request $request)
    {
        try {
            $client = new Client(['verify' => false]);
            $merchantId = 'TEST222201363002';
            $orderId = 'test-001666ae4499a39c';
            $password = "75a406e173782600f3b303d94e0f5fa8"; 
            $transactionId = '2'; 

            $payload = [
                "apiOperation" => "AUTHORIZE",
                "order" => [
                    "amount" => "5.00",                           
                    "currency" => "USD"                             
                ],
                "sourceOfFunds" => [
                    "provided" => [
                        "card" => [
                            "number" => "5123450000000008",          
                            "expiry" => [
                                "month" => "01",                    
                                "year" => "2039"                    
                            ],
                            "securityCode" => "123"                 
                        ]
                    ]
                ],
                "merchant" => [
                    "merchantId" => $merchantId,             
                    "password" => $password                    
                ]
            ];

            $username = "merchant.$merchantId";
            $authHeader = base64_encode("$username:$password");

            $response = $client->put("https://ap-gateway.mastercard.com/api/rest/version/81/merchant/$merchantId/order/$orderId/transaction/$transactionId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseBody);
        } catch (RequestException $e) {
            Log::error('RequestException in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => 'RequestException: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Exception in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function initiateCapture(Request $request)
    {
        try {
            $client = new Client(['verify' => false]);

            $merchantId = 'TEST222201363002';
            $orderId = 'test-001666ae4499a39c'; 
            $transactionId = '2'; 

            $payload = [
                "apiOperation" => "CAPTURE",
                "transaction" => [
                    "targetTransactionId" => $transactionId,
                    "amount" => "5.00",
                    "currency" => "USD"
                ],
                "order" => [
                    "amount" => "5.00",                              
                    "currency" => "USD"                              
                ],
            ];
            

            $username = "merchant.$merchantId";
            $password = "75a406e173782600f3b303d94e0f5fa8";
            $authHeader = base64_encode("$username:$password");

            $response = $client->put("https://ap-gateway.mastercard.com/api/rest/version/81/merchant/$merchantId/order/$orderId/transaction/$transactionId", [
                'headers' => [
                    'Authorization' => "Basic $authHeader",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseBody);
        } catch (RequestException $e) {
            Log::error('RequestException in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => 'RequestException: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Exception in initiateVoid: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
