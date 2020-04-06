<?php
	header('Content-Type: image/jpg');
	readfile('../images/' . $_GET['img']);
?>
