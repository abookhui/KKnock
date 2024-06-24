<?php
include 'islogin.php';
include 'dbinfo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $username = $_SESSION['username'];

    if ($comment_id == 0 || $post_id == 0) {
        echo "잘못된 접근입니다.";
        exit();
    }


    $conn = mysqli_connect($host, $user, $pw, $db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 댓글 작성자 확인
    $check_user_sql = "SELECT user FROM comments WHERE id = $comment_id";
    $result = mysqli_query($conn, $check_user_sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['user'] != $username) {
        echo "본인만 댓글을 삭제할 수 있습니다.";
        mysqli_close($conn);
        exit();
    }

    // 답글 삭제
    $delete_replies_sql = "DELETE FROM comments WHERE parent_id = $comment_id";
    if (!mysqli_query($conn, $delete_replies_sql)) {
        echo "답글 삭제에 실패했습니다: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    // 댓글 삭제
    $delete_comment_sql = "DELETE FROM comments WHERE id = $comment_id";
    if (!mysqli_query($conn, $delete_comment_sql)) {
        echo "댓글 삭제에 실패했습니다: " . mysqli_error($conn);
    } else {
        echo ""; // 성공 시 빈 응답
    }

    // 데이터베이스 연결 종료
    mysqli_close($conn);
}
?>