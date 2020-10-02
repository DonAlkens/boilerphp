<?php

namespace App;

use App\Core\Database\Model;


class Transaction extends Model {

    /**
    * defining all required fields 
    **/
    protected $required = [];


    public function init() {

        return $this->reference = ("WSLT".rand(121561, 918237)."tYrt2y".rand(6574, 98456));
    }

    public function flutterwave($order) {

        $order = (new Order)->where("id", $order)->get();

        if($order) {

            $amount = ($order->amount + $order->shipping_fee);
            $email = $order->customer()->email;
            
            
            $reference = $this->init();
            $transaction = $this->insert([
                "reference" => $reference, 
                "customer" => $order->customer,
                "order" => $order->id,
                "amount" => $amount,
                "payment_method" => "Flutterwave",
                "status" => 316
            ]);


            $curl = curl_init();
            $callback = "http://mendiz.now/order/payment/f/verify";
            $public_key = "FLWPUBK_TEST-bfeefb17327cae17e4502f60a6d8627e-X";

    
            curl_setopt_array($curl, array(

                CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'amount'=>$amount,
                    'customer_email'=>$email,
                    'currency' => "NGN",
                    'txref'=> $transaction,
                    'PBFPubKey'=> $public_key,
                    'redirect_url'=> $callback,
                    'payment_plan'=> ''
                ]),
                CURLOPT_HTTPHEADER => [
                    "content-type: application/json",
                    "cache-control: no-cache"
                ]
            ));
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            if($err){
                // there was an error contacting the rave API
                return false;
            }
    
            $transaction = json_decode($response);
    
            if(!$transaction->data && !$transaction->data->link){
                // there was an error from the API
                return false;
            }
    
            // redirect to page so User can pay
            // uncomment this line to allow the user redirect to the payment page
            $this->location = $transaction->data->link;
            return true;
        }

        return false;

    }

    public function flutterwave_verification($transaction) {

        $query = array(
            "SECKEY" => "FLWSECK_TEST-977a494764481f3d925073dc56663052-X",
            "txref" => $transaction
        );

        $transaction = $this->where("reference", $transaction)->get();
        $amount = $transaction->amount;
        $currency = "NGN";

        $data_string = json_encode($query);
                
        $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        $resp = json_decode($response, true);

        if($resp != "") {
            $paymentStatus = isset($resp['data']['status']) ? $resp['data']['status'] : null;
            $chargeResponsecode = isset($resp['data']['chargecode']) ? $resp['data']['chargecode'] : null;
            $chargeAmount = isset($resp['data']['amount']) ? $resp['data']['amount'] : null;
            $chargeCurrency = isset($resp['data']['currency']) ? $resp['data']['currency'] : null;
    
            if (($chargeResponsecode == "00" || $chargeResponsecode == "0") 
                && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
                return $this->verified = true;
            }
        }

        return $this->verified = false;
    }

    public function paystack($order) {

        $order = (new Order)->where("id", $order)->get();

        if($order) {

            $amount = ($order->amount + $order->shipping_fee);
            $email = $order->customer()->email;
            
            
            $reference = $this->init();
            $transaction = $this->insert([
                "reference" => $reference, 
                "customer" => $order->customer,
                "order" => $order->id,
                "amount" => $amount,
                "payment_method" => "Paystack",
                "status" => 316
            ]);

            $amount = ($amount * 100);

            $callback = "http://mendiz.now/order/payment/p/verify";
            $secret_key = "sk_test_0c1f5695a9d22452c6a569bd3df39fa9bd5b5362";
        
        
            $url = "https://api.paystack.co/transaction/initialize";
            $fields = [
                'email' => $email,
                'amount' => $amount,
                "reference" => $reference,
                "callback_url" => $callback,
            ];
            $fields_string = http_build_query($fields);
            //open connection
            $ch = curl_init();
            
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ".$secret_key,
                "Cache-Control: no-cache"
            ));
            
            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
            
            //execute post
            $result = curl_exec($ch);
            $transaction = json_decode($result);

            if(!$transaction->data && !$transaction->data->link){
                // there was an error from the API
                return false;
            }
    
            $this->location = $transaction->data->authorization_url;
            return true;
        }

        return false;

    }

    public function paystack_verification($transaction) {

        $curl = curl_init();
        $secret_key = "sk_test_0c1f5695a9d22452c6a569bd3df39fa9bd5b5362";
  
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$transaction,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$secret_key,
                "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return false;
        } 
        else 
        {

            $response = json_decode($response);
            if($response->data->status == "success") { 
                return true;
            } 
            else {
                return false;
            }
        }
    }
}

?>