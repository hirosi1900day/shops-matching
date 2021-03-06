<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
class FavoritesController extends Controller
{
    public function store($id){
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return;
        // redirect(route('shops.index'));
    }
    
    public function destroy($id){
         // 認証済みユーザ（閲覧者）が、 idのユーザをフォローする
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
       return;
    }
    public function index(){
        $shops=Auth::user()->favorites()->get();
        return view('favorite.index',['shops'=>$shops]);
    }
    public function like_check($id){
        $result=(string)\Auth::user()->is_favorite($id);
        return $result;
    }

}
