<?php

namespace App\Http\Controllers\Api;

use App\Advert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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

    public function create(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        try {
            $file_path = "adverts/" . $admin_id . "_" . uniqid() . "." . $request->file('image')->extension();
            $disk = Storage::disk('uploads');
            $success = $disk->put($file_path, fopen($_FILES["image"]['tmp_name'], 'r+'));
            if ($success) {
                $image_url = "uploads/" . $file_path;
                $advert = new Advert();
                $advert->admin_id = $admin_id;
                $advert->title = $title;
                $advert->description = $description;
                $advert->image_url = $image_url;
                $advert->start_date = $start_date;
                $advert->end_date = $end_date;
                $advert->save();
                return Response::json(array(
                        "success" => true,
                        "message" => "advert successfully created",
                        "data" => $advert,
                    )

                );
            } else {
                return Response::json(array(
                        "success" => false,
                        "message" => "error uploading image",
                    )

                );
            }

        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error creating advert" . $exception,
                )

            );
        }
    }
    public function delete(Request $request){
        $advert_id = $request->input('advert_id');
        try{
            Advert::where('id',$advert_id)->delete();
            return Response::json(array(
                "success" => true,
                "message" => "advert successfully deleted",
            ));
        }catch(\Exception $exception){
            return Response::json(array(
                "success" => false,
                "message" => "error deleting advert",
            ));
        }
    }
}
