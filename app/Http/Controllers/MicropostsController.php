<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostsController extends Controller
{
   
     public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
             $count_microposts = $user->microposts()->count();
             $count_followings = $user->followings()->count();
             $count_followers = $user->followers()->count();

            $data = [
                'user' => $user,
                'microposts' => $microposts,
                'count_microposts' =>$count_microposts,
                'count_followings' => $count_followings,
            'count_followers' => $count_followers,
            ];
            return view('users.show',$data);
        }
        else{return view('welcome');
    
    }
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
    
    public function destroy($id)
    {
        $micropost = \App\Micropost::find($id);

        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
        }

        return redirect()->back();
    }
    
}
