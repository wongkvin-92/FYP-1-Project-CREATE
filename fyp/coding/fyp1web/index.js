var backEndUrl = '/fypBackEnd';
$('#btnLogin').on('click', function(){
  var email = $('#email').val();
  var pass = $('#pass').val();
  if(saveEmail === "true"){
    Cookies.set('emailId', email);
  }

  $.ajax({
   url: backEndUrl+'/admin/login/',
   method: 'POST',
   data: {
    email: email,
    password: pass,
   },
   dataType: 'json',
   success: function(reply){
      alert(reply.msg);
		  window.location = "/fyp1web/homepage.php";
   },
    error: function(a,b,c){
    alert(a.responseJSON.msg);
   }
});

});

$(document).on("keypress", function(e){
  var keyCode = e.which || e.keyCode;
  if(keyCode ==  13){
	   $('#btnLogin').click();
    console.log("Enter key pressed");
  }
});

// if condition if is undefined, false
var saveEmail = Cookies.get('saveEmailId');
if(saveEmail == "true"){
  $("#squaredTwo").attr("checked", "checked");
  var email = Cookies.get('emailId');
  $('#email').val(email);
}

$('#squaredTwo').on('click', function(p){
    var isChecked = $(this).is(":checked");
    if(isChecked){
      //set a variable to store when logged in
      var email = $('#email').val();
      saveEmail = true;
      Cookies.set('saveEmailId', "true");
      Cookies.set('emailId', email);
    }else{
      //clear cookie
      saveEmail = false;
      Cookies.set('saveEmailId', "false");
      Cookies.remove('emailId');
    }
});
