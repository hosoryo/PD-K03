const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const app = express();

app.set("view engine", "ejs");
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static("public"));

// ユーザーデータ保存ファイル
const USERS_FILE = "users.json";

// ファイル初期化（存在しない場合は作成）
if (!fs.existsSync(USERS_FILE)) {
  fs.writeFileSync(USERS_FILE, JSON.stringify([]));
}

// ホーム → ログイン画面
app.get("/", (req, res) => {
  res.render("login");
});

// ログイン処理
app.post("/login", (req, res) => {
  const { username, password } = req.body;
  const users = JSON.parse(fs.readFileSync(USERS_FILE));

  const user = users.find((u) => u.username === username && u.password === password);
  if (user) {
    res.render("success", { user: username });
  } else {
    res.send("ログイン失敗：ユーザー名またはパスワードが違います。<br><a href='/'>戻る</a>");
  }
});

// 新規登録画面
app.get("/register", (req, res) => {
  res.render("register");
});

// 新規登録処理
app.post("/register", (req, res) => {
  const { username, password } = req.body;
  const users = JSON.parse(fs.readFileSync(USERS_FILE));

  // 重複チェック
  if (users.find((u) => u.username === username)) {
    return res.send("このユーザー名はすでに使われています。<br><a href='/register'>戻る</a>");
  }

  users.push({ username, password });
  fs.writeFileSync(USERS_FILE, JSON.stringify(users, null, 2));
  res.send("登録完了！<br><a href='/'>ログイン画面へ戻る</a>");
});

// パスワード再設定画面
app.get("/forgot", (req, res) => {
  res.render("forgot");
});

// パスワード再設定処理
app.post("/forgot", (req, res) => {
  const { username, newpassword } = req.body;
  const users = JSON.parse(fs.readFileSync(USERS_FILE));

  const user = users.find((u) => u.username === username);
  if (!user) {
    return res.send("そのユーザー名は登録されていません。<br><a href='/forgot'>戻る</a>");
  }

  user.password = newpassword;
  fs.writeFileSync(USERS_FILE, JSON.stringify(users, null, 2));
  res.send("パスワードを更新しました！<br><a href='/'>ログイン画面へ戻る</a>");
});

// サーバー起動
app.listen(3000, () => {
  console.log("サーバー起動中: http://localhost:3000");
});
