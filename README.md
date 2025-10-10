<?php
// ホーム画面（index.php）
// ログイン前・ログイン後どちらにも対応可能なトップページ

session_start();
$isLoggedIn = isset($_SESSION['user_name']); // ログインしているか確認
$userName = $isLoggedIn ? $_SESSION['user_name'] : "ゲスト";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケートアプリ - ホーム</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>アンケート＆ポイントアプリ</h1>
    <nav>
        <ul>
            <li><a href="index.php">ホーム</a></li>
            <li><a href="survey.php">アンケート</a></li>
            <li><a href="points.php">ポイント履歴</a></li>
            <li><a href="reward.php">景品交換</a></li>
            <li><a href="opinion.php">意見箱</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="logout.php">ログアウト</a></li>
            <?php else: ?>
                <li><a href="login.php">ログイン</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <section class="welcome">
        <h2>ようこそ、<?php echo htmlspecialchars($userName); ?>さん！</h2>
        <p>このアプリでは、アンケートに回答するとポイントがもらえます。<br>
