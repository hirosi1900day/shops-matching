<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Shops Matchig</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Permanent+Marker rel="stylesheet">
        <link href="{{ secure_asset('css/welcome.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>
    </head>

    <body>
<div id="welcome-load">
    @include('commons.navbar')
    
   
<div class="background-skyblue">
    </br>
    </br>
    </br>
    </br>
    </br>
   {!! Form::open(['route'=>'shops.serch_tag_index','enctype'=>'multipart/form-data']) !!}
    <div class='form-group'>
        {!! Form::label('tags', 'タグで店舗を検索する（#~~の形で記入、複数検索可能）') !!}
        {!! Form::text('tags',old('tags'),['class'=>'form-control']) !!}
    </div>
        {!! Form::submit('検索する',['class'=>'btn btn-info']) !!}
        {!! Form::close() !!}   
  <section>
     <section class="swiper">
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <div class="swiper-slide slide01">
            <div class="slide-contents">
              <div class="slide-contents-title">
                あなたの店舗をシェアしよう<br>
              </div>
              <div class="slide-contents-text">
                店舗の空き時間をシェアしよう<br>
                店舗稼働率をあげて売り上げを向上させよう<br>
              </div>
            </div> <!-- slide-contents -->
          </div> <!-- swiper-slide -->
          <div class="swiper-slide slide02">
            <div class="slide-contents">
              <div class="slide-contents-title">
                店舗もサブスクの時代<br>
              </div>
              <div class="slide-contents-text">
                お気に入りの店舗を見つけてシェアしよう<br>
                店舗を共同で経営し、経済の荒波を打ち破ろう！！<br>
              </div>
            </div> <!-- slide-contents -->
          </div> <!-- swiper-slide -->
          <div class="swiper-slide slide03">
            <div class="slide-contents">
              <div class="slide-contents-title">
                サービス説明<br>
              </div>
              <div class="slide-contents-text">
                このサービスは店舗の未稼働時間を他のユーザーに店舗を貸し出すことで<br>
                店舗稼働率を上げて収益率をあげることを目的としたサービスです。<br>
                あなたの店舗も参加してみませんか？
              </div>
            </div> <!-- slide-contents -->
          </div> <!-- swiper-slide -->
        </div> <!-- swiper-wrapper -->
        <div class="swiper-button">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div> <!-- swiper-button -->
        <div class="swiper-pagination"></div>
      </div> <!-- swiper-container -->
    </section> <!-- swiper -->
 </section>
 <section id="middle">
 <p>募集</p>
 <div class="container-welcome-recruit">
    @foreach($recruits as $recruit)
        <div class="shops-index-container shadow">
            <a href="{{route('recruit.show',['id'=>$recruit->id])}}">
                <div>
                    <div class="text"><div>募集タイトル</div>{{substr($recruit->title,0,20)}}</div>
                    <div class="text"><div>募集内容</div>{{substr($recruit->content,0,20)}}</div>
                    <div class="card-body-font card-grid-set container-position">
                        <div class="text-left">
                            <p class="user-name center">{{ $recruit->user->name }}</p>
                            @if($recruit->user->profile_image_location=='')
                                <img class="user-profile-image center" src="{{ secure_asset('/img/welcom-main-photo/human2.png') }}" alt="">
                            @else
                                <img class="user-profile-image" src="{{Storage::disk('s3')->url($recruit->user->profile_image_location)}}" alt="">
                            @endif
                        </div>
                    </div>
                </div>
            </a> 
        </div>
    @endforeach
 </div>
 <a href="{{route('recruit.index')}}">
    <span>もっと見る</span>
 </a>
 <p>新着店舗</p>
 <div class="container-welcome">
    @foreach($shops_new as $shop)
        <div class="shops-index-container shadow">
           <a href="{{route('shops.show',['shop'=>$shop->id])}}">
               <div>
                    <div class="center"><img src="{{Storage::disk('s3')->url($shop->image_location)}}" class="shops-index-image shadow"></div>
                    <div class="text"><div>ショップ情報</div>{{substr($shop->shop_introduce,0,20)}}</div>
                    <div class="card-body-font card-grid-set container-position">
                        <div class="text-left">
                            <p class="user-name center">{{ $shop->user->name }}</p>
                            @if($shop->user->profile_image_location=='')
                                <img class="user-profile-image center" src="{{ secure_asset('/img/welcom-main-photo/human2.png') }}" alt="">
                            @else
                                <img class="user-profile-image" src="{{Storage::disk('s3')->url($shop->user->profile_image_location)}}" alt="">
                            @endif
                        </div>
                        <div class="line-height-center">   
                           <div class="card-body-font font-bold card-body-font line-height-center">都道府県:</div>
                           <div class="text card-body-font line-height-center">{{$prefecture_array[$shop->shop_location_prefecture]}}</div>
                        </div> 
                        <div class="line-height-center">
                           <div class="card-body-font card-body-font line-height-center">店舗種類:</div>
                           <div class="text card-body-font line-height-center">{{ $shop_type_array[$shop->shop_type]}}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
 </div>
 <a href="{{route('shops.index')}}">
        <span class="text-right">もっと見る</span>
 </a>
 <p>おすすめ店舗</p>
 <div class="container-welcome">
 @foreach($shop_favorite as $shop)
   <div class="shops-index-container shadow">
        <a href="{{route('shops.show',['shop'=>$shop->id])}}">
            <div class="center"><img src="{{Storage::disk('s3')->url($shop->image_location)}}" class="shops-index-image shadow"></div>
            <div class="text"><div>ショップ情報</div>{{substr($shop->shop_introduce,0,20)}}</div>
            <div class="container-position">
                <div class="card-body-font card-grid-set">
                    <div class="text-left">
                        <p class="user-name center">{{ $shop->user->name }}</p>
                        @if($shop->user->profile_image_location=='')
                            <img class="user-profile-image center" src="{{ secure_asset('/img/welcom-main-photo/human2.png') }}" alt="">
                        @else
                            <img class="user-profile-image" src="{{Storage::disk('s3')->url($shop->user->profile_image_location)}}" alt="">
                        @endif
                    </div>
                    <div class="line-height-center">   
                        <div class="font-bold card-body-font line-height-center">都道府県:</div>
                        <div class="text line-height-center">{{$prefecture_array[$shop->shop_location_prefecture]}}</div>
                    </div> 
                    <div class="line-height-center">
                        <div class="font-bold card-body-font line-height-center">店舗種類:</div>
                        <div class="text line-height-center">{{ $shop_type_array[$shop->shop_type]}}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
 @endforeach
 </div>
 <a href="{{route('chat.help_view')}}"><div class="footer-help-botton">ヘルプ</div></a>
 <div class="message-popup">操作方法で困った際は</br>
                            右側のヘルプボタンをクリックしよう！</br>
 </div>
 </section>
 </div>
 <footer>
    <div class="footer-bar">
        <div class=footer-title>あなたの理想の店舗見つけて</div>
        <div class=footer-title> 起業家になろう!!</div>
    </div>
    <div class="footer-bar-bottom">
       <div>仕事のご依頼、お問い合わせemail</div> 
       <div>sugashi1900day@gmail.com</div>
       <div class="center">&copySherSt</div>
    </div>
 </footer>
</div>
   @include('loading')
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="{{ secure_asset('/js/loading.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
    <script type="text/javascript">
      var swiper = new Swiper('.swiper-container',{
      speed: 1000, // スピード設定 1000=1秒
      autoplay: true, // 自動切り替え trueで有効 falseで無
      loop: true, // ループ trueで有効 falseで無効
      navigation: {
        nextEl: '.swiper-button-next', // 次のボタンを表示する要素指定
        prevEl: '.swiper-button-prev' // 前のボタンを表示する要素指定
      },
      pagination: {
        el: '.swiper-pagination', // ページネーションを表示する要素指定
      }
    });
　　</script>      
   </body>
</html>