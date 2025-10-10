const express = require("express");
const bodyParser = require("body-parser");
const app = express();

app.set("view engine", "ejs");
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static("public"));

// ログインページ表示
app.get("/", (req, res) => {
  res.render("login");
});

// ログイン処理
app.post("/login", (req, res) => {
  const { username, password } = req.body;

  // 仮の認証（実際はデータベースで確認する）
  if (username === "user" && password === "pass123") {
    res.render("success", { user: username });
  } else {
    res.send("ログイン失敗：ユーザー名またはパスワードが違います");
  }
});

app.listen(3000, () => {
  console.log("サーバー起動中: http://localhost:3000");
});
