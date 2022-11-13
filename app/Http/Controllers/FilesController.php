<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class FilesController extends Controller
{
    //protected $dirs_without_images=['css', 'js', 'cache', 'cron', 'font-awesome', 'fonts', 'HTML_template', 'newsletter', 'novosti', 'plugin','rss', 'sass', 'scripts','tinymce','vendor', 'widget'];
    protected $dirs_with_images=['images', 'img'];
    protected $folder_id=1;
    protected $images=[];

    public function index(){
        return view('file_manager.index');
    }

    public function create(){

    }
    protected function validImage($url) {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $imgExtArr = ['jpg', 'jpeg', 'png', 'svg'];
        if(in_array($extension, $imgExtArr)){
            return true;
        }
        return false;
    }

    public function getFolders(){
        $starting_dir=public_path('');
        $scanned=array_diff(scandir($starting_dir), array('..', '.'));
        //info($scanned);
        $result=[];
        foreach ($scanned as $item){

            if(is_dir($item) && in_array($item, $this->dirs_with_images)){
                $temp_result=['type'=>'folder'];
                $temp_result['name']=$item;
                $temp_result['id']=$this->folder_id;
                $temp_result['parent']=0;
                $temp_result['path']=$item;
                $temp_result['folders'] = $this->returnFolder($item, $this->folder_id);
                $result[]=$temp_result;
                $this->folder_id +=1;
            }
        }
       // info($result);
        return response()->json(['result'=>$result]);
    }

    protected function returnFolder($dir, $parent){
        $previous_path=$dir.DIRECTORY_SEPARATOR;
        $scanned=array_diff(scandir($dir), array('..', '.'));
        $result=[];
        //info(json_encode($scanned));
        foreach ($scanned as $item){

            if(is_dir($previous_path.$item)){
                $temp_result=['type'=>'folder'];
                $temp_result['name']=$item;
                $this->folder_id +=1;
                $temp_result['id']=$this->folder_id;
                $temp_result['parent']=$parent;
                $temp_result['path']=$previous_path.$item;
                $temp_result['folders'] = $this->returnFolder($previous_path.$item, $temp_result['id']);
                $result[]=$temp_result;
            }
        }
        //info(json_encode($this->sortResult($result)));
        return $this->sortResult($result);
    }

    protected function sortResult($result){
        //info(json_encode($result));
        $folders=[];
        $image_objects=[];
        foreach($result as $obj){
            //info($obj);
            if($obj['type']==="folder"){
                $folders[]=$obj;
            }else{
                $image_objects[]=$obj;
            }
        }
        //info($image_objects);
        //info(json_encode(array_merge($folders, $image_objects)));
        return array_merge($folders, $image_objects);
    }


    public function getImages(Request $request){
        //info($request->all());
        $folders=explode(DIRECTORY_SEPARATOR, $request->path);
        if(!in_array($folders[0], $this->dirs_with_images)){
            return response()->json(['error']);
        }
        $images_arr=[];
        $scanned=array_diff(scandir($request->path), array('..', '.'));
        foreach ($scanned as $item){
            if($this->validImage($item)){
                //info($request->path.DIRECTORY_SEPARATOR.$item);
                //$size=File::size($request->path.DIRECTORY_SEPARATOR.$item); //ok. u bajtovima
//                $imageData = Storage::get($request->path.DIRECTORY_SEPARATOR.$item);
//
                $img=getimagesize($request->path.DIRECTORY_SEPARATOR.$item);
                //$width = Image::make($request->path.DIRECTORY_SEPARATOR.$item)->width(); // getting the image width
                //$height = Image::make($request->path.DIRECTORY_SEPARATOR.$item)->height(); // getting the image height
                //info(json_encode($img));
                //info( $img[1]);
                $images_arr[]=['path'=>DIRECTORY_SEPARATOR.$request->path.DIRECTORY_SEPARATOR.$item, 'width'=>$img[0], 'height'=>$img[1]];
            }
        }
        //info(json_encode($images_arr));
        return response()->json(['success'=>$images_arr]);
    }

    public function storeImage(Request $request){
        //info(json_encode($request->all()));
        $validator=Validator::make($request->all(), [
            'image'=>'required|image',
            'folder_path'=>'required',
            'name'=>'sometimes|max:200'
        ]);
        if($validator->fails()){
            $errors_arr=[];
            $err=collect($validator->errors());

            foreach($err as $obj){
                //info($value);
                foreach($obj as $key=>$msg){
                    $errors_arr[]=$msg;
                }
            }
            return response()->json(['errors'=>$errors_arr]);
        }
        $image = $request->file('image');
        if($request->has("name")){
            //da li već postoji slika sa istim imenom u ovom folderu
            $file_name=Str::replace(" ", "_", $request->name).".png";
            $pathtofile=$request->folder_path.DIRECTORY_SEPARATOR.$file_name;
            if(file_exists($pathtofile)){
                $file_name=Str::replace(" ", "_", $request->name).time().".png";
            }
        }else{
            $file_name=time().".png";
        }

        //info('file name je'.$file_name);
        $path = public_path($request->folder_path)."/".$file_name;
        //info('pathj je '.$path);
        if(Image::make($image->getRealPath())->save($path)){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['errors'=>['Slika nije aploudovana.']]);
        }
    }


    public function storeFolder(Request $request){
        //info(json_encode($request->all()));
        $validator=Validator::make($request->all(), [
            'folder_path'=>'required',
            'name'=>'required|max:200'
        ]);
        if($validator->fails()){
            //info($validator->errors());
            return response()->json(['errors'=>$validator->errors()]);
        }
        if(!is_writable(public_path()."/".$request->folder_path)){
            return response()->json(['errors'=>['Nije moguće pisati u folderu. Obratiti se programeru']]);
        }else{
            $new_folder=Str::replace(" ", "_", $request->name);
            $folderPath=public_path($request->folder_path)."/".$new_folder;
            $response=File::makeDirectory($folderPath, 0777, true, true);
            //info($response);
            //return response()->json(['success'=>['path'=>$folderPath]]);
            return response()->json(['success'=>['name'=>$new_folder, 'path'=>$request->folder_path]]);
        }
//
    }


    public function storeVideo(Request $request){

    }

    public function destroy(Request $request){

    }

}
