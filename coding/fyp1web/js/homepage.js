var backEndUrl='/fypBackEnd';

(function() {
  $('.dashboard-nav__item').on('click', function(e) {
    var itemId;
    e.preventDefault();
    $('.dashboard-nav__item').removeClass('dashboard-nav__item--selected');
    $(this).addClass('dashboard-nav__item--selected');
    itemId = $(this).children().attr('href');
    $('.dashboard-content__panel').hide();
    $('.dashboard-content__panel[data-panel-id=' + itemId + ']').show();
    if(itemId == "timetable")
      refreshTimeTable();
    return console.log(itemId);
  });
}).call(this);



$('#submitSemdate').on('click', ()=> {
  var startSemDay = $('#start-sem-day').val();
  var endSemDay = $('#end-sem-day').val();

  /**
  * do validation here
  **/
    $.ajax({
      url:backEndUrl + "/admin/semester/",
      method: "POST",
      dataType: "json",
      data: JSON.stringify({
        'startDate' : startSemDay,
        'endDate' : endSemDay
      }),
      success: function(a){
        alert("successfully created");
        //newSuccessAlert("Successfully added!");
      },
    error: function(reply){
        reply = reply.responseJSON;
        newErrorAlert(reply.msg);
    }
  });

});


$.ajax({
  url:backEndUrl + "/classes/pending/count/",
  method: "GET",
  dataType: "json",
  success: function(a){
    $('#countRequest').html(a.count);
  }
});



/*

*/

var newWarningAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newSuccessAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newErrorAlert = function(msg){
 return `<div class="alert alert-danger">
    `+msg+`
</div>`;
}

var newInfoAlert = function(msg){
  return `    <div class="alert alert-info">
      `+msg+`
  </div>`
}
