var backEndUrl = '/fypBackEnd';


Using regular expressions is probably the best way. You can see a bunch of tests here (taken from chromium)


function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validate() {
  var $result = $("#result");
  var email = $("#email").val();
  $result.text("");

  if (validateEmail(email)) {
    $result.text(email + " is valid :)");
    $result.css("color", "green");
  } else {
    $result.text(email + " is not valid :(");
    $result.css("color", "red");
  }
  return false;
}

$("#validate").bind("click", validate);
$('#btnLogin').on('click', function(){
  var email = $('#email').val();
  var pass = $('#pass').val();
  if(saveEmail === "true"){
    Cookies.set('emailId', email);
  }
  if($('#email').val())
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
