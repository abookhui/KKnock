<?php

session_start();

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] === true) {
        echo '이미 로그인 되어있습니다. 잘못된 접근.';
        exit;
}

include("dbinfo.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
        $chk = $_POST['chk'];
        $gender = $_POST['gender'];
        $nickname = $_POST['nickname'];
        if ($nickname === ''){
                $nickname = $username;
        }
        if($username===''||$password===''||$chk===''||$gender==''){
                echo "<script>alert('입력이 안되었습니다.'); window.location.href = 'register.php'; </script>;";
                exit();

        }
        if($password !== $chk){
                echo "<script>alert('비밀번호가 다름니다.'); window.location.href = 'register.php'; </script>";
                exit();
        }

    $conn = mysqli_connect($host, $user, $pw, $db);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 아이디 중복 확인
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('이미 존재하는 아이디입니다.'); window.location.href='register.php';</script>";
        mysqli_close($conn);
        exit();
    }

    // 중복이 없으면 새로운 사용자 등록
    $sql = "INSERT INTO users (username, password, gender,nickname) VALUES ('$username', '$password', '$gender','$nickname')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('가입 완료 !'); window.location.href='login.php';</script>";
    }

    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">
</head>
  <body>
     <?php include 'nav.php';?>

    <div class = "login-container">
        <div class="login-md">
            <h1>회원가입</h1>
            <form action="" method="POST">
                <input type="username" class="form-control" name="username" id="login-ID" placeholder="ID">
                <input type="password" class="form-control" name="password"id="login-pw" placeholder="PW">
                                <input type="password" class="form-control" name="chk" id="login-pw" placeholder="확인">
                                <input type="text" class="form-control" name="nickname" id="login-pw" placeholder="닉네 임">


                                <div class="gender">
                    <input type="radio" class="btn-check" name="gender" id = 'male' value = "male" checked>
                    <label class="btn" for="male">남자</label>

                    <input type="radio" class="btn-check" name="gender" id = 'female'  value= 'female'>
                    <label class="btn" for="female">여자</label>
                </div>

                <button type="submit" class="log-btn btn btn-secondary btn-lg">가입</button>
            </form>
        </div>

    </div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>