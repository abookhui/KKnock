<?php //session_start();?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <style>

                .hide{display:none;}

        </style>

</head>
<body>
    <nav>
        <ul class="nav-container">
            <li class="nav-item"><a href="index.php" id="link">Home</a></li>
                                <li class="nav-item loggedin"><a href="login.php" id="link">Login</a></li>
                                <li class="nav-item loggedin"><a href="register.php" id="link">register</a></li>
                                <li class="nav-item"><a href="list.php" id="link">list</a></li>
                                <li class="nav-item"><a href="write.php" id="link">write</a></li>
                                <li class="nav-item"><a href="mypage.php" id="link">mypage</a></li>
        </ul>
    </nav>

        <?php

                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
                echo  ("<script>
                                $('.loggedin').addClass('hide');
                        </script>");}

        ?>

        <br><b></b><b></b><br>
        <script>
        $('.nav-item').on('mouseover',function(e){
            $(e.target).css('color','grey')
        })
        $('.nav-item').on('mouseout',function(e){
            $(e.target).css('color','white')
        })
    </script>


</body>
</html>