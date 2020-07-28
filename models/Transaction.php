<?php

/** 
 * creating a model class
 * it must extends the schema class
 * in order to use the dboperation methods in 
 * the model class
 * @example creating a model
 * class User extends Schema{
 *  #state the table name in the public $table variable
 *  #the model structure should be design 
 *  #and assign to the public $model variable
 * }
 * */

use App\Core\Database\Model;

class Transaction extends Model {

    private $public_key = "FLWPUBK_TEST-ab417c700f9683a9524e4f190a21aafb-X";
    private $secret_key = "FLWSECK_TEST-a8b02df71e892b059a85a3f6ad0cf811-X";
    
    public $redirect_url = "http://april.com/payment-response";

    public $table = 'transactions';

    public $model = array(
        'sn' => 'bigIncrements',
        'transaction_id' => 'string',
        'order_id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'floatL2',
        'status' => 'integer'
    );

    public function __construct($transaction_id = null)
    {
        parent::__construct();
        if($transaction_id != null) {
            $this->transaction = $transaction_id;
            return $this->select(["transaction_id" => $this->transaction]);
        } else {
            $this->transaction = "CRT-". rand(1234567890,9986756757) . date("ymd");
        }
    }

    public function order() {
        return $this->hasOne(Order::class, "order_id");
    }

    public function close($status) {
        return $this->update([
            "status" => $status
        ])->where(["transaction_id" => $this->transaction]);
    }

    public function open($user, $order, $amount) {
        return $this->insert([
            "transaction_id" => $this->transaction,
            "order_id" => $order,
            "user_id" => $user,
            "amount" => $amount,
            "status" => "pending"
        ]);
    }


    public function init($email, $amount, $currency = "NGN") {
        $curl = curl_init();

		curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount'=> $amount,
                'customer_email'=> $email,
                'currency'=> $currency,
                'txref'=> $this->transaction,
                'PBFPubKey'=> $this->public_key,
                'redirect_url'=> $this->redirect_url,
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if($err){
			// there was an error contacting the rave API
			$this->error = true;
            return false;
		}

		$transaction = json_decode($response);

		if(!$transaction->data && !$transaction->data->link){
		    // there was an error from the API
            print_r('API returned error: ' . $transaction->message);
            $this->error = true;
            return false;
		}

		// redirect to page so User can pay
		// uncomment this line to allow the user redirect to the payment page
		$this->pay_link = $transaction->data->link;
		$this->error = false;
		return true;
    }

    
    public function verify($amount, $currency = "NGN") {
        
        $query = array(
            "SECKEY" => $this->secret_key,
            "txref" => $this->transaction
        );

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
                
                $this->update(["status" => true])->where(["transaction_id" => $this->transaction]);
                return $this->verified = true;
            }
        }

        return $this->verified = false;
    }
}