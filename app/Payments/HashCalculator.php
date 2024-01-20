<?php
namespace App\Payments;

class HashCalculator {

    private $signatureKey = '';
    function __construct()
    {
        $this->signatureKey= env('PAYMENTS_SIGNATURE_KEY');
    }
    public function calculateHmac($data) {
      return base64_encode(hash_hmac("sha256", $data, $this->signatureKey, true));
    }
  }
  