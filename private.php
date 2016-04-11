<?php
session_start();
if (!isset($_SESSION['username'] )) {
    header("location:./index.php");
}
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type='text/css' href="pure-min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="jquery-ui.css" type="text/css" rel="stylesheet" />
	<style type="text/css"> 
		.pure-button{
			font-size: <?php if($_GET['argument'] == checkOut || $_GET['argument'] == checkIn){echo '16px;';} else{echo '30px;';} ?>
			height: <?php if($_GET['argument'] == checkOut || $_GET['argument'] == checkIn){echo '40px;';} else{echo '90px;';} ?>
			width: <?php if($_GET['argument'] == checkOut || $_GET['argument'] == checkIn){echo '95px;';} else{echo '225px;';} ?>
			text-align: center;
			margin: 2px;
			margin-bottom: 5px;
		}
	</style>
</head>
<center>

<?php 
$inactive = 300;
if(isset($_SESSION['timeout']) ) {
    $session_life = time() - $_SESSION['timeout'];
    if($session_life > $inactive){  
        header("Location: logout.php"); 
    }
}
$_SESSION['timeout'] = time();
?>

<body>    
<div id="overall">    
<div id="title">CNN BCC Tool Access</div>
	<div id="nav">
		<ul>
		<li><a href="http://bccwiki.turner.com">BCC Wiki</a></li>
		<li><a href="toolsLog.xml" target="_blank">Tool Log</a></li>
		<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<?php if($_GET['argument'] != checkOut && $_GET['argument'] != checkIn){echo '<br>';} ?>    
	<div id="check">
		<input type="submit" class="pure-button" name="Check Out" value ="Check Out" onClick="location.href='private.php?argument=checkOut';" />
		<input type="submit" class="pure-button" name="Check In" value ="Check In" onClick="location.href='private.php?argument=checkIn';" />
		<br>
		<br>
		<?php
		$Cat_6_KitColor;
		if( strpos(file_get_contents("./quantities.txt"),"Cat_6_Kit 1") !== false) {
			global $Cat_6_KitColor;
			$Cat_6_KitColor = '#00e600';
		}
		else{
			global $Cat_6_KitColor;
			$Cat_6_KitColor = "red";
		}

		$BNC_KitColor;
		if( strpos(file_get_contents("./quantities.txt"),"BNC_Kit 1") !== false) {
			global $Cat_6_KitColor;
			$BNC_KitColor = '#00e600';
		}
		else{
			global $Cat_6_KitColor;
			$BNC_KitColor = "red";
		}

		if($_GET['argument'] == checkOut || $_GET['argument'] == checkIn){
			echo '<div id="div1">';
		}
		else{
			echo '<div id="div1" style="display: none;">';
		}

		if($_GET['argument'] == checkOut){
			echo "<font size='6'>Checking Out</font>";
		}
		if($_GET['argument'] == checkIn){
			echo "<font size='6'>Checking In</font>";
		}
		?>

		<div id="tools">
			<br>
			<button type="submit" class="button" name="Cat_6_Kit" value="Cat_6_Kit" style="background-color:<?php echo $Cat_6_KitColor; ?>" >Cat 6 Kit</button>
			<button type="submit" class="button" name="BNC_Kit" value="BNC_Kit" style="background-color:<?php echo $BNC_KitColor; ?>" />BNC Kit</button>
			<button type="submit" class="button" name="tool3" value="tool3"/>N/A</button>
			<button type="submit" class="button" name="tool4" value="tool4"/>N/A</button>
			<button type="submit" class="button" name="tool5" value="tool5"/>N/A</button>
			<br>
			<button type="submit" class="button" name="tool6" value="tool6"/>N/A</button>
			<button type="submit" class="button" name="tool7" value="tool7"/>N/A</button>
			<button type="submit" class="button" name="tool8" value="tool8"/>N/A</button>
			<button type="submit" class="button" name="tool8" value="tool9"/>N/A</button>
			<button type="submit" class="button" name="tool8" value="tool10"/>N/A</button>
			<br>
		</div>
		<br>

		<div id="green">
			<div id="greenbox"></div>
			<div id="tgreen"> = Tool is available</div>
		</div>

		<div id="red">
			<div id="redbox"></div>
			<div id="tred"> = Tool is NOT available</div>
		</div>
		
		<div id="welcome">
	<?php echo 'Welcome, <b>'.$_SESSION['username'] .'</b>'; ?>
</div>
	</div>
</div>
</body>
	
<div id="dialog" class="myDialog" title="Your session is about to expire!">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		You will be logged off in <span id="dialog-countdown" style="font-weight:bold"></span> seconds.
	</p>

	<p>Do you want to continue your session?</p>
</div>

</center>

<script type="text/javascript" src="jquery-2.1.4.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    $('.button').click(function(){
        var clickBtnValue = $(this).val();
        var tempr = '<?php echo $_GET['argument']; ?>';
		var actionString = tempr.toLowerCase().replace('check','check ');
		var valueString = clickBtnValue.split('_').join(' ');
		var ajaxurl = "lock.php?argument=" + tempr + "&toolc=" + clickBtnValue;
        data =  {'action': clickBtnValue};
		var mightdo = confirm("Confirm " + actionString + " for " + "' " + valueString + " '");
	if(mightdo == true){
            $.post(ajaxurl, data, function (response) {
				window.location.href = window.location.href
                alert(response);
            });
           
        }
    });

});
</script>

<script src="jquery.min.js" type="text/javascript"></script>
<script src="jquery-ui.min.js" type="text/javascript"></script>
<script src="jquery.idletimer.js" type="text/javascript"></script>
<script src="jquery.idletimeout.js" type="text/javascript"></script>

<script>
$("#dialog").dialog({
	autoOpen: false,
	modal: true,
	width: 400,
	height: 300,
	closeOnEscape: false,
	open: function(event, ui) { $(".ui-dialog-titlebar-close").hide();},
	draggable: false,
	resizable: false,
	buttons: {
		'Keep Working': function(){
			$(this).dialog('close');
			window.location.href = window.location.href;
		},
		'Logout': function(){
			$.idleTimeout.options.onTimeout.call(this);
		}
	}
});

var $countdown = $("#dialog-countdown");
$.idleTimeout('#dialog', 'div.ui-dialog-buttonpane button:first', {
	idleAfter: 180,
	pollingInterval: 2,
	keepAliveURL: 'keepalive.php',
	serverResponseEquals: 'OK',
	onTimeout: function(){
		window.location = "logout.php";
	},
	onIdle: function(){
		$(this).dialog("open");
	},
	onCountdown: function(counter){
		$countdown.html(counter);
	}
});
</script>
</html>
