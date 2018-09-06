<?php

namespace App\Http\Controllers\Api;

use App\Advert;
use App\AdvertView;
use App\Http\Resources\AdvertResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AdvertController extends Controller
{
    public function index()
    {
        try {
            $adverts = Advert::all();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($adverts),
                    "data" => AdvertResource::collection($adverts),
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

    public function store(Request $request)
    {
        try {
            //validation first
            $data = [];
            $data['title'] = $request->title;
            $data['description'] = $request->description;
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
            $data['admin_id'] = $request->admin_id;

            if ($request->has('image')) {
                $file = $request->file('image');
                $file_name = uniqid().time().".jpg";
                $file->move(public_path('uploads/adverts'),$file_name);
                $data['image_url'] = $file_name;
            }


            $advert = Advert::create($data);

            if ($advert) {
                return response()->json([
                    'success' => true,
                    'message' => 'advert created',
                    'datum'   => AdvertResource::collection($advert),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'advert not created',
                    'datum'   => null
                ]);
            }
        } catch ( \Exception $exception ) {
            return response()->json([
                'success'=>false,
                'message'=>"failed ".$exception->getMessage(),
            ]);
        }
    }
}
