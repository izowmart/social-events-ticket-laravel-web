<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 11/5/18
 * Time: 2:47 PM
 */

namespace App\Transformers;


use App\AdvertView;
use League\Fractal\TransformerAbstract;

class AdvertViewTransformer extends TransformerAbstract
{

    public function transform(AdvertView $advertView)
    {
        return [
            'advert_id' => $advertView->advert_id,
            'user_id'   => $advertView->user_id
        ];
    }

}