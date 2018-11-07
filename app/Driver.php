<?php

namespace App;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    public function sendSMS($message)
    {
        $username = 'depotmanager'; // use 'sandbox' for development in the test environment
        $apiKey   = '09ed82d5c611fd1085fdb55ee1423b3108113273e34f96f4f6f6a226808ca3ac'; // use your sandbox app API key for development in the test environment
        $AT       = new AfricasTalking($username, $apiKey);

// Get one of the services
        $sms      = $AT->sms();

// Use the service
        $result   = $sms->send([
            'to'      => $this->phone_no,
            'message' => $message
        ]);
    }
}
