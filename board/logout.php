<?php
session_start(); // 세션 시작

// 모든 세션 변수 제거
$_SESSION = array();

// 세션 쿠키 제거
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션 지우
session_destroy();

// 로그인 페이지로 리디렉션
header("Location: login.php");
exit();
?>