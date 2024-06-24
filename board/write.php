<?php
include 'islogin.php';
include 'dbinfo.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION['username'];

// 데이터베이스 연결
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $file_path = '';
        $list_type = $_POST['list_type'];

    // 파일 업로드 처리
    if (isset($_FILES['file'])) { // error,type,tmp_name,name,size

    if ($_FILES['file']['error'] == 0) {// 업로드 상태 오류 코크확인 (0이 성공 or UPLOAD_ERR_OK)
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['file']['name']); // 업로드 파일 이름
        $file_path = $upload_dir . $file_name; // uploads/파일이름

    //tmp_name : 서버에 저장된 임시 파일 경로
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path);


    }

    }

    // 데이터베이스에 게시글 삽입
    $sql = "INSERT INTO posts (title, user, content, file_path, list_type) VALUES ('$title', '$username', '$content', '$file_path', '$list_type')";
    if (mysqli_query($conn, $sql)) {
        //echo "게시글 작성 성공<br>";
        header("Location: list.php?list_type={$list_type}");
        exit();
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>글 작성</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">
        <style>
        .list-select {
                width: 40%;
        }
        .form-group{margin-top:30px;}
        </style>
</head>
<body>
    <?php include 'nav.php';?>

    <div class="container">
        <div class="side-nav ">
            <div class="detail-profile">
                <img src="cat.jpg" class="profile-img" alt="">
                <p><?php echo htmlspecialchars($username); ?></p>
            </div>
        </div>

        <div class="section">
                        <h2>새 글 작성</h2>
            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                        게시판 선택
                                        <input type="radio" class="btn-check" name="list_type" id="list1" value="list1" autocomplete="off" checked>
                                        <label class="btn" for="list1">list1</label>

                                        <input type="radio" class="btn-check" name="list_type" id="list2" value="list2" autocomplete="off">
                                        <label class="btn" for="list2">list2</label>

                                        <input type="radio" class="btn-check" name="list_type" id="list3" value="list3" autocomplete="off">
                                        <label class="btn" for="list3">list3</label>

                                        <br> <br>

                                        <label for="title">제목</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">내용</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="mb-3 form-group">
                    <label for="file" class="form-label">파일 업로드</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                <button type="submit" class="btn btn-primary">작성</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>