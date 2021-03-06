<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use Illuminate\Support\Facades\Storage;
use App\Gallery;

class GallerysController extends Controller
{
    public function create($id){
        $shop=Shop::findOrFail($id);
        // 作成ビューを表示
        return view('gallery.create',['shop'=>$shop]);
    }
    public function store(Request $request,$id){
        $request->validate([
        'image_location'=>['file','mimes:jpeg,png,jpg,bmb','max:600','required']
         ]);
       if($file = $request->image_location){
        //保存するファイルに名前をつける    
        $fileName = time().'.'.$file->getClientOriginalExtension();
        
        $path = Storage::disk('s3')->putFileAs('/shopsGallery',$file, $fileName,'public');
        
       }
        $shop=Shop::findOrFail($id);
        $shop->gallerys()->create([
        'image_location'=>$path
        
        ]);
        return redirect(route('gallery.showGallerys', ['id' => $shop->id]));
     
    }
    public function showGallerys($id){
        //$idショップのID
        $shop=Shop::findOrFail($id);
        $gallerys=Shop::findOrFail($id)->gallerys()->get();
        $user=Shop::findOrFail($id)->user()->get();
          // メッセージ一覧ビューでそれを表示
        return view('gallery.showGallerys', [
            'gallerys' => $gallerys,
            'user'=>$user,
            'shop'=>$shop,
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
