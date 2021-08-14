<!DOCTYPE html>
<html lang="en">
<?php
//開啟session，用於存放資訊
session_start();
//將$_SESSION['u_id']清空
$_SESSION['u_id'] = '';

?>

<head>
  <meta charset="utf-8">
  <title>會員系統</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php
  $cssFile = "login.css";
  echo "<link rel='stylesheet' href='" . $cssFile . "'>";
  ?>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</head>


<body>
  <div id="nav">
    <ul>
      <a href="../HiFun/index.html"><img src="../img/LOGO.png" alt="LOGO"></a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
      <a>會員登入</a>
    </ul>


  </div>

  <!-- 這是最上方導覽列 -->
  <form method=POST>
    <br /><br />
    <span style="font-size:22px;">帳號：</span><input type=text name=u_account value="" style=" width: 180px; height: 25px;" /><br /><br />
    <span style="font-size:22px;">密碼：</span><input type=password name=u_password value="" style=" width: 180px; height: 25px;" /><br /><br />
    <label><input type="checkbox" name=remember value="remember">記住帳號</label><br></br>
    <label><input type=submit name=login value="登陸" style=" width: 255px; height: 40px;" /></label><br></br>
    <a href="regist.php">註冊帳號</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
    <a href="regist.php">忘記密碼</a>

  </form>
</body>
<?php
if (isset($_POST["login"])) {
  include('connect.php');
  //將u_password變成密文形式，$_POST['u_password']是接收上面<input name=u_password>的資料，正常密碼不會以明文方式儲存
  $u_password = hash('md5', $_POST['u_password']);
  $sql = "SELECT * FROM users WHERE u_account=:u_account AND u_password=:u_password ";
  $exec = $conn->prepare($sql);
  //將$_POST['u_account']代入至:u_account ; $_POST['u_account']是接收上面<input name=u_account>的資料
  $exec->bindParam(':u_account', $_POST['u_account']);
  //將u_password代入至:u_password ; 
  $exec->bindParam(':u_password', $u_password);
  $exec->execute(); //執行select語法
  $row = $exec->fetch(); //取值存放在$row陣列中
  //判斷$row['u_id']不是空值

  if ($row['u_id'] != '' and $row['u_superuser'] == '1') {
    $_SESSION['u_id'] = $row['u_id'];
    echo '<script>alert("歡迎光臨 小垃圾");location.href="index.html"</script>'; //跳轉回index.html
  } else {
    if ($row['u_superuser'] == '0') {
      //將$row['u_id']存放在$_SESSION['u_id']
      $_SESSION['u_id'] = $row['u_id'];
      header('Location:member.php');  //跳轉回member.php 
    } else {
      echo '帳號密碼錯誤，或查詢無此帳號。';
    }
    $conn = null;
  }
}

?>

</html>