<?php

include 'islogin.php';
include 'dbinfo.php';

// 데이터베이스 연결
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
$content = isset($_POST['content']) ? mysqli_real_escape_string($conn, $_POST['content']) : '';


// 글 수정
$sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
if (mysqli_query($conn, $sql)) {
    header("Location: detail.php?id=$id"); // 수정 후 detail.php로 리디렉션
}
else {
    echo "글 수정에 실패했습니다: " . mysqli_error($conn);
}

// 데이터베이스 연결 종료
mysqli_close($conn);
?>