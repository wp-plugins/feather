<?php header('HTTP/1.1 500 Internal Server Error'); // Send 500 HTTP header ?>

<!DOCTYPE html> 
<html dir="ltr" lang="en-US"> 
<head> 
<meta charset="UTF-8"> 
<title><?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php echo FEATHER_URL.'assets/css/feather-tmpl.css'; ?>">
</head>
<body class="error">
<div>
	<p class="version">Feather</p>
	<h1>Framework Error</h1>
	<p class="msg">PHP 5.3 or greater required. Please check with your web host to see if PHP 5.3 is supported.</p>
</div>
</body>
</html>
