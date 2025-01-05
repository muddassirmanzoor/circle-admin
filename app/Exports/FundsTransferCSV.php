<?php

namespace App\Exports;

use App\Freelancer;
use App\FreelancerEarning;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FundsTransferCSV implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $freelancers;
    function __construct($freelancers) {
            $this->freelancers = $freelancers;
    }
    public function view(): View
    {
        return view('exports.funds', [
            'records' => $this->freelancers
        ]);
    }
}
