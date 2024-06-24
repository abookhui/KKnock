<?php
include 'islogin.php';
include 'dbinfo.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id == 0) {
    echo "잘못된 접근입니다.";
    exit();
}

$username = $_SESSION['username'];

// 데이터베이스 연결
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL 쿼리 실행
$sql = "SELECT id, title, user, content, file_path, created_time,list_type FROM posts WHERE id = $id";
$ans = mysqli_query($conn, $sql);

if (!$ans) {
    die("Query failed: " . mysqli_error($conn));
}

$post = mysqli_fetch_assoc($ans);

if (!$post) {
    echo "게시글을 찾을 수 없습니다.";
    exit();
}

// 댓글 작성 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment_user = $_SESSION['username'];
    $comment_content = mysqli_real_escape_string($conn, $_POST['comment']);
    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    $sql = "INSERT INTO comments (post_id, user, content, parent_id) VALUES ($id, '$comment_user', '$comment_content', " . ($parent_id ? $parent_id : "NULL") . ")";
    if (!mysqli_query($conn, $sql)) {
        echo "댓글 작성에 실패했습니다: " . mysqli_error($conn);
    } else {
        header("Location: detail.php?id=$id"); // 댓글 작성 후 새로고침하여 중복 작성 방지
        exit();
    }
}

// 댓글 목록 가져오기
$comments_sql = "SELECT id, post_id, user, content, created_time FROM comments WHERE post_id = $id AND parent_id IS NULL ORDER BY created_time DESC";
$comments_result = mysqli_query($conn, $comments_sql);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>

        <style>
                .section { margin-top:60px;}
                .content-bottom{margin-top:100px;}
                .content-top{margin-bottom:40px;}
                .download-file{width:140px;}
                .content{
    border:1px solid black;
    border-radius:10px;
    max-width:1000px;
    padding:20px;
    min-height: 400px;
}
        </style>

</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <div class="side-nav">
        <div class="detail-profile">
            <img src="cat.jpg" class="profile-img" alt="">
            <p><?php echo htmlspecialchars($username); ?></p>
        </div>

        <?php if ($post['user'] == $username): ?>
        <div class="modfiy-box">
            <form action="delete_post.php" class="modify" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit"> 🗑️ </button>
            </form>
            <form action="edit.php?id=<?php echo $id ?>" class="modify" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit"> ✏️ </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
                <div class = 'content-top'>
        <span>작성자: <?php echo htmlspecialchars($post['user']); ?></span>
                <span>(<?php echo $post['created_time']; ?>)</span>
                </div>
                <h1>제목: <?php echo htmlspecialchars($post['title']); ?></h1>
        <br>
        <div class="content">
            <p><?php echo htmlspecialchars($post['content']); ?></p>

        </div>


                <div class ="content-bottom">
                <?php if ($post['file_path']): ?>
                <p class = 'download-file'><a href="<?php echo $post['file_path']; ?>" download>첨부 파일 다운로드</a></p>
            <?php endif; ?>

                        <a href="list.php?list_type=<?=$post['list_type'];?>" class="btn btn-primary">목록으로 돌아가기</a>
                </div>
    </div>

    <div class="comment-section">
        <h4>댓글</h4>
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
            <div class="comment">
                <p><strong>
                    <?php echo htmlspecialchars($comment['user']); ?>
                </strong>
                (<?php echo $comment['created_time']; ?>)

                <svg focusable="false" class="set-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="<?php echo htmlspecialchars($comment['id']); ?>">
                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                </svg>

                <span class="set-box set-box-<?php echo htmlspecialchars($comment['id']); ?>">
                    <span class="set-btn revise-btn" data-id="<?php echo htmlspecialchars($comment['id']); ?>"
                                        data-author="<?php echo htmlspecialchars($comment['user']); ?>">수정</span> | <span class="set-btn delete-btn" data-id="<?php echo htmlspecialchars($comment['id']); ?>">삭제</span>
                </p>

                <p class="origin-comment-content-<?php echo htmlspecialchars($comment['id']); ?>"><?php echo htmlspecialchars($comment['content']); ?></p>
                <!-- 댓글 수정 및 삭제 -->
                <div class="revise revise-box-<?php echo htmlspecialchars($comment['id']); ?> hide" data-id="<?php echo htmlspecialchars($comment['id']); ?>">
                    <form action="edit_comment.php" method="POST">
                        <textarea name="edit_comment" class="form-control revise-comment" rows="3"><?php echo htmlspecialchars($comment['content']); ?></textarea>
                        <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($comment['post_id']); ?>">
                        <button type="submit" class="btn btn-light">수정</button>
                        <a class="btn btn-light cancel">취소</a>
                    </form>
                </div>

                <?php

                $replies_sql = "SELECT id, user, content, created_time FROM comments WHERE parent_id = " . $comment['id'] . " ORDER BY created_time ASC";
                $replies_result = mysqli_query($conn, $replies_sql);
                ?>

                <?php while ($reply = mysqli_fetch_assoc($replies_result)) : ?>
                    <div class="reply reply-id">
                        <p><strong><?php echo htmlspecialchars($reply['user']); ?></strong> (<?php echo $reply['created_time']; ?>)</p>
                        <p><?php echo nl2br(htmlspecialchars($reply['content'])); ?></p>
                    </div>
                <?php endwhile; ?>

                <span class="double-comment-btn" id="<?php echo htmlspecialchars($comment['id']); ?>">답글 쓰기</span>

                        <div class="double-comment-section double-comment-section-<?php echo htmlspecialchars($comment['id']); ?>">
                <!-- Reply form -->
                <div class="reply-form">
                    <form action="add_reply.php" method="post">
                        <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                        <textarea name="comment" class="form-control" rows="3" placeholder="답글 작성"></textarea>
                        <button type="submit" class="btn btn-primary">작성</button>
                    </form>
                </div>
            </div>


                        </div>
                        <?php endwhile; ?>
        <?php else: ?>
            <p>댓글이 없습니다.</p>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="comment" class="form-label"></label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">댓글 작성</button>
        </form>
    </div>


