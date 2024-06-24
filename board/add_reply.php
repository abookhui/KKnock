<?php
include 'islogin.php';
include 'dbinfo.php';

// 데이터베이스 연결
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// POST 데이터 받기
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
$comment_user = $_SESSION['username'];
$comment_content = mysqli_real_escape_string($conn, $_POST['comment']);

if ($post_id == 0 || $parent_id == 0) {
    echo "잘못된 접근입니다.";
    exit();
}

// 댓글 작성 처리
$sql = "INSERT INTO comments (post_id, user, content, parent_id) VALUES ($post_id, '$comment_user', '$comment_content', $parent_id)";
if (!mysqli_query($conn, $sql)) {
    echo "답글 작성에 실패했습니다: " . mysqli_error($conn);
} else {
    header("Location: detail.php?id=$post_id"); // 답글 작성 후 새로고침,  중복작성 xx
    exit();
}

// 데이터베이스 연결 종료
mysqli_close($conn);
?>