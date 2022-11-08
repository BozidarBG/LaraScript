<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;

/**********************************************/
//   JEDNA STRANA CRUD //
/************************************************/


class PostController extends Controller
{

    public function index()
    {
        return view('crud_strana.posts', ['items'=>Post::paginate()]);
    }



    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required'
        ]);

        $p=new Post();
        $p->title=$request->title;
        $p->body=$request->body;
        $p->user_id=auth()->id();

        $p->save();
        $p->name=auth()->user()->name;
        return response()->json(['success'=>$p]);
        //session()->flash('success', 'Row Created');
        //return redirect()->route('posts.index');
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required'
        ]);

        $p=Post::find($id);
        if($p){
            $p->title=$request->title;
            $p->body=$request->body;
            $p->save();
            return response()->json(['success'=>$p]);
        }
        return response()->json(['errors'=>'Such post does not exist!']);


        //session()->flash('success', 'Row Created');
        //return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $post=Post::find($id);
        if($post){
            $post->delete();
            return response()->json(['success'=>'Row deleted']);
            //ili http verzija
            //session()->flash('success', 'Deleted successfully');
            //return redirect()->route('posts.index');
        }else{
            return response()->json(['errors'=>'Post not found']);
        }


    }
}
