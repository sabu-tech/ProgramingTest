<!DOCTYPE HTML>
<html lang="ja">
  <head>
      <meta charset="UTF-8">
      <title>mission5-1</title>
  </head>
  <body>
    <!-- 投稿用フォーム -->
    <form action="" method="post">
      名前：<input type="text"  name="name"  value="名前">
      コメント：<input type="text" name="comment" value="コメント">
      <!-- 投稿パスワード：<input type="text" name="password1"> -->
      <input type="submit" name="submit" value="投稿">
    </form>
    <!-- 削除フォーム -->
    <form action="" method="post">
      削除：<input type="text" name="deleteNu">
      <input type="submit" name="deleteButton" value="削除">
    </form>
    <!-- 編集フォーム -->
    <form action="" method="post">
      編集：<input type="text" name="edit">
      編集したい名前：<input type="text" name="editName" >
      編集したいコメント：<input type="text" name="editComment">
      <input type="submit" name="editButton" value="編集">
    </form>
  </body>
  <?php
    $dsn='mysql:dbname=*********;host=localhost';
    $user = '*********';
    $password = '**********';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS tbchat"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";
    $stmt = $pdo->query($sql);
    $sql = $pdo -> prepare("INSERT INTO tbchat (name, comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    if(isset($_POST["submit"])){
      $name = $_POST["name"];//投稿した名前を変数化
      $comment = $_POST["comment"]; //投稿したコメントを変数化
      $sql -> execute();
    }
    if(isset($_POST["deleteButton"])){//削除機能
      $id = $_POST["deleteNu"];//削除番号を変数化
      $sql = 'delete from tbchat where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }
    //編集ボタンが押された時
    if(isset($_POST["editButton"])){
      //いっぺんに変更したい
      if($_POST["editName"] !="" || $comment = $_POST["editComment"] !=""){//変更したい名前とコメントが空でない時
        $id = $_POST["edit"]; //変更する投稿番号
        $name = $_POST["editName"];//変更したい名前
        $comment = $_POST["editComment"]; //変更したいコメント
        $sql = 'UPDATE tbchat SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
      }
      // 一部編集したい時
      // elseif($_POST["editName"] =="" || $comment = $_POST["editComment"] ==""){//変更したい名前とコメントが空の時
      //   //編集番号の同じidにある名前とコメントを編集フォームに入力
      //   $sql = 'UPDATE  tbchat SET $editname=:name,$editcomment=:comment WHERE id=:id';
      // }
    }
    $sql = 'SELECT * FROM tbchat';//入力したでデータコードを表示する
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      //それぞれを出力
      echo $row['id'].',';
      echo $row['name'].',';
      echo $row['comment'].'<br>';
      echo "<hr>";
    }
    ?>
</html>