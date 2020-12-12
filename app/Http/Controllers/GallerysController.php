<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use Illuminate\Support\Facades\Storage;
use App\Gallery;

class GallerysController extends Controller
{
    public function create(){
        
        // 作成ビューを表示
        return view('gallery.create');
    }
    public function store(Request $request){
      
        $request->validate([
        'image_location'=>['file','mimes:jpeg,png,jpg,bmb','max:2048'],
        $request->validate([
            'image_location' => 'required']),
      ]);
       if($file = $request->image_location){
        //保存するファイルに名前をつける    
        $fileName = time().'.'.$file->getClientOriginalExtension();
        
        $path = Storage::disk('s3')->putFileAs('/shopsGallery',$file, $fileName,'public');
        
       }
       
        $request->user()->shops()->first()->gallerys()->create([
        'image_location'=>$path
        
        ]);
        return redirect(route('gallery.showGallerys', ['id' => $request->user()->shops()->first()->id]));
     
    }
    public function showGallerys($id){
        //$idショップのID
        
        $gallerys=Shop::findOrFail($id)->gallerys()->get();
       
        $gallery_images=[];
        foreach($gallerys as $index=>$gallery){
           $gallery_images[$index] = Storage::disk('s3')->url($gallery->image_location);
        }
       
        
          // メッセージ一覧ビューでそれを表示
        return view('gallery.showGallerys', [
            'gallerys' => $gallerys,
            'gallery_images'=>$gallery_images,
           
        ]);
        
    }
    public function destroy($id){
         // idの値で検索して取得
        $gallery = Gallery::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $gallery->shop()->first()->user_id) {
           $deletename=$gallery->image_location;
        // $deletePath='public/uploads/'.$deletename;
        Storage::disk('s3')->delete($deletename);
        $gallery->delete();
            
        }

        // 前のURLへリダイレクトさせる
        return back();
    }
    
}
