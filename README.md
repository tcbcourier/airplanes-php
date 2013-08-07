#Airplanes-PHP 0.1#
###A Simple PHP Wrapper that implements the Airplanes in the Sky API

Airplanes-PHP is a simple PHP class that allows you to send orders to a courier company running Airplanes in the Sky.

####Installation
To install airplanes-php, either download the zip or git clone into your directory of choice.
```
git clone https://github.com/tcbcourier/airplanes-php.git 
```
Then simply require it the files that need to interact with Airplanes in the Sky.
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

#####Creating Orders
After you init the Airplanes class, you can populate the order info.

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

#####Tracking Orders
To get the current tracking information on a single order, simply call `trackJob()` and pass the order number that was returned in the `uploadOrder()` function. `trackJob()` will return an array of JSON objects containing order information. If an invalid order number is passed the `notes` property in the JSON object will read `Order Not Found`.

```
$status = $airplane->trackJob('1234');
echo $status;

/* Result
[{"job_id": "1234", "picked_datetime": "", "created_datetime": "2013-08-06 18:49", "notes": "", "current_status": "new", "dispatched_datetime": "", "pod": ""}]
*/
```
If you wish to get the status on several orders, simply call `trackJobs()` and pass an array containing the order numbers you wish to track.
```
$trackTheseJobs = array('1001', '1002', '1003);
$statuses = $airplane->trackJobs($trackTheseJobs);
echo $statuses;
/* Result
[{"job_id": "1001", "picked_datetime": "", "created_datetime": "2013-08-06 18:49", "notes": "", "current_status": "new", "dispatched_datetime": "", "pod": ""}, {"job_id": "1002", "picked_datetime": "", "created_datetime": "2013-08-06 18:49", "notes": "", "current_status": "new", "dispatched_datetime": "", "pod": ""}, {"job_id": "1003", "picked_datetime": "", "created_datetime": "2013-08-06 18:49", "notes": "", "current_status": "new", "dispatched_datetime": "", "pod": ""}]
*/
```



####License
Airplanes-PHP is released under the BSD license.

