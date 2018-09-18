<?php

namespace App\Transformers;

use App\Scanner;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ScannerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Scanner $scanner
     *
     * @return array
     */
    public function transform(Scanner $scanner)
    {
        return [
            'id'            => $scanner->id,
            'name'          => $scanner->first_name.' '.$scanner->last_name,
            'email'         => $scanner->email,
            'created_at'    => Carbon::parse($scanner->created_at)->toDateTimeString(),
            'updated_at'    => Carbon::parse($scanner->updated_at)->toDateTimeString(),

        ];
    }
}
