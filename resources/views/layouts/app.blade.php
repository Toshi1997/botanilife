<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BotaniLife')</title>
    
    <!-- 共通の外部CSS読み込み -->
    @vite(['resources/css/style.css']) <!-- もし実際のファイル名が app.css なら app.css に合わせてください -->
</head>
<body>
    <!-- 共通の囲いをなくし、各ページのコンテンツをそのまま流し込む -->
    @yield('content')
</body>
</html>