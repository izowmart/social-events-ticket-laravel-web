<?php

namespace App\Http\Controllers\Api;

use App\Advert;
use App\Advert_View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AdvertController extends Controller
{
    public function index()
    {
        try {
            $adverts = Advert::all()->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($adverts),
                    "data" => $adverts,
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

    public function create_advert_view(Request $request)
    {
        $advert_id = $request->input('advert_id');
        $user_id = $request->input('user_id');
        try {
            $advert_view = Advert_View::where('advert_id',$advert_id)->where('user_id',$user_id);
            if ($advert_view == null){
                $advert_view = new Advert_View();
                $advert_view->advert_id = $advert_id;
                $advert_view->user_id = $user_id;
                $advert_view->save();
            }else{
                $advert_view_count =$advert_view->count + 1;
                $advert_view->count = $advert_view_count;
                $advert_view->save();
            }
            return Response::json(array(
                    "success" => true,
                    "message" => "advert view created successfully",
                    "data" => $advert_view,
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
