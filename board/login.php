<?php
session_start();

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] === true) {
        echo '이미 로그인 되어있습니다. 잘못된 접근.';
        exit;
}

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="list.css">
</head>
  <body>
  <?php include 'nav.php';?>
    <div class = "login-container">
        <div class="login-md">
                        <?php
                                if (isset($_GET['message'])) {
                                        echo '<p style="color:red;">' . htmlspecialchars($_GET['message']) . '</p>';
                                }

                         ?>

            <h1>LogIn</h1>
            <form action="check_login.php" method="POST">
                <input type="username" class="form-control" name="id" id="login-ID" placeholder="ID">
                <input type="password" class="form-control" name="password"id="login-pw" placeholder="PW">
                <button type="submit" class="btn btn-secondary btn-lg">Login</button>
            </form>
        </div>

    </div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>