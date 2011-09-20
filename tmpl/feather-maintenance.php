<?php header('HTTP/1.1 503 Service Temporarily Unavailable'); // Send 503 HTTP header ?>
<!DOCTYPE html> 
<html dir="ltr" lang="en-US"> 
<head> 
<meta charset="UTF-8"> 
<title><?php bloginfo('name'); ?></title>
<style type="text/css">
body { font-family:Arial; font-size:15px; margin:60px 80px; text-align: center; }
h1 { color: #444; font-size: 38px; letter-spacing: -1px; line-height:45px; margin-top:0; margin-bottom: 30px; }
.box { width: 500px; margin: 0 auto; }
.note { background:#fffad6; color:#846000; padding:10px; margin:0; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; }
</style>
</head>
<body class="maintenance">
<div class="box">
	<h1>Site Maintenance</h1>
	<p class="note">Site is currently under maintenance. Please check back later.</p>
</div>
</body>
</html>