<?php
include 'islogin.php';
include 'dbinfo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $post_id = (int)$_POST['id'];
    $username = $_SESSION['username'];

    // 데이터베이스 연결
    $conn = mysqli_connect($host, $user, $pw, $db);
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // 게시물 작성자 확인
    $sql = "SELECT user FROM posts WHERE id = $post_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo '쿼리 실행 오류: ' . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    $post = mysqli_fetch_assoc($result);

    if ($post) {
        if ($post['user'] == $username) {
            // 게시물에 대한 댓글 삭제
            $delete_comments_sql = "DELETE FROM comments WHERE post_id = $post_id";
            if (mysqli_query($conn, $delete_comments_sql)) {
                // 댓글이 모두 삭제된 후 게시물 삭제
                $delete_post_sql = "DELETE FROM posts WHERE id = $post_id";
                if (mysqli_query($conn, $delete_post_sql)) {
                    header('Location: list.php');
                    exit();
                } else {
                    echo '게시글 삭제 실패: ' . mysqli_error($conn);
                }
            } else {
                echo '댓글 삭제 실패: ' . mysqli_error($conn);
            }
        } else {
            echo "권한 없음. 로그인 유저: $username, 작성자: " . $post['user'];
        }
    } else {
        echo '게시글을 찾을 수 없습니다. id: ' . $post_id;
    }

    mysqli_close($conn);
}
?>