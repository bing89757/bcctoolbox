<script type="text/javascript" src="http://code.jquery.com/jquery-1.3.2.min.js"></script>
<script>
$(function() {
  $("#submit_login").click(function() { // if submit button is clicked
    var username = $("input#username").val(); // define username variable
    if (username == "") { // if username variable is empty
       $('.errormess').html('Please Insert Your Username'); // printing error message
       return false; // stop the script
    }
    var password = $("input#password").val(); // define password variable
    if (password == "") { // if password variable is empty
       $('.errormess').html('Please Insert Your Password'); // printing error message
       return false; // stop the script
    }
  
    $.ajax({ // JQuery ajax function
      type: "POST", // Submitting Method
      url: 'login.php', // PHP processor
      data: 'username='+ username + '&password=' + password, // the data that will be sent to php processor
      dataType: "html", // type of returned data
      success: function(data) { // if ajax function results success
      if (data == 0) { // if the returned data equal 0
      $('.errormess').html('Wrong Login Data'); // print error message
      } else { // if the reurned data not equal 0
      $('.errormess').html('<b style="color: green;">you are logged. wait for redirection</b>');// print success message   
      document.location.href = 'private.php'; // redirect to the private area  
      }
      }
     });
    return false;
    });
});
</script>

