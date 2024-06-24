<?php
include 'islogin.php';
include 'dbinfo.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_comment']) && !empty($_POST['post_id']) && !empty($_POST['comment_id'])) {
        $comment_id = (int)$_POST['comment_id'];
        $post_id = (int)$_POST['post_id'];

        // 데이터베이스 연결
        $conn = mysqli_connect($host, $user, $pw, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // 댓글 작성자 확인
        $sql = "SELECT user FROM comments WHERE id = $comment_id";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("쿼리 실패: " . mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        $comment_author = $row['user'];

        // 로그인된 사용자 확인
        $username = $_SESSION['username'];

        // 디버깅 출력
        //echo "글쓴이: $comment_author<br>";
        //echo "로그인 유저: $username<br>";

        if ($comment_author === $username) {
            $comment_content = mysqli_real_escape_string($conn, $_POST['edit_comment']);
            $sql = "UPDATE comments SET content = '$comment_content' WHERE id = $comment_id";

            if (mysqli_query($conn, $sql)) {
                // 댓글이 성공적으로 수정되었으면 상세 페이지로 이동
                header("Location: detail.php?id=$post_id");
                exit();
            } else {
                // 오류가 발생하면 오류 메시지를 출력
                echo "댓글 수정에 실패했습니다: " . mysqli_error($conn);
            }
        } else {
            echo "수정 권한이 없습니다.";
        }

        mysqli_close($conn);
    } else {
        echo "잘못된 요청입니다.";
    }
} else {
    echo "잘못된 요청입니다.";
}
?>