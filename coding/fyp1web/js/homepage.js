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

$.ajax({
  url:backEndUrl + "/classes/pending/count/",
  method: "GET",
  dataType: "json",
  success: function(a){
    $('#countRequest').html(a.count);
  }
});


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
