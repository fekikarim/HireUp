<?php
include '../../../Controller/articleC.php';

$articleC = new ArticleC();
$articleC->deleteArticle($_GET["id"]);
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>