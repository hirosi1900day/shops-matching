<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chatroom;
use App\Chatroomuser;
use App\Chatmessage;
use App\User;
use App\Shop;
use Auth;
use App\Events\MessageCreated;

class ChatController extends Controller
{
    
   public function create_chatroom($id){
      //$idはshop_id
      // チャットルームを取得
      $chat_room_id = Chatroom::where('user_id', Auth::id())
        ->where('shop_id',$id)
        ->pluck('id');
        //チャットルームがなければ作成する
      if($chat_room_id->isEmpty()){
          Chatroom::create(['user_id'=>Auth::id(),'shop_id'=>$id,]); //チャットルーム作成
          $latest_chat_room = Chatroom::orderBy('created_at', 'desc')->first(); //最新チャットルームを取得
          $chat_room_id = $latest_chat_room->id;
        }
        // チャットルーム取得時はオブジェクト型なので数値に変換
      if(is_object($chat_room_id)){
            $chat_room_id = $chat_room_id->first();
        }
      
      return redirect(route('chat.view', ['id' => $chat_room_id]));
   }
   public function show($id){
       //$idはchatroom_id
      $users=[];
      $chatroom=Chatroom::findOrFail($id);
      $messages=$chatroom->message()->take(15)->orderBy('created_at','desc')->get();
      if($messages->isEmpty()){
          $messages=[];
      }
      foreach($messages as $index=>$message){
         $users[$index]=User::findOrFail($message->user_id);
      }
      $shop=Shop::findOrFail($chatroom->shop_id);
    
      return ['messages'=>$messages,
             'users'=>$users,
             'shop'=>$shop,
             'chatroom'=>$chatroom,]; 
   }
   public function view($id){
   
      $chatroom=Chatroom::findOrFail($id);
      $shop=Shop::findOrFail($chatroom->shop_id);
      if($shop->user()->first()->id!=Auth::id()){
          $user=$shop->user()->first();
      }else{
          $user=User::findOrFail($chatroom->user_id);
      }
       return view('chat.show2',[
             'chatroom'=>$chatroom,
             'shop'=>$shop,
             'user'=>$user,
             ]);
   }
   public function store(Request $request,$id){
       //$idはchatroom_id
       $request->validate([
            'message' => 'required|max:255',
        ]);
        //user_idでメッセージ送信者を特定する
        //shop_idでどのショップに対する内容かを特定する
        //これらはinput hiddenで送信する
       $message = Chatroom::findOrFail($id)->message()->create([
          'user_id'=>$request->user_id,
          'shop_id'=>$request->shop_id,
          'chatroom_id'=>$id,
          'message'=>$request->message,
           ]);
          return ;
        
   
   }
   public function user_index(){
       $chat_rooms = Chatroom::where('user_id', Auth::id())->get();
       $shops=[];
       foreach($chat_rooms as $index=>$chat_room){
           $shops[$index]=Shop::findOrFail($chat_room->shop_id);
       }
       return view('chat.user_index',['shops'=>$shops,'chat_rooms'=>$chat_rooms,]);
   }
   public function shop_index($id){
       //$idはshop_id
       $chat_rooms = Chatroom::where('shop_id', $id)->get();
       $users=[];
       foreach($chat_rooms as $index=>$chat_room){
           $users[$index]=User::findOrFail($chat_room->user_id);
       }
       return view('chat.shop_index',['users'=>$users,'chat_rooms'=>$chat_rooms,]);
       
   }
   public function message_redirect($id){
        return redirect(route('chat.show', ['id' => $id]));  
    }
   public function help_view(){
       $help_question=["A=>会員登録方法\nB=>募集方法\nC=>店舗作成方法\nD=>チャットでのメッセージやりとり方法"];
       return view('chat.show_help',['help_question'=>$help_question]);
   }
   public function store_help(Request $request){
         $request->validate([
        'help_message'=>['required'],
        ]);
        $input_question=[$request->help_message];
        $help_message=[];
        switch ($request->help_message) {
            case 'A':
              $help_message=['上部のナビゲーションバーの会員登録にアクセスし','必要事項入力してください'];
              break;
            case 'B':
               $help_message=['会員登録後、上部のナビゲーションバー=>募集作成をクリックし','必要事項を登録してください','会員登録方法がわからない場合は「A」を入力してください'];
               break;
            case 'C':
              $help_message=['会員登録後、上部のナビゲーションバー=>店舗作成をクリックし','必要事項を登録してください','会員登録方法がわからない場合は「A」を入力してください'];
               break;
            case 'D':
              $help_message=['会員登録後、上部のナビゲーションバー=>ショップ一覧よりお気にりの店舗を探し',
              'メッセージボタンよりメッセージを送信してください'];
               break;
            default:
               $help_message=['申し訳ありませんがお問い合わせページよりご連絡お願いします'];
        }
        return [
                'help_message'=>$help_message,
                'input_question'=>$input_question
               ];
   }
        
}
