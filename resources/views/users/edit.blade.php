@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4 card-main">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                     @if($user->profile_image_location=='')
                        <img class="user-profile-image center" src="{{ secure_asset('/img/welcom-main-photo/human2.png') }}" alt="">
                    @else
                        <img class="user-profile-image" src="{{Storage::disk('s3')->url($user->profile_image_location)}}" alt="">
                    @endif
                </div>
            </div>
        </aside>
        <div class="col-sm-8 background-skyblue">
                {!! Form::model($user, ['route' => ['users.update','user'=>$user->id],'enctype'=>'multipart/form-data','method'=>'put']) !!}
                <div class="form-group">
                    {!! Form::label('profile_image_location', 'プロフィール写真を変更する（200KBまで可能）:') !!}
                    {!! Form::file('profile_image_location', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('name', 'name:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('self_introduce', 'introduce:') !!}
                    {!! Form::textarea('self_introduce', null, ['class' => 'form-control']) !!}
                </div>
                {!! Form::submit('更新', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
        </div>
    </div>
@endsection