<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
    $pathInPieces = explode('/', $_SERVER["SCRIPT_NAME"]);
    $projectpath = $pathInPieces[1];

    $uri .= $_SERVER['HTTP_HOST'];
    $uri .= '/';
    $uri .= $projectpath;
    
	header('Location: '.$uri.'/public/');
	exit;
?>