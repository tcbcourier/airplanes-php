<?php
/*
Copyright (c) 2013, TCB Courier
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. All advertising materials mentioning features or use of this software
   must display the following acknowledgement:
   This product includes software developed by the TCB Courier
4. Neither the name of the TCB Courier nor the
   names of its contributors may be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY TCB COURIER ''AS IS'' AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

define('TCB_CREDIT_CARD', 'credit card');
define('TCB_CASH', 'cash');
define('TCB_PREPAID', 'prepaid');
define('API_ENDPOINT', 'https://airplanesinthesky.appspot.com/api/ecom/v2/jobs/');

class Airplanes {
    
    //Constants
    
    private         $clientId;
    private         $sharedSecret;
    private         $externalId;
    private         $airplanesURI;
    private         $deliverToName;
    private         $deliverToCompany;
    private         $deliverToAddress;
    private         $deliverToSuite;
    private         $deliverToCity;
    private         $deliverToState;
    private         $deliverToZip;
    private         $readyTime;
    private         $deliverToDuetime;
    private         $instructions;
    private         $paymentMethod;
    private         $total;
    private         $tip;
    private         $items = array();
    
    public function setClientId($inClient) {
        $this->clientId = $inClient;
    }
    
    public function setSharedSecret($inKey) {
        $this->sharedSecret = $inKey;
    }
    
    public function setExternalId($inId) {
        $this->externalId = $inId;
    }
    
    public function setAirplanesURI($inURI) {
        $this->airplanesURI = $inURI;
    }
    
    public function setDeliverToName($inName) {
        $this->deliverToName = $inName;
    }
    
    public function setDeliverToCompany($inCompany) {
        $this->deliverToCompany = $inCompany;
    }
    
    public function setDeliverToAddress($inAddress) {
        $this->deliverToAddress = $inAddress;
    }
    
    public function setDeliverToSuite($inSuite) {
        $this->deliverToSuite = $inSuite;
    }
    
    public function setDeliverToCity($inCity) {
        $this->deliverToCity = $inCity;
    }
    
    public function setDeliverToState($inState) {
        $this->deliverToState = $inState;
    }
    
    public function setDeliverToZip($inZip) {
        $this->deliverToZip = $inZip;
    }
    
    public function setReadyTime($inDate) {
        date_default_timezone_set('America/Los_Angeles');
        $newDate = date('Y-m-d h:i', strtotime($inDate));
        $this->readyTime;
    }
    
    public function setDeliverToDueTime($inDate) {
        date_default_timezone_set('America/Los_Angeles');
        $newDate = date('Y-m-d h:i', strtotime($inDate));
        $this->deliverToDuetime = $newDate;
    }
    
    public function setInstructions($inInstructions) {
        $this->instructions = $inInstructions;
    }
    
    public function setPaymentMethod($inPayment) {
        $this->paymentMethod = $inPayment;
    }
    
    public function setTotal($inTotal) {
        $this->total = $inTotal;
    }
    
    public function setTip($inTip) {
        $this->tip = $inTip;
    }
    
    public function addItemToItems($quantity, $name, $sku = false) {
        if ($sku) {
            array_push($this->items, array('name' => $name, 'quantity' => intval($quantity), 'sku' => $sku));
        }
        else {
            array_push($this->items, array('name' => $name, 'quantity' => intval($quantity)));
        }
    }
    
    public function uploadOrder() {
        if (!isset($this->clientId)) {
            throw new AirplanesException("You must set a client id.");
            return false;
        }
        
        if (!isset($this->deliverToName)) {
            throw new AirplanesException("You must set name of the person we are delivering to.");
            return false;
        }
        
        if (!isset($this->deliverToAddress)) {
            throw new AirplanesException("You must set the address of where we are delivering to.");
            return false;
        }
        
        if (!isset($this->deliverToCity)) {
            throw new AirplanesException("You must set the city we are delivering to.");
            return false;
        }
        
        if (!isset($this->deliverToState)) {
            throw new AirplanesException("You must set the state we are delivering to.");
            return false;
        }
        
        if (!isset($this->deliverToZip)) {
            throw new AirplanesException("You must set the zip code we are delivering to.");
            return false;
        }
        
        if (!isset($this->readyTime)) {
            date_default_timezone_set('America/Los_Angeles');
            $date = date('Y-m-d h:i', time());
            $this->readyTime = $date;
        }
        
        if (!isset($this->deliverToDueTime)) {
            date_default_timezone_set('America/Los_Angeles');
            $date = date('Y-m-d h:i', time() + 2700);
            $this->deliverToDuetime = $date;
            
        }
        
        if (!isset($this->paymentMethod)) {
            throw new AirplanesException("You must set the payment method for this order.");
            return false;
        }
        
        if (!isset($this->total)) {
            throw new AirplanesException("You must set the order total.");
            return false;
        }
        
        date_default_timezone_set('America/Los_Angeles');
        $currentDatetime = date('Y-m-d h:i', time());
        
        //Set the required fields in the JSON request
        $anOrder = array(
            'client_id'             => $this->clientId,
            'order_date'            => $currentDatetime,
            'deliver_to_name'       => $this->deliverToName,
            'deliver_to_address'    => $this->deliverToAddress,
            'deliver_to_city'       => $this->deliverToCity,
            'deliver_to_state'      => $this->deliverToState,
            'deliver_to_zip'        => $this->deliverToZip,
            'ready_time'            => $this->readyTime,
            'deliver_to_duetime'    => $this->deliverToDuetime,
            'payment_method'        => $this->paymentMethod,
            'total'                 => $this->total
        );
        
        //Now add the optional fields (if applicable)
        
        if (isset($this->externalId)) {
            $anOrder['external_id'] = $this->externalId;
        }
        
        if (isset($this->deliverToCompany)) {
            $anOrder['deliver_to_company'] = $this->deliverToCompany;
        }
        
        if (isset($this->deliverToSuite)) {
            $anOrder['deliver_to_suite'] = $this->deliverToSuite;
        }
        
        if (isset($this->instructions)) {
            $anOrder['instructions'] = $this->instructions;
        }
        
        if (isset($this->tip)) {
            $anOrder['tip'] = $this->tip;
        }
        
        if (sizeof($this->items) > 0) {
            $anOrder['items'] = $this->items;
        }
        
        //Now put the above order into a wrapper array and encode it as JSON.
        $orders = array();

        array_push($orders, $anOrder);

        $json = json_encode($orders);
        
        $datetime           = new DateTime('America/Los_Angeles');
        $requestDate        = $datetime->format(DateTime::ISO8601);
 
        //Prepare the headers.
        $requestBodyMD5     = md5($json); //Take MD5 hash of JSON
        $utf8encoded        = utf8_encode($requestDate . $requestBodyMD5); //Utf8 encode with the date
        $stringToSign       = base64_encode($utf8encoded); // base 64 encode it
        $calculatedSig      = hash_hmac('sha1', $stringToSign, $this->sharedSecret); //HMAC it with shared key
 
        //cURL request
        $curl = curl_init(API_ENDPOINT);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); //Set the JSON
        //Set your header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                  
            'Content-Type: application/json',                                                                  
            'Content-Length: ' . strlen($json),
            'Date: ' . $requestDate,
            'Authorization :' . $this->clientId . ':' . $calculatedSig)                                                      
        ); 
        $result = curl_exec($curl);
        return $result;
    }
}

class AirplanesException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
            return $this->message;
        }
}
    
?>