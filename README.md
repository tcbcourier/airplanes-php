#Airplanes-PHP 0.1#
###A Simple PHP Wrapper that implements the Airplanes in the Sky API

Airplanes-PHP is a simple PHP class that allows you to send orders to a courier company running Airplanes in the Sky.

####Installation
To install airplanes-php, simply include `Airplanes.php` in your source.
```
require_once(/path/to/Airplanes.php);
```

####Usage
Using Airplanes-PHP is easy. First, init the Airplanes class

```
$airplane = new Airplanes();
```
Now set your client id and shared secret that has been provided to you by your courier company:
```
$airplane->setClientId('myawesomecompanyid');
$airplane->setSharedSecret('mysharedsecret);
```
Once you are done that, now you can populate the order info.

```
$airplane->setDeliverToName('Doug Suriano');
$airplane->setDeliverToCompany('TCB Courier');
$airplane->setDeliverToAddress('123 Main St');
$airplane->setDeliverToSuite('Romm 237');
$airplane->setDeliverToCity('Philadelphia');
$airplane->setDeliverToState('PA');
$airplane->setDeliverToZip('19146');
$airplane->setInstructions("Be Awesome");
$airplane->setPaymentMethod(TCB_CREDIT_CARD);
$airplane->setTotal("20.00");
$airplane->setTip("5.00");
$airplane->addItemToItems('1', 'Whiteboard');
```
After you have done that, simple call `uploadOrder()` to send the order to Airplanes in the Sky.
```
try {
    $orderNumbers = $airplane->uploadOrder();
}
catch (AirplanesException $ex) {
    echo $ex;
}
```
`uploadOrder()` returns an array containing the order number of your submited order. You can store that in your database to access tracking info (coming shortly).

