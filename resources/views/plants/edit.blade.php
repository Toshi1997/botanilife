@extends('layouts.app')

@section('title', 'BotaniLife - 植物情報の編集')

@section('content')
<div class="container container-narrow">
    <h1 class="page-title">🌱 植物情報の編集</h1>
    <p class="page-subtitle">登録情報を変更して更新してください。</p>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 更新処理は PUT メソッドを使うため @method('PUT') を挟みます -->
    <form action="{{ route('plants.update', $plant) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">植物の名前 <span class="required-mark">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $plant->name) }}" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="nickname" class="form-label">ニックネーム（愛称）</label>
            <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $plant->nickname) }}" class="form-input">
        </div>

        <div class="form-group">
            <label for="location" class="form-label">置き場所</label>
            <input type="text" id="location" name="location" value="{{ old('location', $plant->location) }}" class="form-input">
        </div>

        <button type="submit" class="btn-submit">更新する</button>
    </form>

    <a href="{{ route('plants.show', $plant) }}" class="back-link">← 詳細画面に戻る</a>
</div>
@endsection