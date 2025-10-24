<?php
// ※このファイルの先頭に BOM や空白を置かないこと
declare(strict_types=1);

// デバッグ（必要に応じてON）
ini_set('display_errors','1');
error_reporting(E_ALL);

// 文字コードとタイムゾーン
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Tokyo');

// POST 以外は拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Method Not Allowed';
  exit;
}

// 入力取得 & 簡易バリデーション
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $message === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo '入力に不備があります。';
  exit;
}

try {
  // DBに接続（プロジェクト直下の opinions.db）
  $dbPath = dirname(__DIR__) . '/opinions.db';
  $pdo = new PDO('sqlite:' . $dbPath);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // テーブルが無ければ作成
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS opinions (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT,
      email TEXT,
      message TEXT,
      created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )
  ");

  // 保存
  $stmt = $pdo->prepare("INSERT INTO opinions (name, email, message) VALUES (?, ?, ?)");
  $stmt->execute([$name, $email, $message]);

  // 完了したら thanks.html へ 303 リダイレクト（再送防止）
  header('Location: thanks.html', true, 303);
  exit;

} catch (Throwable $e) {
  http_response_code(500);
  echo 'DB Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  exit;
}

