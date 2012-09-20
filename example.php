<?php
    define('SMS_KEY', 'your-api-key-put-here');
    include_once(dirname(__FILE__) . '/Sms.php');
    
    $sms = new Sms();

    //Send 1 sms
    $results = $sms->send(array(
        'recipient' => '+79021234567',
        'sender' => 'PHPScript',
        'text' => 'Hello World!'
    ));
    var_dump($results);
    
    //Send ~ sms
    $results = $sms->send(array(
        'recipient' => '+79021234567',
        'sender' => 'PHPScript',
        'text' => 'Hello World!'
    ));
    var_dump($results);

    //get status of 1 sms by custom (client-side) ID
    $results = $sms->status(array('id' => 123));
    var_dump($results);
    
    //get status of 1 sms by server-side ID
    $results = $sms->status(array('sms_id' => 13434));
    var_dump($results);
    
    //get status of ~ sms by mixed values
    $results = $sms->status(array(array('id' => 131), array('sms_id' => 13434), array('id' => 132)));
    var_dump($results);
?>
