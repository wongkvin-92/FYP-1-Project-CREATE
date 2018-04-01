var backEndUrl='/fypBackEnd';

$.ajax({
  url: backEndUrl+'/subjects/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      var tag = `
          <option value="`+d.subjectID+`">`+d.subjectID+`</option>
        `;
        $('#hey').append($(tag));

    }
    console.log(reply);

  }
});

$('document').ready( function(){
 $('.s2').select2({
   placeholder: 'Subject Code'
 });
});

$('b[role="presentation"]').hide();
$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');
