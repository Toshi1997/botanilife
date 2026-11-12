@extends('layouts.app')

@section('title', 'BotaniLife - 植物の新規登録')

@section('content')
<div class="container container-narrow">
    <h1 class="page-title">🌱 植物の新規登録</h1>
    <p class="page-subtitle">新しく育てる植物の情報を入力してください。</p>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('plants.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">植物の名前 <span class="required-mark">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="例: モンステラ、バジル など" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="nickname" class="form-label">ニックネーム（愛称）</label>
            <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}" placeholder="例: モンちゃん、バジル先輩 など" class="form-input">
        </div>

        <div class="form-group">
            <label for="location" class="form-label">置き場所</label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="例: リビング窓際、ベランダ など" class="form-input">
        </div>

        <button type="submit" class="btn-submit">登録する</button>
    </form>

    <a href="{{ route('plants.index') }}" class="back-link">← 一覧画面に戻る</a>
</div>
@endsection