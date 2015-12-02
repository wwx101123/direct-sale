<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/10/25
 * Time: 上午9:09
 */
include 'library/init.inc.php';

$code = new Code();

$code->doimg();
$_SESSION['code'] = $code->getCode();
?>