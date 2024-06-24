<?php

session_start(); // session 시작.

include("dbinfo.php");

$post_id = $_POST['id'];
$post_pw = $_POST['password'];

// DB 연결
$conn = mysqli_connect($host, $user, $pw, $db);

if (!$conn) {
    //die("연결 실패: $host" . mysqli_connect_error());
    echo $host;
}
// SQL 인젝션을 방지, 입력 값을 이스케이프 특수 문자 \로 이스케이프 처리
$post_id = mysqli_real_escape_string($conn, $post_id);
$post_pw = mysqli_real_escape_string($conn, $post_pw);


$sql = "SELECT * FROM users WHERE username = '$post_id' AND password = '$post_pw'";

$ans = mysqli_query($conn, $sql);

if ($ans) {
    if (mysqli_num_rows($ans) > 0) {
        //echo "로그인 성공";
                $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $post_id;


                header("Location: mypage.php");
                exit();

    } else {
        // echo "로그인 실패: 잘못된 사용자 이름 또는 비밀번호";

                //header("Location: index.php");
                echo "<script>
                                alert('로그인 실패: 잘못된 사용자 이름 또는 비밀번호');
                                history.back();
                        </script>";
                exit;

        }
} else {
    echo "쿼리 실패: " . mysqli_error($conn); // error 메세지 반환
}

mysqli_close($conn);


?>