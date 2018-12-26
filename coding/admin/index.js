var backEndUrl = '/fypBackEnd';


//Using regular expressions is probably the best way. You can see a bunch of tests here (taken from chromium)


function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
$('input#email').keyup(function() {
    var email = $("#email").val();
  if(!(validateEmail(email))){
          $('#email').css("border-bottom", "2px solid #ff0000");
}else{
  $('#email').css("border-bottom", "2px solid #228B22");
}
});

$('input#pass').keyup(function() {
  var pass = $('#pass').val();
    if(pass.length<6){
      $('#pass').css("border-bottom", "2px solid #ff0000");
    }else{
      $('#pass').css("border-bottom", "2px solid #228B22");
    }
});

function validate() {

  var email = $("#email").val();
  var pass = $('#pass').val();
  if (email == "" && pass == "")  {

      $('#email').css("border-bottom", "2px solid #ff0000");
      $('#pass').css("border-bottom", "2px solid #ff0000");
      alert("All entries are empty!");
  } else {
        if (email == ""){

        $('#email').css("border-bottom", "2px solid #ff0000");
          alert("Email entry are empty!");
        }else if (!(validateEmail(email)) ) {

          $('#email').css("border-bottom", "2px solid #ff0000");
          alert("Email entry is not a valid!");
          if(pass==""){

            $('#pass').css("border-bottom", "2px solid #ff0000");
          }
        }else if(pass.length<6){
          alert("Password should be more than 5 characters!");
        }
         else {

        $('#email').css("border-bottom", " 2px solid #228B22");
        return true;
        }
    }
    return false;
}

//$("#btnLogin").bind("click", validate);

$('#btnLogin').on('click', function(e){

  var email = $('#email').val();
  var pass = $('#pass').val();
  if(saveEmail === "true"){
    Cookies.set('emailId', email);
  }
 if(validate()){
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
    		  window.location = "/admin/homepage.php";
       },
        error: function(a,b,c){
        alert(a.responseJSON.msg);
          $('#pass').css("border-bottom", "2px solid #ff0000");
       }
    });

}

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
