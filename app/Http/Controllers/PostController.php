<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $userId=auth()->id();
        $posts = Post::where('user_id', $userId)->get();
       return view('index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $user=auth()->id();
        $validated=$request->validate([
            'title'=>'required|string|max:255',
            'content'=>'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
           
        $validated['user_id']=$user;
       if ($request->hasFile('image')) {
         $validated['image'] = $request->file('image')->store('posts', 'public');         
        }
       Post::create($validated);
        } catch (\Throwable $th) {
            //throw $th;
        return redirect()->back()->with('error', 'Error: '.$th->getMessage());

        }
        
      
     
     
        return redirect()->route('post.index')->with('success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
             //
        $user=auth()->user();
        $posts=Post::findOrFail($id);
        $validated=$request->validate([
            'title'=>'title',
            'content'=>'content',
            'image'=>'image'
        ]);
        if($request->hasFile('image')){
        $request->file('image')->store('uploads', 'public');
        }
        $validated['user_id']=$user->id();
        $posts->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $posts=Post::findOrFail($id);
        $posts->delete();
        return redirect()->route('dashboard')->with('success');
    }
}
