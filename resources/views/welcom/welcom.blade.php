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
    

   
   
    @include('commons.navbar')
    @include('loading')
<div class="background-skyblue">
  <section>
        
        
     <section class="swiper">
      <div class="swiper-container">
        <div class="swiper-wrapper">

          <div class="swiper-slide slide01">
            <div class="slide-contents">
              <div class="slide-contents-title">
                あなただけの1曲を。<br>
              </div>
              <div class="slide-contents-text">
                世界中の楽曲をチェック。<br>
                あの音楽が見つかる。<br>
              </div>
            </div> <!-- slide-contents -->
          </div> <!-- swiper-slide -->

          <div class="swiper-slide slide02">
            <div class="slide-contents">
              <div class="slide-contents-title">
                音楽を共有しよう。<br>
              </div>
              <div class="slide-contents-text">
                気に入った楽曲をSNSで簡単にシェアできます。<br>
                シェアを通して新しい音楽と出会おう。<br>
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
      
 <p>新着店舗</p>
 <div class="container-welcome">
 @foreach($shops_new as $shop)
     
        <div class="shops-index-container shadow">
           <a href="{{route('shops.show',['shop'=>$shop->id])}}">
            <div>
                <div class="center"><img src="{{Storage::disk('s3')->url($shop->image_location)}}" class="shops-index-image shadow"></div>
                <div class="card-body-font card-grid-set">
                    <div class="text-left">
                        <p class="user-name center">{{ $shop->user->name }}</p>
                        @if($shop->user->profile_image_location=='')
                            <img class="user-profile-image" src="{{ Gravatar::get($shop->user->email) }}" alt="">
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
                   
           </a>
        </div>

    </div>
     
 @endforeach
    </div>
 <p>おすすめ店舗</p>
 <div class="container-welcome">
 @foreach($shop_favorite as $shop)
   <div class="shops-index-container shadow">
           <a href="{{route('shops.show',['shop'=>$shop->id])}}">
               <div>
                <div class="center"><img src="{{Storage::disk('s3')->url($shop->image_location)}}" class="shops-index-image shadow"></div>
                <div class="card-body-font card-grid-set">
                    <div class="text-left">
                        <p class="user-name center">{{ $shop->user->name }}</p>
                        @if($shop->user->profile_image_location=='')
                            <img class="user-profile-image" src="{{ Gravatar::get($shop->user->email) }}" alt="">
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
           </a>
        </div>
    </div>
 @endforeach
 </div>
 </section>
 </div>
        
        

    
 
       
 
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script src="{{ secure_asset('/js/loading.js') }}"></script>
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