<?php
include 'islogin.php'; // 로그인 확인
include 'dbinfo.php'; // 데이터베이스 정보 포함

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION['username'];

// 현재 페이지 번호 설정
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 6; // 페이지당 게시글 수
$offset = ($page - 1) * $limit; // 시작 post 번호
// 게시판 번호
$list_type = isset($_GET['list_type']) ? $_GET['list_type'] : 'list1';
$search_query = "where list_type = '$list_type'"; // list 1
//echo $search_query;
// 검색어 설정
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
        $search_query .= " AND (title LIKE '%$search%' OR user LIKE '%$search%')";
}

// 정렬 기준과 순서 설정
$order_list = array('desc','asc');
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : "created_time"; // defalut: 시간
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc'; // defalut: 내림

$selected = 0; // option selected chk
if($sort_by == 'created_time') $selected = ($sort_order == 'desc') ? 0:1;
else $selected = ($sort_order == 'asc') ? 2:3;

// 데이터베이스 연결
$conn = mysqli_connect($host, $user, $pw, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 총 게시글 수 확인
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts $search_query");
$total_row = mysqli_fetch_assoc($total_result);
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $limit);

// SQL 쿼리 실행
$sql = "SELECT id, title, user, created_time FROM posts $search_query ORDER BY $sort_by $sort_order LIMIT $limit OFFSET $offset";

$ans = mysqli_query($conn, $sql);

if (!$ans) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">
</head>
<style>
    .section {
        margin-top: 10px;
    }
</style>

<body>
    <?php include 'nav.php';?>

    <div class="container">
        <div class="side-nav">
            <div class="side-list">
                <div class="list-group">
                                        <a href="list.php" class="list-item btn btn-light <?php if($list_type=='list1') echo 'present'; ?>">🌎 List1</a>
                    <a href="list.php?list_type=list2" class="list-item btn btn-light <?php if($list_type=='list2') echo 'present'; ?>">🌎 List2</a>
                    <a href="list.php?list_type=list3" class="list-item btn btn-light <?php if($list_type=='list3') echo 'present'; ?>">🌎 List3</a>
                    <a href="write.php" class="list-item btn btn-light">글 작성</a>
                </div>


            </div>
        </div>

        <div class="section">
            <div class="main-section">
                <div class="section-top">
                    <div class="search-bar">
                        <form class="d-flex" role="search" method="GET" action="">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?= $search; ?>">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                     <div class="sorting">
                        <select class="form-select sorting-box" id="sortOption">
                            <option value="?list_type=<?=$list_type?>&<?= $search ?>" <?php if($selected==0) echo 'selected'; ?>>최신순</option>
                                                        <option value="?list_type=<?=$list_type?>&sort_order=asc&search=<?= $search?>" <?php if($selected==1) echo 'selected'; ?>>오래된순</option>
                            <option value="?list_type=<?=$list_type?>&sort_by=title&sort_order=asc&search=<?= $search?>" <?php if($selected==2) echo 'selected'; ?>>제목 순</option>
                            <option value="?list_type=<?=$list_type?>&sort_by=title&sort_order=desc&search=<?= $search?>" <?php if($selected==3) echo 'selected'; ?>>제목 역순</option>
                        </select>
                    </div>
                                </div>

                <script>
                    $(document).ready(function() {
                        $('#sortOption').change(function() {
                            const sortOption = $(this).val();
                            window.location.href = sortOption;
                        });
                    });
                </script>



                                        <h4 style="margin: 20px 12px;">게시판 홈 <?= $list_type;?> </h4>
                <div class="post">
                    <?php if (mysqli_num_rows($ans) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($ans)): ?>
                    <div class="post-box post-box-<?php echo $row['id']; ?>" data-id='<?php echo $row['id']; ?>'>

                        <a href="detail.php?id=<?php echo $row['id']; ?>">
                            <div class="post-profile">
                                <img src="cat.jpg" alt="" class="profile-img">
                                <p class="profile-name"><?= $row['user']; ?>
                                    (<?php echo $row['created_time']; ?>)
                                </p>
                            </div>

                            <div class="post-content">
                                <p class="post-content-<?php echo $row['id']; ?>"><?= $row['title']; ?></p>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <div class="post-box">
                        <p>게시글이 없습니다.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right-section">
                <div class="side-profile">
                    <img src="cat.jpg" alt="" class="profile-img">
                    <p class="profile-name"><?= $username; ?></p>
                </div>
                <a href="mypage.php"><button type="button" class="btn btn-secondary btn-my">MYpage</button></a>
                <form action="logout.php" method="post">
                    <button type="submit" class="btn btn-secondary btn-my">Logout</button>
                </form>
            </div>
        </div>

        <div class="next-page">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?list_type=<?=$list_type?>&page=<?= $i?>&search=<?= $search?>&sort_by=<?= $sort_by; ?>&sort_order=<?=$sort_order;?>" class="btn btn-light"><?=$i;?></a>
            <?php endfor; ?>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            //let i = $('.post-box').data('id');
            $('.post-box').on('mouseover', function(e) {
                let i = $(this).data('id');

                //console.log(i);
                $('.post-box-' + i).addClass('select');

                $('.post-box').on('mouseout', function(e) {
                    $('.post-box-' + i).removeClass('select');

                });
            });


        })
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>