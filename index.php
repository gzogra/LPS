<?php
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Frame-Options: SAMEORIGIN");

if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="style.css" rel="stylesheet" type="text/css">
		<title>LLM PromptScope Framework</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<style>		
		.bg {
		  animation:slide 3s ease-in-out infinite alternate;
		  background-image: linear-gradient(-60deg, #6CCECB 50%, #218C8D 50%);
		  bottom:0;
		  left:-50%;
		  opacity:.5;
		  position:fixed;
		  right:-50%;
		  top:0;
		  z-index:-1;
		}
		
		.bg2 {
		  animation-direction:alternate-reverse;
		  animation-duration:4s;
		}
		
		.bg3 {
		  animation-duration:5s;
		}
		
		.content {
		  background-color:rgba(255,255,255,.8);
		  border-radius:.25em;
		  box-shadow:0 0 .25em rgba(0,0,0,.25);
		  box-sizing:border-box;
		  left:50%;
		  padding:10vmin;
		  position:fixed;
		  text-align:center;
		  top:50%;
		  transform:translate(-50%, -50%);
		}
		
		
		@keyframes slide {
		  0% {
		    transform:translateX(-25%);
		  }
		  100% {
		    transform:translateX(25%);
		  }
		}
		
		.btn{
		    padding:10px;
		    margin:10px;
		    border-radius:10px;
		    font-size:14px;
		    color:white;
		    background-color:#218C8D;
		    width:70%;
		    border:0px;
		    cursor:pointer;
		}
		
		</style>
	</head>
	<body>
		<div class="bg"></div>
		<div class="bg bg2"></div>
		<div class="bg bg3"></div>
		<div class="login">
			<h1>LLM-PromptScope<br/>Framework</h1>
			
			<center>
			<img style='margin 0 auto; border-radius:5px;' src="ai.gif">
			</center>
			<form id='mf' action="authenticate.php" method="post">
				<label for="username"><i class="fas fa-user"></i></label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password"><i class="fas fa-lock"></i></label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="hidden" name='device' id="device" value="" />
				<input type="submit" value="Login">
			</form>
		</div>

<script>
   function onSubmit(token) {
     document.getElementById("mf").submit();
   }
</script>

<script>
var isMobile = 0; 
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 isMobile = 1;
 
}

document.getElementById("device").value=isMobile;
console.log("isMobile",isMobile);

</script>
	</body>
</html>
