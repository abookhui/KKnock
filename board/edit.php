<?php
include 'islogin.php'; // 로그인 확인
include 'dbinfo.php'; // 데이터베이스 정보 포함

// GET 요청으로부터 글 ID 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
//$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
//echo $id; // 디버깅용

// 해당 ID의 글을 데이터베이스에서 가져오기
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT title, content, user FROM posts WHERE id = $id";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$post = mysqli_fetch_assoc($result);

// 글이 존재하지 않거나, 로그인한 사용자와 글 작성자가 다르면 수정xx
if (!$post) {
     // echo "수정할 수 없습니다. 글이 존재하지 않습니다.";
        echo htmlspecialchars($id);
    exit();
} elseif ($post['user'] !== $_SESSION['username']) {
    echo "수정할 수 없습니다. 권한이 없습니다.";
    exit();
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>글 수정</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">


        <style>
                .section{margin-top:70px;}
        </style>
</head>
<body>
    <?php include 'nav.php';?>
    <div class="container">
        <div class="section">
            <h2>글 수정</h2>
            <form action="edit_post.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">제목</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">내용</label>
                    <textarea class="form-control" id="content" name="content" rows="5"><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">수정</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>