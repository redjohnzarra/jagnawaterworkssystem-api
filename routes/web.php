<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

date_default_timezone_set('Asia/Manila');

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'userApi'], function () use ($router) {
  // Users Route
  $router->get('/login','UserPrivilegesController@authenticate');
  $router->get('/users',  ['uses' => 'UserPrivilegesController@getAllUsers']);
  $router->post('/users',  ['uses' => 'UserPrivilegesController@createUserPrivileges']);
  $router->get('/users/{id}',  ['uses' => 'UserPrivilegesController@getUser']);
  $router->post('/users/{id}',  ['uses' => 'UserPrivilegesController@updateUserPrivileges']);
  $router->delete('/users/{id}',  ['uses' => 'UserPrivilegesController@deleteUserPrivileges']);

  //public for consumer's inquiry
  $router->get('/inquiry/monthly-bills/unpaid/account',  ['uses' => 'MonthlyBillController@consumersInquiry']);

  
  $router->get('/consumers/{accountNo}/picture',  ['uses' => 'ConsumerController@getConsumerPicture']);
  $router->get('/consumers/{accountNo}/signature',  ['uses' => 'ConsumerController@getConsumerSignature']);

});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
  // Consumers Route
  $router->get('/consumer-types',  ['uses' => 'ConsumerTypeController@getConsumerTypes']);
  $router->get('/consumer-types/{consumerTypeId}',  ['uses' => 'ConsumerTypeController@getConsumerType']);
  $router->post('/consumer-types',  ['uses' => 'ConsumerTypeController@createConsumerType']);
  $router->post('/consumer-types/{consumerTypeId}',  ['uses' => 'ConsumerTypeController@updateConsumerType']);
  $router->delete('/consumer-types/{consumerTypeId}',  ['uses' => 'ConsumerTypeController@deleteConsumerType']);

  // Consumers Route
  $router->get('/consumers',  ['uses' => 'ConsumerController@getConsumers']);
  $router->get('/consumers/{accountNo}',  ['uses' => 'ConsumerController@getConsumer']);
  $router->post('/consumers',  ['uses' => 'ConsumerController@createConsumer']);
  $router->post('/consumers/{accountNo}',  ['uses' => 'ConsumerController@updateConsumer']);
  $router->delete('/consumers/{accountNo}',  ['uses' => 'ConsumerController@deleteConsumer']);

  // Monthly Bills Route
  $router->get('/monthly-bills',  ['uses' => 'MonthlyBillController@getMonthlyBills']);
  $router->get('/monthly-bills/{id}',  ['uses' => 'MonthlyBillController@getMonthlyBill']);
  $router->get('/monthly-bills/account/{accountNo}',  ['uses' => 'MonthlyBillController@getMonthlyBillsByAccountNo']);
  $router->get('/monthly-bills/unpaid/account/{accountNo}',  ['uses' => 'MonthlyBillController@getUnpaidMonthlyBillsByAccountNo']);
  $router->post('/monthly-bills',  ['uses' => 'MonthlyBillController@createMonthlyBill']);
  $router->post('/monthly-bills/{id}',  ['uses' => 'MonthlyBillController@updateMonthlyBill']);
  $router->delete('/monthly-bills/{id}',  ['uses' => 'MonthlyBillController@deleteMonthlyBill']);

  $router->get('/monthly-bills-report',  ['uses' => 'MonthlyBillController@monthlyBillsReport']); //pwede naay startDate ug endDate

  // Readings Route //creates monthly bill if not present and updates it if it is.
  $router->get('/readings',  ['uses' => 'ReadingController@getReadings']);
  $router->get('/readings/{id}',  ['uses' => 'ReadingController@getReading']);
  $router->get('/readings/account/{accountNo}',  ['uses' => 'ReadingController@getReadingsByAccountNo']);
  $router->post('/readings',  ['uses' => 'ReadingController@createReading']);
  $router->post('/readings/{id}',  ['uses' => 'ReadingController@updateReading']);
  $router->delete('/readings/{id}',  ['uses' => 'ReadingController@deleteReading']);

  // Payments Route
  $router->get('/payments',  ['uses' => 'PaymentController@getPayments']);
  $router->get('/payments/{id}',  ['uses' => 'PaymentController@getPayment']);
  $router->get('/payments/account/{accountNo}',  ['uses' => 'PaymentController@getPaymentsByAccountNo']);
  $router->get('/payments/monthly-bill/{monthlyBillId}',  ['uses' => 'PaymentController@getPaymentsByMonthlyBill']);
  $router->post('/payments',  ['uses' => 'PaymentController@createPayment']);
  $router->post('/payments/{id}',  ['uses' => 'PaymentController@updatePayment']);
  $router->delete('/payments/{id}',  ['uses' => 'PaymentController@deletePayment']);

  // Payments Route
  $router->get('/settings',  ['uses' => 'SettingsController@getSettings']);
  $router->post('/settings',  ['uses' => 'SettingsController@upsertSettings']);

  // SMS Route
  $router->post('/send-sms', ['uses' => 'SMSController@sendSMSURL']);
  $router->get('/send-sms-to-all-for-current-bill', ['uses' => 'SMSController@sendSMSBillToAllConsumer']);
});
