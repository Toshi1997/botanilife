@extends('layouts.app')

@section('title', 'BotaniLife - 植物一覧')

@section('content')
<div class="container container-wide">
    <!-- ヘッダー部分 -->
    <div class="page-header">
        <div>
            <h1 class="page-title">植物一覧</h1>
            <p class="page-subtitle">我が家の植物たちの一覧です。</p>
        </div>
        <!-- ヘッダー部分の天気表示 -->
        <div class="header-right">
            <span class="weather-info">天気：{{ $weatherText }}</span>
            <a href="{{ route('plants.create') }}" class="btn-primary">+ 植物追加</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- フィルタータブ（動的絞り込み） -->
    <div class="filter-tabs">
        <a href="{{ route('plants.index') }}" class="filter-tab {{ is_null($selectedLocation) ? 'active' : 'inactive' }}">
            すべて
        </a>

        @foreach($locations as $loc)
            <a href="{{ route('plants.index', ['location' => $loc]) }}" class="filter-tab {{ $selectedLocation === $loc ? 'active' : 'inactive' }}">
                {{ $loc }}
            </a>
        @endforeach
    </div>

    <!-- グリッド一覧 -->
    <div class="plant-grid">
        @forelse($plants as $plant)
            @php
                $latestPhotoLog = $plant->careLogs->whereNotNull('image_path')->first();
                $latestWaterLog = $plant->careLogs->where('care_type', \App\Enums\CareType::Watering->value)->first();
            @endphp

            <div class="plant-card">
                <!-- 右上のクイック水やりボタン -->
                <form action="{{ route('plants.quick-water', $plant) }}" method="POST" class="quick-water-form">
                    @csrf
                    <button type="submit" title="ワンクリック水やり" class="quick-water-btn">
                        💧
                    </button>
                </form>

                <!-- 写真エリア -->
                <div class="plant-card__image">
                    @if($latestPhotoLog)
                        <img src="{{ asset('storage/' . $latestPhotoLog->image_path) }}" alt="{{ $plant->name }}">
                    @else
                        <span class="no-photo-text">写真</span>
                    @endif
                </div>

                <!-- 情報エリア -->
                <div class="plant-card__body">
                    <div class="plant-card__name">{{ $plant->name }}</div>
                    <div class="plant-card__nickname">{{ $plant->nickname ?? 'ニックネームなし' }}</div>
                    <div class="plant-card__water-status">
                        最終水やり: 
                        @if($latestWaterLog)
                            {{ $latestWaterLog->logged_at->diffForHumans() }}
                        @else
                            記録なし
                        @endif
                    </div>

                    <!-- ボタン群 -->
                    <div class="plant-card__actions">
                        <a href="{{ route('plants.show', $plant) }}" class="btn-outline">詳細</a>
                        
                        <!-- 記録ボタン（ポップアップを開くトリガー） -->
                        <button type="button" class="btn-solid open-record-modal" 
                            data-store-url="{{ route('care-logs.store', $plant) }}" 
                            data-plant-name="{{ $plant->name }}">
                            記録
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="empty-grid-message">
                登録されている植物がまだありません。「植物追加」から登録してみましょう！ 🌱
            </p>
        @endforelse
    </div>
</div>

<!-- ================= 共通の作業記録ポップアップ（モーダル） ================= -->
<div id="indexRecordModal" class="modal-overlay">
    <div class="modal-content">
        <button type="button" id="indexCloseModalBtn" class="modal-close">&times;</button>
        
        <h3 id="modalPlantTitle" class="modal-title">作業を記録する</h3>

        <form id="indexRecordForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 作業の種類 -->
            <div class="form-group">
                <label class="form-label">作業の種類</label>
                <select name="care_type" class="form-select">
                    @foreach(\App\Enums\CareType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 作業日時 -->
            <div class="form-group">
                <label class="form-label">作業日時</label>
                <input type="datetime-local" name="logged_at" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-input">
            </div>

            <!-- 写真（任意） -->
            <div class="form-group">
                <label class="form-label">写真（任意）</label>
                <input type="file" name="image" accept="image/*" class="form-file">
                <span class="form-hint">※JPEG, PNG形式（2MBまで）</span>
            </div>

            <!-- メモ・気づき -->
            <div class="form-group">
                <label class="form-label">メモ・気づき</label>
                <textarea name="memo" rows="3" placeholder="今日の様子や気づいたことを入力..." class="form-textarea"></textarea>
            </div>

            <button type="submit" class="btn-submit">
                ログを記録する
            </button>
        </form>
    </div>
</div>

<!-- モーダル制御用のJavaScript -->
<script>
    const indexModal = document.getElementById('indexRecordModal');
    const indexCloseBtn = document.getElementById('indexCloseModalBtn');
    const indexForm = document.getElementById('indexRecordForm');
    const modalPlantTitle = document.getElementById('modalPlantTitle');

    document.querySelectorAll('.open-record-modal').forEach(button => {
        button.addEventListener('click', () => {
            const storeUrl = button.getAttribute('data-store-url');
            const plantName = button.getAttribute('data-plant-name');

            indexForm.action = storeUrl;
            modalPlantTitle.textContent = `${plantName} の作業を記録する`;
            indexModal.style.display = 'flex';
        });
    });

    indexCloseBtn.addEventListener('click', () => {
        indexModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === indexModal) {
            indexModal.style.display = 'none';
        }
    });
</script>
@endsection