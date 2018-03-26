

(function() {
  $('.dashboard-nav__item').on('click', function(e) {
    var itemId;
    e.preventDefault();
    $('.dashboard-nav__item').removeClass('dashboard-nav__item--selected');
    $(this).addClass('dashboard-nav__item--selected');
    itemId = $(this).children().attr('href');
    $('.dashboard-content__panel').hide();
    $('.dashboard-content__panel[data-panel-id=' + itemId + ']').show();

    return console.log(itemId);
  });


}).call(this);
