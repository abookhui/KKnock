<?php
include 'islogin.php'; // 로그인 확인
include 'dbinfo.php'; // 데이터베이스 정보 포함

$conn = mysqli_connect($host,$user,$pw,$db);

$username = $_SESSION['username'];

$sql = "SELECT nickname,gender FROM users WHERE username = '$username'";
$ans = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($ans);
$nickname = $row['nickname'];
$gender = $row['gender'];

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">

        <style>
                .section-top{margin-top: 50px;}
                .d-flex{width: 500px;}
                .right-section{
                        height: 150px;
                        width: 330px;
                }
                .btn-my{margin-top:20px;}
                .info{
                        border-radius: 10px;
                        padding: 10px;
                }
        </style>
</head>

<body>
    <?php include 'nav.php';?>

    <div class="container">
        <div class="side-nav">
            <div class="side-list">
                <div class="list-group">
                    <a href="list.php" class="list-item btn btn-light">🌎 List1</a>
                    <a href="list2.php" class="list-item btn btn-light">🌎 List2</a>
                    <a href="list3.php" class="list-item btn btn-light">🌎 List3</a>
                    <a href="write.php" class="list-item btn btn-light">글 작성</a>
                </div>


            </div>
        </div>

        <div class="section">
            <div class="main-section">
                <div class="section-top">
                    <div class="search-bar">
                        <form class="d-flex" role="search" method="GET" action="userinfo.php">
                            <input class="form-control me-2" type="search" placeholder="유저 검색" name="search" value="<?= $search; ?>">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>

               </div>





                <h4 style="margin: 20px 12px;">마이 페이지</h4>
               <div class="info">
                                        <h1>ID : <?= $username?>  </h1>
                    <p>닉네임 :<?= $nickname?> </p>
                    <p>성별: <?= $gender?></p>
                </div>
            </div>
            <div class="right-section">
                <div class="side-profile">
                    <img src="cat.jpg" alt="" class="profile-img">
                    <p class="profile-name"><?= $username; ?></p>
                </div>

                <form action="logout.php" method="post">
                    <button type="submit" class="btn btn-secondary btn-my">Logout</button>
                </form>
            </div>
        </div>


    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>