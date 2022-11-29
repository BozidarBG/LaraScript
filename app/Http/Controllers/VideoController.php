<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    //
    public function index(){
        return view('video_player.index');
    }

    public function videoHA(){
        return view('video_player.ha_njegov');
    }

    public function getVideo(Video $video)
    {
        info($video);

        $path = $video->path;
        $fileContents = Storage::disk('videos')->get("{$path}");
        $response = Response::make($fileContents, 200);
        $response->header('Content-Type', "video/mp4");
        return $response;
    }

    public function manyVideos(){
        return view('video_player.videos', ['videos'=>Video::all()]);
    }

}
