<html>  
<head>
  <link rel="stylesheet" type="text/css" href="main.css">
  <link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="pure-min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>



<?php
session_start();
unset($_SESSION["username"]);
unset($_SESSION['timeout']);
session_unset();
session_destroy();
?>

</style>
<div id="overall">
<div id="title">CNN BCC Tool Access</div>
<div id="login">
  <form method="POST" class="pure-form pure-form-stacked">
    <center>Sign In</center>
	<hr>
	<fieldset>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" class="pure-input-1" placeholder="Username">
        <label for="password">Password</label>
        <input id="password" name="pasword" type="password" class="pure-input-1" placeholder="Password">
        <button id="submit_login" name="submit" value="Login" type="submit" class="pure-button pure-button-primary">Sign in</button>
		<span class="errormess"></span>
    </fieldset>
  
  </form>
</div>
</div>
</html>

<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>

<script>
$(function() {
  $("#submit_login").click(function() { // if submit button is clicked
    var username = $("input#username").val(); // define username variable
    if (username == "") { // if username variable is empty
       $('.errormess').html('Insert Username'); // printing error message
       return false; // stop the script
    }
    var password = $("input#password").val(); // define password variable
    if (password == "") { // if password variable is empty
       $('.errormess').html('Insert Password'); // printing error message
       return false; // stop the script
    }
  
    $.ajax({ // JQuery ajax function
      type: "POST", // Submitting Method
      url: 'login.php', // PHP processor
      data: 'username='+ username + '&password=' + password, // the data that will be sent to php processor
      dataType: "html", // type of returned data
      success: function(data) { // if ajax function results success
      if (data == 'Could not bind to AD.' || data == 'Could not connect to LDAP server.' || data == 'Wrong login data' || data == 0) { // if the returned data equal 0
      $('.errormess').html('<b style="color: red;"> Wrong Login Data</b>'); // print error message
      // $('.errormess').html(data);
      } else { // if the reurned data not equal 0
      $('.errormess').html('<b style="color: green;"> Signing In</b>');// print success message 
      setTimeout(function(){ 
      document.location.href = 'private.php'; // redirect to the private area
      },500);  
      }
      }
     });
    return false;
    });
});

</script>
