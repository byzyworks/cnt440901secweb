<?php
	$img_dir = '../images/';
	header('Content-Type: image/jpg');
	readfile($img_dir . basename(realpath($img_dir . $_GET['img'])));
?>