</div>

<script>
    $(document).ready(function() {
        let loggedInUser = '<?php echo $_SESSION['username']; ?>';

        $('.revise-btn').click(function(e) {
            let i = (e.target).dataset.id;
            let author = (e.target).dataset.author;

            if (author !== loggedInUser) {
                alert('권한이 없습니다.');
                return;
            }
            $('.origin-comment-content-' + i).addClass('hide');
            $('.revise-box-' + i).removeClass('hide');
        });

        $('.cancel').click(function(e) {
            let i = $(e.target).parent().parent().data('id');
            $('.origin-comment-content-' + i).removeClass('hide');
            $('.revise-box-' + i).addClass('hide');
        });

        function ShowOrHide(p1, p2) {
            $(p1).click(function(e) {
                let clk = $(p2 + '-' + e.target.id);
                if (clk.hasClass('show')) {
                    clk.addClass('hide');
                    clk.removeClass('show');
                } else {
                    clk.addClass('show');
                    clk.removeClass('hide');
                }
            });
        }

        ShowOrHide('.set-img', '.set-box');
        ShowOrHide('.double-comment-btn', '.double-comment-section');

                // 댓글 삭제 처리
        $('.delete-btn').click(function(e) {
            if (!confirm('정말로 이 댓글을 삭제하시겠습니까?')) {
                return;
            }
            let commentId = $(this).data('id');
            let postId = <?php echo $id; ?>;
            $.post('delete_comment.php', { comment_id: commentId, post_id: postId }, function(response) {
                if (response.trim() == '') {
                    location.reload(); // 성공 시 페이지 새로고침
                } else {
                    alert(response); // 오류 메시지 출력
                    location.reload(); // 오류 메시지 출력 후 새로고침
                }
            }).fail(function() {
                alert('서버 요청 중 오류가 발생했습니다.');
            });
        });

        });


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>

<?php mysqli_close($conn); ?>