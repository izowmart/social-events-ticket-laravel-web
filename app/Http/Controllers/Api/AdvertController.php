<?php

namespace App\Http\Controllers\Api;

use App\Advert;
use App\AdvertView;
use App\Http\Resources\AdvertResource;
use App\Transformers\AdvertTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Spatie\Fractalistic\ArraySerializer;

class AdvertController extends Controller
{
    public function index()
    {
        try {
            $adverts = Advert::all();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($adverts),
                    "data" => fractal($adverts,AdvertTransformer::class,ArraySerializer::class),
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching adverts!" . $exception,
                )

            );
        }
    }

    public function advert_view(Request $request)
    {
        $advert_id = $request->input('advert_id');
        $user_id = $request->input('user_id');
        $count = $request->input('count');
        try {
            $advert_view = AdvertView::where('advert_id',$advert_id)->where('user_id',$user_id)->first();
            if ($advert_view == null){
                $advert_view = new AdvertView();
                $advert_view->advert_id = $advert_id;
                $advert_view->user_id = $user_id;
                $advert_view->count = $count;
                $advert_view->save();
            }else{
                $advert_view_count =$advert_view->count + $count;
                $advert_view->count = $advert_view_count;
                $advert_view->save();
            }
            return Response::json(array(
                    "success" => true,
                    "message" => "advert view created successfully",
                    "data" => []//fractal($advert_view,AdvertTransformer::class,ArraySerializer::class),
                )

            );

        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error creating advert view",
                )

            );
        }

    }
}
