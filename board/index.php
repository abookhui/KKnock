<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <title>Document</title>
    <link rel="stylesheet" href="list.css">
<style>
body{
    margin:0;
    background-color:blanchedalmond;
}
.welcom{
    color:chocolate;
}
.team{
    color:darkgoldenrod;
}

.text{
    color:grey;
}
</style>

</head>
<body>
        <?php include 'nav.php';?>

    <h1 class="welcom">Welcome to Kknock Server</h1>
    <br>
    <h2>Team: Kknock Kknock</h2>

    <ol class="team">
        <li>Kong</li>
        <li>Lee</li>
        <li>Jung</li>
        <li>Bong</li>
        <li>asdfasd</li>
    </ol>


</body>
</html>