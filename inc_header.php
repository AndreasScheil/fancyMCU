<?php 
/**
<script src="js/doclist.js" type="text/javascript" charset="utf-8"></script>
**/
function copyToDir($pattern, $dir)
{
    foreach (glob($pattern) as $file) {
        if(!is_dir($file) && is_readable($file)) {
            $dest = realpath($dir . DIRECTORY_SEPARATOR) . basename($file);
            copy($file, $dest);
        }
    }    
}

?>
<!DOCTYPE html> 
<html>
	<head>
		<title>fancyMCU</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.css">
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.min.js"></script>	
		<script src="js/ace.js" data-ace-base="src" type="text/javascript" charset="utf-8"></script>		
				
	</head>
	<body>