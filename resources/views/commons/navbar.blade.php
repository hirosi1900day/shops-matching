<header >
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        {{-- トップページへのリンク --}}
        <a class="navbar-brand" href="/">Microposts</a>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
              <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                @if (Auth::check())
                    {{-- ユーザ一覧ページへのリンク --}}
                    <li class="nav-item"><a href="#" class="nav-link">Users</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            {{-- ユーザ詳細ページへのリンク --}}
                     　      <li class="dropdown-item">{!! link_to_route('users.show', 'MyPage', ['user' => Auth::id()]) !!}</li>
                          　 {{-- 店舗作成へのリンク--}}
                            <li class="dropdown-item">{!! link_to_route('shops.create', 'ショップ作成',) !!}</li>
                            <li class="dropdown-item">{!! link_to_route('mypage.shop', 'Myshop',) !!}</li>
　　　　　　　          　　<li class="dropdown-item">{!! link_to_route('shops.index', 'ショップ一覧',) !!}</li>
　　　　　　　          　　<li class="dropdown-item">{!! link_to_route('favorites.index', 'お気に入り一覧',) !!}</li>
　　　　　　　          　　<li class="dropdown-item">{!! link_to_route('chat.user_index', '取引メッセージ',) !!}</li>
　　　　　　　              <li class="dropdown-item">{!! link_to_route('recruit.create', '募集作成',) !!}</li>
　　　　　　　              <li class="dropdown-item">{!! link_to_route('recruit.show', 'Myrecruit',) !!}</li>
　　　　　　　              <li class="dropdown-item">{!! link_to_route('recruit.index', '募集一蘭',) !!}</li>
                            <li class="dropdown-divider"></li>
                            {{-- ログアウトへのリンク --}}
                            <li class="dropdown-item">{!! link_to_route('logout.get', 'Logout') !!}</li>
                        </ul>
                    </li>
                @else
                    {{-- ユーザ登録ページへのリンク --}}
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    {{-- ログインページへのリンク --}}
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
                @endif
            </ul>
        </div>
     </nav>
</header>