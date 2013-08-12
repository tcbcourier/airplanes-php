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


class Airplanes {
    //Constants
    private         $airplaneOrder;
    
    private         $API_CREATE_ENDPOINT;
    private         $API_STATUS_ENDPOINT;
    private         $clientId;
    private         $sharedSecret;
    
    public function __construct($testing = false) {
        $this->airplaneOrder = new AirplaneOrder();
        if (!$testing) {
            $this->API_CREATE_ENDPOINT = 'https://airplaneinthesky.appspot.com/api/ecom/v2/jobs/';
            $this->API_STATUS_ENDPOINT = 'https://airplaneinthesky.appspot.com/api/ecom/v2/jobs/status/';
        }
        else {
            $this->API_CREATE_ENDPOINT = 'http://localhost:8080/api/ecom/v2/jobs/';
            $this->API_STATUS_ENDPOINT = 'http://localhost:8080/api/ecom/v2/jobs/status/';
        }
    }
    
    
    public function setClientId($inClient) {
        $this->clientId = $inClient;
    }
    
    public function setSharedSecret($inKey) {
        $this->sharedSecret = $inKey;
    }
    
    public function setExternalId($inId) {
        $this->airplaneOrder->externalId = $inId;
    }
    
    public function setPickup($inPickup) {
        $this->airplaneOrder->pickUp = $inPickup;
    }
    
    public function setDeliverToName($inName) {
        $this->airplaneOrder->deliverToName = $inName;
    }
    
    public function setDeliverToCompany($inCompany) {
        $this->airplaneOrder->deliverToCompany = $inCompany;
    }
    
    public function setDeliverToAddress($inAddress) {
        $this->airplaneOrder->deliverToAddress = $inAddress;
    }
    
    public function setDeliverToSuite($inSuite) {
        $this->airplaneOrder->deliverToSuite = $inSuite;
    }
    
    public function setDeliverToCity($inCity) {
        $this->airplaneOrder->deliverToCity = $inCity;
    }
    
    public function setDeliverToState($inState) {
        $this->airplaneOrder->deliverToState = $inState;
    }
    
    public function setDeliverToZip($inZip) {
        $this->airplaneOrder->deliverToZip = $inZip;
    }
    
    public function setReadyTime($inDate) {
        date_default_timezone_set('America/Los_Angeles');
        $newDate = date('Y-m-d h:i', strtotime($inDate));
        $this->airplaneOrder->readyTime;
    }
    
    public function setDeliverToDueTime($inDate) {
        date_default_timezone_set('America/Los_Angeles');
        $newDate = date('Y-m-d h:i', strtotime($inDate));
        $this->airplaneOrder->deliverToDuetime = $newDate;
    }
    
    public function setInstructions($inInstructions) {
        $this->airplaneOrder->instructions = $inInstructions;
    }
    
    public function setPaymentMethod($inPayment) {
        $this->airplaneOrder->paymentMethod = $inPayment;
    }
    
    public function setTotal($inTotal) {
        $this->airplaneOrder->total = $inTotal;
    }
    
    public function setTip($inTip) {
        $this->airplaneOrder->tip = $inTip;
    }
    
    public function addItemToItems($quantity, $name, $sku = false) {
        if ($sku) {
            array_push($this->airplaneOrder->items, array('name' => $name, 'quantity' => intval($quantity), 'sku' => $sku));
        }
        else {
            array_push($this->airplaneOrder->items, array('name' => $name, 'quantity' => intval($quantity)));
        }
    }
    
    public function uploadOrder() {
        $retval = $this->processAndUploadOrders(array($this->airplaneOrder));
        if ($retval) {
            $this->airplaneOrder = new AirplaneOrder();
        }
        return $retval;
    }
    
