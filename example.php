<?php
require_once('Airplanes.php');

//Init a new Airplanes Object
$airplane = new Airplanes();


//Set the Client Id that was provided to you by your courier company.
$airplane->setClientId('myawesomeclientId');

//Set the shared secret provided to you by your courier company
$airplane->setSharedSecret('shared_secret');

//(Optional) Set an external Id of the order. 
// Useful for inhouse ids and such.
$airplane->setExternalId('Test#320');

//Set the name of the person the order is being delivered to
$airplane->setDeliverToName('Doug Suriano');

//(Optional) Set the name of the company the order is being delivered to
$airplane->setDeliverToCompany('TCB Courier');

//Set the address where the order is being delivered to.
$airplane->setDeliverToAddress('123 Main St');

//(Optional Set if there is a suite or room number for the order
$airplane->setDeliverToSuite('Romm 237');

//Set the Deliver City
$airplane->setDeliverToCity('Philadelphia');

//Set the Deliver State
$airplane->setDeliverToState('PA');

//Set the Deliver Zip
$airplane->setDeliverToZip('19146');

//Set any special instructions
$airplane->setInstructions("Be Awesome");

//Set the payment method
$airplane->setPaymentMethod(TCB_CREDIT_CARD);

//Set the order total
$airplane->setTotal("20.00");

//(Optional) Set the tips
$airplane->setTip("5.00");

// (Optional) Set the items being delivered
$airplane->addItemToItems('1', 'Whiteboard');
$airplane->addItemToItems('2', 'Black Markets', '484844');

try {
    $airplane->uploadOrder();
}
catch (AirplanesException $ex) {
    echo $ex;
}
?>