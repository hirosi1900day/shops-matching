<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\Tag;
use App\Recruit;
use Illuminate\Support\Facades\Storage;

class ShopsController extends Controller
{
    
    public function welcom(){
        $shops_new=Shop::orderBy('created_at','desc')->limit(4)->get();
        $shop_favorite=Shop::withCount('favorite_users')->orderBy('favorite_users_count', 'desc')
        ->limit(4)
        ->get();
        $recruits=Recruit::orderBy('created_at','desc')->limit(4)->get();
        $prefecture_array=config('const.prefecture_array');
        $shop_type_array=config('const.shop_type_array');
        return view('welcom.welcom',['shops_new'=>$shops_new,
                    'shop_favorite'=>$shop_favorite,
                    'prefecture_array'=>$prefecture_array,
                    'shop_type_array'=> $shop_type_array,
                    'recruits'=>$recruits,
                     ]);
     }
    public function index(){
        $shops = Shop::orderBy('created_at','desc')->paginate(10);
          // メッセージ一覧ビューでそれを表示
        return view('shops.index2', [
            'shops' => $shops
        ]);
    }
    public function create(){
         
        // メッセージ作成ビューを表示
        return view('shops.create');
    }
    public function store(Request $request){

        $request->validate([
        'name'=>['required','string','max:255'],
        'shop_location'=>['required','string','max:255'],
        'free_time'=>['required','string','max:255'],
        'shop_type'=>['required'],
        'shop_introduce'=>['required','string','max:255'],
        'image_location'=>['file','mimes:jpeg,png,jpg,bmb','max:600','required',],
        
       ]);
       
        if($file = $request->image_location){
        //保存するファイルに名前をつける    
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs('/',$file, $fileName,'public');
    }else{
        //画像が登録されなかった時はから文字をいれる
        $name = "";
    }
        $request->user()->shops()->create([
        'name'=>$request->name,
        'image_location'=>$fileName,
        'shop_location_prefecture'=>$request->shop_location_prefecture,
        'shop_location'=>$request->shop_location,
        'free_time'=>$request->free_time,
        'shop_type'=>$request->shop_type,
        'shop_introduce'=>$request->shop_introduce,
   ]);
      
        preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tags, $match);
        $tags = [];
      
        // $matchの中でも#が付いていない方を使用する(配列番号で言うと1)
    foreach($match[1] as $tag) {
        // firstOrCreateで重複を防ぎながらタグを作成している。
        $record = Tag::firstOrCreate(['name' => $tag]);
        array_push($tags, $record);
      }

        $tags_id = [];
    foreach($tags as $tag) {
         array_push($tags_id, $tag->id);
      }
         $request->user()->shops()->orderBy('created_at', 'desc')->first()->tags()->attach($tags_id);
         return redirect(route('shops.index'));
    }
    public function serch_tag_index(Request $request){
        preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tags, $match);
        $shops = [];
       
         // $matchの中でも#が付いていない方を使用する(配列番号で言うと1)
        foreach($match[1] as $tag) {
              // firstOrCreateで重複を防ぎながらタグを作成している。
             $serch_result_shop = Tag::where(['name' => $tag])->get()[0]->shops;
              array_push($shops, $serch_result_shop);
         }
          
        return view('shops.index2',['shops'=>$shops[0]]);
    }
    public function show($id){
        
       
        $shop = Shop::findOrFail($id);
        $path = Storage::disk('s3')->url($shop->image_location);
        $prefecture_array=config('const.prefecture_array');
        $shop_type_array=config('const.shop_type_array');
        
         // メッセージ詳細ビューでそれを表示
        return view('shops.show', [
            'shop' => $shop,
            'prefecture_array'=>$prefecture_array,
            'shop_type_array'=>$shop_type_array,
         ]);
    }
    
    public function mypage_shop(){
        $data = [];
        if (\Auth::check()){
            
            // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザのshopの一覧を作成日時の降順で取得
            $shops = $user->shops()->orderBy('created_at', 'desc')->get();
            $prefecture_array=config('const.prefecture_array');
            $shop_type_array=config('const.shop_type_array');
            $data = [
                'user' => $user,
                'shops' => $shops,
                'prefecture_array'=>$prefecture_array,
                'shop_type_array'=>$shop_type_array,
            ];
            return view('shops.user_shop',$data);
        }
     }
    public function edit($id){
        // idの値でshopを検索して取得
        $shop = Shop::findOrFail($id);
        // メッセージ編集ビューでそれを表示
        return view('shops.edit', [
            'shop' => $shop,
        ]);
    }
    public function update(Request $request, $id){
        $shop = Shop::findOrFail($id);
        $request->validate([
        'name'=>['string','max:255'],
        'image_location'=>['file','mimes:jpeg,png,jpg,bmb','max:600'],
       ]);
     if($file = $request->image_location){
      
        $fileName = time().'.'.$file->getClientOriginalExtension();
        Storage::disk('s3')->delete($shop->image_location);
        $path = Storage::disk('s3')->putFileAs('/', $file, $fileName,'public');
       
    }
        $shop->name=$request->name;
         if($file = $request->image_location){
        $shop->image_location=$fileName;
        }
        if($request->shop_location_prefecture!=0){
            $shop->shop_location_prefecture=$request->shop_location_prefecture;
        }
        $shop->shop_location=$request->shop_location;
        $shop->free_time=$request->free_time;
        if($request->shop_type!=0){
             $shop->shop_type=$request->shop_type;
        }
        $shop->shop_introduce=$request->shop_introduce;
        $shop->save();
        preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶ亜-熙]+)/u', $request->tag, $match);
        $before = [];
        foreach($shop->tags()->get() as $tag){
           array_push($before, $tag->name);
        }
        $after = [];
        foreach($match[1] as $tag){
           // 普通に新しいのが来たら新規作成する動き
           $record = Tag::firstOrCreate(['name' => $tag]);
           array_push($after, $record);
        }
 
        $tags_id = [];
        foreach($after as $tag) {
            array_push($tags_id, $tag->id);
        }
        $shop->tags()->sync($tags_id);
        $shop->save();
        return redirect(route('mypage.shop'));
    }
    public function destroy($id){
        // idの値で投稿を検索して取得
        $shop = Shop::findOrFail($id);
       
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() == $shop->user_id) {
            $deletename=$shop->image_location;
            // $deletePath='public/uploads/'.$deletename;
            Storage::disk('s3')->delete($deletename);
            $shop->delete();
        }
        // 前のURLへリダイレクトさせる
        return back(); 
    }
    public function narrow_down(Request $request){
        if($request->prefecture_id==0 && $request->shop_type==0){
            $shops=Shop::all();
        }elseif($request->prefecture_id==0){
            $shops=Shop::where('shop_type',$request->shop_type)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }elseif($request->shop_type==0){
             $shops=Shop::where('shop_location_prefecture',$request->prefecture_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }else{
             $shops=Shop::where('shop_location_prefecture',$request->prefecture_id)
                    ->where('shop_type',$request->shop_type)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
        $prefecture_array=config('const.prefecture_array');
        $shop_type_array=config('const.shop_type_array');
        return view('shops.index2',
        ['shops'=>$shops,
         'prefecture_array'=>$prefecture_array,
         'shop_type_array'=>$shop_type_array,
        ]);
    }
     

    
}