    private function processAndUploadOrders($inOrders) {
        $orders = array();
        foreach ($inOrders as $order) {
            if (!isset($this->clientId)) {
                throw new AirplanesException("You must set a client id.");
                return false;
            }
        
            if (!isset($order->deliverToName)) {
                throw new AirplanesException("You must set name of the person we are delivering to.");
                return false;
            }
        
            if (!isset($order->deliverToAddress)) {
                throw new AirplanesException("You must set the address of where we are delivering to.");
                return false;
            }
        
            if (!isset($order->deliverToCity)) {
                throw new AirplanesException("You must set the city we are delivering to.");
                return false;
            }
        
            if (!isset($order->deliverToState)) {
                throw new AirplanesException("You must set the state we are delivering to.");
                return false;
            }
        
            if (!isset($order->deliverToZip)) {
                throw new AirplanesException("You must set the zip code we are delivering to.");
                return false;
            }
        
            if (!isset($order->readyTime)) {
                date_default_timezone_set('America/Los_Angeles');
                $date = date('Y-m-d h:i', time());
                $order->readyTime = $date;
            }
        
            if (!isset($order->deliverToDueTime)) {
                date_default_timezone_set('America/Los_Angeles');
                $date = date('Y-m-d h:i', time() + 2700);
                $order->deliverToDuetime = $date;
            
            }
        
            if (!isset($order->paymentMethod)) {
                throw new AirplanesException("You must set the payment method for this order.");
                return false;
            }
        
            if (!isset($order->total)) {
                throw new AirplanesException("You must set the order total.");
                return false;
            }
            
            if (isset($order->pickUp)) {
                $order->deliverToAddress = $order->pickUp . " to " . $order->deliverToAddress;
            }
        
            date_default_timezone_set('America/Los_Angeles');
            $currentDatetime = date('Y-m-d h:i', time());
        
            //Set the required fields in the JSON request
            $anOrder = array(
                'client_id'             => $this->clientId,
                'order_date'            => $currentDatetime,
                'deliver_to_name'       => $order->deliverToName,
                'deliver_to_address'    => $order->deliverToAddress,
                'deliver_to_city'       => $order->deliverToCity,
                'deliver_to_state'      => $order->deliverToState,
                'deliver_to_zip'        => $order->deliverToZip,
                'ready_time'            => $order->readyTime,
                'deliver_to_duetime'    => $order->deliverToDuetime,
                'payment_method'        => $order->paymentMethod,
                'total'                 => $order->total
            );
        
            //Now add the optional fields (if applicable)
        
            if (isset($order->externalId)) {
                $anOrder['external_id'] = $order->externalId;
            }
        
            if (isset($order->deliverToCompany)) {
                $anOrder['deliver_to_company'] = $order->deliverToCompany;
            }
        
            if (isset($order->deliverToSuite)) {
                $anOrder['deliver_to_suite'] = $order->deliverToSuite;
            }
        
            if (isset($order->instructions)) {
                $anOrder['instructions'] = $order->instructions;
            }
        
            if (isset($order->tip)) {
                $anOrder['tip'] = $order->tip;
            }
        
            if (sizeof($order->items) > 0) {
                $anOrder['items'] = $order->items;
            }
        
            //Now put the above order into a wrapper array and encode it as JSON.
        

            array_push($orders, $anOrder);
        }

        $json = json_encode($orders);
        
        $datetime           = new DateTime('America/Los_Angeles');
        $requestDate        = $datetime->format(DateTime::ISO8601);
 
        //Prepare the headers.
        $requestBodyMD5     = md5($json); //Take MD5 hash of JSON
        $utf8encoded        = utf8_encode($requestDate . $requestBodyMD5); //Utf8 encode with the date
        $stringToSign       = base64_encode($utf8encoded); // base 64 encode it
        $calculatedSig      = hash_hmac('sha1', $stringToSign, $this->sharedSecret); //HMAC it with shared key
 
        //cURL request
        $curl = curl_init($this->API_CREATE_ENDPOINT);
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
    
    public function trackJob($jobId) {
        $retval = $this->processStatusRequests(array($jobId));
        return $retval;
    }
    
    public function trackJobs($jobIds) {
        $retval = $this->processStatusRequests($jobIds);
        return $retval;
    }
    
    private function processStatusRequests($orderIds) {
        $orders = array();
        foreach ($orderIds as $orderId) {
           array_push($orders, array('order_number' => $orderId)); 
        }
        $json = json_encode($orders);
        
        $datetime           = new DateTime('America/Los_Angeles');
        $requestDate        = $datetime->format(DateTime::ISO8601);
 
        //Prepare the headers.
        $requestBodyMD5     = md5($json); //Take MD5 hash of JSON
        $utf8encoded        = utf8_encode($requestDate . $requestBodyMD5); //Utf8 encode with the date
        $stringToSign       = base64_encode($utf8encoded); // base 64 encode it
        $calculatedSig      = hash_hmac('sha1', $stringToSign, $this->sharedSecret); //HMAC it with shared key
 
        //cURL request
        $curl = curl_init($this->API_STATUS_ENDPOINT);
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

class AirplaneOrder {
    public         $externalId;
    public         $airplanesURI;
    public         $pickUp;
    public         $deliverToName;
    public         $deliverToCompany;
    public         $deliverToAddress;
    public         $deliverToSuite;
    public         $deliverToCity;
    public         $deliverToState;
    public         $deliverToZip;
    public         $readyTime;
    public         $deliverToDuetime;
    public         $instructions;
    public         $paymentMethod;
    public         $total;
    public         $tip;
    public         $items = array();
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