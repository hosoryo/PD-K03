<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user_id'];
$db = new PDO('sqlite:'.__DIR__.'/db/data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ユーザー情報
$stmt = $db->prepare("SELECT id,display_name,points FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// アンケート履歴
$stmt = $db->prepare("SELECT a.*, s.title FROM survey_answers a LEFT JOIN surveys s ON a.survey_id = s.id WHERE a.user_id = ? ORDER BY a.answered_at DESC");
$stmt->execute([$userId]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 最近のお知らせ（簡易）
$notifications = [
    ['msg'=>'新しいアンケートが追加されました', 'date'=>'2025-10-20']
];
?>
<!doctype html>
<html lang="ja">
<head><meta charset="utf-8"><title>マイページ</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>マイページ</h1><nav><a href="index.php">ホーム</a> | <a href="logout.php">ログアウト</a></nav></header>
<main>
  <section>
    <h2><?php echo htmlspecialchars($user['display_name']); ?> さん</h2>
    <p>保有ポイント: <strong><?php echo (int)$user['points']; ?> pt</strong></p>
  </section>

  <section>
    <h3>アンケート回答履歴</h3>
    <?php if (empty($answers)): ?>
      <p>まだ回答がありません</p>
    <?php else: ?>
      <ul>
      <?php foreach($answers as $a): ?>
        <li><?php echo htmlspecialchars($a['title'] ?: '（未設定）'); ?> — 回答日: <?php echo $a['answered_at']; ?> — 獲得: <?php echo (int)$a['awarded_points']; ?>pt</li>
      <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <section>
    <h3>お知らせ</h3>
    <ul>
      <?php foreach($notifications as $n): ?>
        <li><?php echo $n['date']; ?> - <?php echo htmlspecialchars($n['msg']); ?></li>
      <?php endforeach; ?>
    </ul>
  </section>
</main>
<footer>© 2025</footer>
</body>
</html>
