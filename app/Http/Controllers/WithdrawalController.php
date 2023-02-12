<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Process customer withdrawal request
     */
    public function withdraw()
    {
        //
    }

    private function fees($amount)
    {
        return get_fees($amount);
    }
}
