@extends('layouts.app')

@section('title', 'BotaniLife - 植物詳細')

@section('content')
<div class="container" style="max-width: 800px;">
    <!-- 植物の基本情報 -->
    <div class="plant-detail-header-section">
        <h1 class="page-title">🌱 植物の詳細</h1>
        
        <div class="plant-info-card">
            <h2 class="plant-name-heading">{{ $plant->name }}</h2>
            <p class="plant-info-text"><strong>愛称（ニックネーム）：</strong> {{ $plant->nickname ?? 'なし' }}</p>
            <p class="plant-info-text"><strong>置き場所：</strong> {{ $plant->location ?? '未設定' }}</p>
            <p class="plant-meta-date">登録日時：{{ $plant->created_at->format('Y年m月d日 H:i') }}</p>
        </div>

        <!-- 編集・削除・記録追加ボタンの並び -->
        <div class="plant-action-bar">
            <a href="{{ route('plants.edit', $plant) }}" class="btn-primary-inline">
                ✏️ この情報を編集する
            </a>
            
            <!-- ポップアップを開くボタン -->
            <button type="button" id="openModalBtn" class="btn-record-open">
                📝 作業を記録する
            </button>

            <form action="{{ route('plants.destroy', $plant) }}" method="POST" onsubmit="return confirm('本当にこの植物を削除しますか？');" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger-inline">
                    🗑️ 削除
                </button>
            </form>
        </div>
    </div>

    <hr class="section-divider">

    <!-- これまでの記録（振り返り専用エリア） -->
    <div>
        <h3 class="section-subtitle">📖 これまでの記録</h3>

        <div class="log-list-container">
            @forelse($plant->careLogs as $log)
                <div class="log-card">
                    <div class="log-card-header">
                        <span class="log-type-badge">
                            {{ $log->care_type->label() }}
                        </span>
                        <span class="log-date-text">
                            {{ $log->logged_at->format('Y年m月d日 H:i') }}
                        </span>
                    </div>

                    @if($log->image_path)
                        <div class="log-image-wrapper">
                            <img src="{{ asset('storage/' . $log->image_path) }}" alt="作業の写真" class="log-thumbnail">
                        </div>
                    @endif

                    @if($log->memo)
                        <p class="log-memo-text">{{ $log->memo }}</p>
                    @endif
                </div>
            @empty
                <p class="empty-log-message">
                    まだ作業記録がありません。「作業を記録する」ボタンから最初の記録を残してみましょう！
                </p>
            @endforelse
        </div>
    </div>

    <div class="back-link-container">
        <a href="{{ route('plants.index') }}" class="back-link-anchor">← 一覧画面に戻る</a>
    </div>
</div>

<!-- ================= 記録用ポップアップ（モーダル） ================= -->
<div id="recordModal" class="modal-overlay">
    <div class="modal-content">
        <button type="button" id="closeModalBtn" class="modal-close">&times;</button>
        
        <h3 class="modal-title">新しい作業を記録する</h3>

        <form action="{{ route('care-logs.store', $plant) }}" method="POST" enctype="multipart/form-data">
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
    const modal = document.getElementById('recordModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
@endsection