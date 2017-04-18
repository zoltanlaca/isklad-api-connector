# IskladRestApi
PHP RestApi connector for Isklad

With this class we have just 3 steps to send data to Isklad:
1. Include this class to your php script
    include('IskladRestApi.class.php');
2. Initialize it / test connection
    IskladRestApi::Initialize(AUTH_ID, AUTH_KEY, AUTH_TOKEN);
    (under development we can test the connection with  IskladRestApi::ConnectionTest(); )
3. If ConnectionTest is OK, we call the method, we want, e.g. CreateNewOrder()
    IskladRestApi::CreateNewOrder($params);

Be sure to use ConnectionTest only under development, do not call it before each request!

$params is an array, which must be prepared for each method in structure described in the PDF API Manual.
e.g.This params is data from the real order from Prestashop (name, adress, contact, items...)

We can get last request, or last response, just call:
IskladRestApi::GetRequest();
IskladRestApi::GetResponse();

Thats all :) Enjoy!
