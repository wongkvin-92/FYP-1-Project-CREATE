var backEndUrl = '/fypBackEnd';
var checkEditing = false;

$.ajax({
  url: backEndUrl+'/admin/',
  method: 'GET',
  dataType:'json',
  success: function(reply){
    $('#uName').html(reply.adminName);
}
});

$("#btnLogout").on("click", function(){
    $.ajax({
      url: backEndUrl+'/admin/logout/',
      method: 'GET',
      dataType:'json',
      success: function(reply){
      alert(reply.msg);
      window.location = "index.php";
    }
    });
});


$.ajax({
  url: backEndUrl+'/classes/pending/',
  method: 'GET',
  dataType: 'json',
  success: function(d){
    //items = d;
    for(var i = 0; i < d.length; i++){
      var item = d[i];
      items[item.id] = item;
    }
    addData(items);
  }
});

//insert Data
function newReCard(item){
    return createReCard(item.subjectCode, item.subjectName, item.lecturer, item.reDate, item.reTime, item.duration, item.id, item.venue);
}

function removeClass(id){
  $('#approval-request-' + id).remove();
}

function approveClass(id){
  var regexVenue = /^(sr)[2]{1}.[1-3]{1}|^(lh)[2]{1}.[1-3]{1}|^(ls)[1-2]{1}|^(tis)$/;
  var venue = $('#venue').val();
  if (venue == ""){
    alert("Please enter a class venue!");
    return;
  }else if(!(regexVenue.test(venue))){
    alert(venue + " is invalid!");
        return;
  }
  else{
  if (confirm('Are you sure you approve this Class Reschedulement?')) {
    $.ajax({
        url : backEndUrl+"/classes/"+id+"/approve/",
        method : "GET",
        dataType : "json",
        success : function(r) {

          removeClass(id);
          alert(r.msg);

        }
    });
} else {
    return;
  }
}
}

//create structure of the card
function createReCard(subjCode, subjName, lecturer, rDate, rTime, duration, id, venue){
  var replacementCard = `<div class="content-layout" id="approval-request-`+id+`">


  <div style="padding-bottom:20px;"> <span class="left"></span><button class="btn btn-default btn-sm pull-right btnEdit" onClick="editBtnClicked(`+id+`)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></div>
  <div style="position:relative; z-index:9999; top:-5px;">
    <h3><span class="left">Code:</span> <span class="right" id="subjectCode"></span></h3>
    <p class="class-reschedule_subject"><span class="left">Subject:</span> <span class="right" id="subjectName"></span></p>
    <p><span class="left">Lecturer:</span> <span class="right" id="lecturer"></span></p>
    <p><span class="left">Re-Date:</span> <span class="right date" id="reDate"></span></p>
    <p><span class="left">Re-Time:</span> <span class="right time" id="reTime"></span></p>
    <p><span class="left">Duration:</span> <span class="right" id="duration"></span></p>
    <p><span class="left">Venue:</span> <span class="right"><input type="text" name="venue" placeholder="Class Venue" id="venue" value="`+venue+`"  required/ size="14" disabled></span></p>
    <div class="btn-style">
      <!--<p><a class="btn btn-primary venueBtn" role="button">Check Venue &raquo;</a></p>-->
      <p><a class="btn btn-primary btn-style2 approveBtn" role="button" onClick="approveClass(`+id+`)">Approve &raquo;</a></p>
    </div>
   </div>
  </div>`;

  //jQueryselector
  var jqs = $(replacementCard);

  jqs.find("#subjectCode").html(subjCode);
  jqs.find("#subjectName").html(subjName);
  jqs.find("#lecturer").html(lecturer);
  jqs.find("#reDate").html(rDate);
  jqs.find("#reTime").html(rTime);
  jqs.find("#duration").html(duration);

  return jqs;
}

function addData(itemArray){
   $('#reContainer').html("");
   for(var i=0; i< items.length; i++){
     var item = itemArray[i];
     if(item === undefined)
        continue;
     var card = newReCard(item);
     $('#reContainer').append(card);
   }
   paginate();
}

function findData(items, query){
  var result = [];
  items.forEach( (el) => {
    if(el.subjectCode.match(query.toLowerCase())!=null){
      result.push(el);
    }
  });
  return result;
}



var editBtnClicked = function(id) {
/*
  console.log(this);
    var el = $(this).parent().parent()[0];
  console.log(el);
  */
  var item = items[id];
var subjectBox = $('#approval-request-'+id);

  var subjCard = `
  <div style="padding-bottom:20px;"> <span class="left"></span><button class="btn btn-default btn-sm pull-right btnSave" onClick="goBackViewMode(`+id+`)"><i class="fa fa-close" aria-hidden="true"></i></i></button></div>
  <div style="position:relative; z-index:9999; top:-5px;">
    <h3 ><span class="left">Code:</span> <span class="right" id="subjectCode">`+item.subjectCode+`</span></h3>
    <p ><span class="left">Subject:</span> <span class="right" id="subjectName">`+item.subjectName+`</span></h5>
    <p ><span class="left">Lecturer:</span> <span class="right" id="lecturer">`+item.lecturer+`</span></h5>
    <div class="redateBox">
      <p ><span class="left redateBox">Re-Date:</span> <span class="right date" id="reDate"><input id="newDate" type="date" name="datechanged" value="`+item.reDate+`" /></span></p>
      <p ><span class="left">Re-Time:</span> <span class="right time" id="reTime"><input id="newTime" type="time" name="timechanged" value="`+item.reTime+`" /></span></p>
      <p ><span class="left">Duration:</span> <span class="right" id="duration">2</span></p>
      <p><span class="left">Venue:</span> <span class="right"><input id="newVenue"  type="text" name="venuechanged"  value="`+item.venue+`" required/ size="14"></span></p>
      <div class="btn-style">
        <p><a class="btn btn-primary btn-style2 approveBtn" role="button" onClick="saveBtn(`+id+`)">Save &raquo;</a></p>
      </div>
    </div>
   </div>`;
   if(!checkEditing){
      subjectBox.html(subjCard);
      checkEditing = true;
    }

 };

var saveRecheduling = function(id,date, time, venue){

$.ajax({
 "async": true,
 "crossDomain": true,
 "url": backEndUrl + "/requests/rescheduling/"+id+"/",
 "method": "PATCH",
 "processData": false,
 "dataType": "json",
 "data": "{\n\t\"date\": \""+date+"\",\n\t\"venue\": \""+venue+"\",\n\t\"time\": \""+time+"\"\n}",
 "success": function(response){
   items[id].reDate = date;
   items[id].reTime = time;
   items[id].venue = venue;
   goBackViewMode(id);
   alert(response.msg);
 }
});
}

function goBackViewMode(id){
  checkEditing = false;
  var subjCard = newReCard(items[id]);
  var subjectBox = $('#approval-request-'+id);
  subjectBox.html(subjCard.html());
}

var saveBtn = function(id){
  var date = $('#newDate').val(),
      time = $('#newTime').val(),
      venue = $('#newVenue').val();
  saveRecheduling(id,date, time, venue);
}

//pagination
var paginate = function(){
(function($){

    var paginate = {
        startPos: function(pageNumber, perPage) {
            // determine what array position to start from
            // based on current page and # per page
            return pageNumber * perPage;
        },

        getPage: function(items, startPos, perPage) {
            // declare an empty array to hold our page items
            var page = [];

            // only get items after the starting position
            items = items.slice(startPos, items.length);

            // loop remaining items until max per page
            for (var i=0; i < perPage; i++) {
                page.push(items[i]); }

            return page;
        },

        totalPages: function(items, perPage) {
            // determine total number of pages
            return Math.ceil(items.length / perPage);
        },

        createBtns: function(totalPages, currentPage) {
            // create buttons to manipulate current page
            var pagination = $('<div class="pagination" />');

            // add a "first" button
            pagination.append('<span class="pagination-button">&laquo;</span>');

            // add pages inbetween
            for (var i=1; i <= totalPages; i++) {
                // truncate list when too large
                if (totalPages > 5 && currentPage !== i) {
                    // if on first two pages
                    if (currentPage === 1 || currentPage === 2) {
                        // show first 5 pages
                        if (i > 5) continue;
                    // if on last two pages
                    } else if (currentPage === totalPages || currentPage === totalPages - 1) {
                        // show last 5 pages
                        if (i < totalPages - 4) continue;
                    // otherwise show 5 pages w/ current in middle
                    } else {
                        if (i < currentPage - 2 || i > currentPage + 2) {
                            continue; }
                    }
                }

                // markup for page button
                var pageBtn = $('<span class="pagination-button page-num" />');

                // add active class for current page
                if (i == currentPage) {
                    pageBtn.addClass('active'); }

                // set text to the page number
                pageBtn.text(i);

                // add button to the container
                pagination.append(pageBtn);
            }

            // add a "last" button
            pagination.append($('<span class="pagination-button">&raquo;</span>'));

            return pagination;
        },

        createPage: function(items, currentPage, perPage) {
            // remove pagination from the page
            $('.pagination').remove();

            // set context for the items
            var container = items.parent(),
                // detach items from the page and cast as array
                items = items.detach().toArray(),
                // get start position and select items for page
                startPos = this.startPos(currentPage - 1, perPage),
                page = this.getPage(items, startPos, perPage);

            // loop items and readd to page
            $.each(page, function(){
                // prevent empty items that return as Window
                if (this.window === undefined) {
                    container.append($(this)); }
            });

            // prep pagination buttons and add to page
            var totalPages = this.totalPages(items, perPage),
                pageButtons = this.createBtns(totalPages, currentPage);

            container.after(pageButtons);
        }
    };

    // stuff it all into a jQuery method!
    $.fn.paginate = function(perPage) {
        var items = $(this);

        // default perPage to 5
        if (isNaN(perPage) || perPage === undefined) {
            perPage = 5; }

        // don't fire if fewer items than perPage
        if (items.length <= perPage) {
            return true; }

        // ensure items stay in the same DOM position
        if (items.length !== items.parent()[0].children.length) {
            items.wrapAll('<div class="pagination-items" />');
        }

        // paginate the items starting at page 1
        paginate.createPage(items, 1, perPage);

        // handle click events on the buttons
        $(document).on('click', '.pagination-button', function(e) {
            // get current page from active button
            var currentPage = parseInt($('.pagination-button.active').text(), 10),
                newPage = currentPage,
                totalPages = paginate.totalPages(items, perPage),
                target = $(e.target);

            // get numbered page
            newPage = parseInt(target.text(), 10);
            if (target.text() == '«') newPage = 1;
            if (target.text() == '»') newPage = totalPages;

            // ensure newPage is in available range
            if (newPage > 0 && newPage <= totalPages) {
                paginate.createPage(items, newPage, perPage); }
        });
    };

})(jQuery);
if (items.length > 6)
  $('.pagination-button').hide();
else
  $('.pagination-button').show();
//var l = (items.length > 6)? 6 : items.length;

$('.content-layout').paginate(6);
}

$('#searchReplacement').on('change keyup paste',function(){
  var jqs = $(this);
  var query = jqs.val();

  //if find item words length more than 3 only search
  if (query.length > 3){

         addData(findData(items, query));
  }
  else{
        addData(items);
  }
});

var items = [];
addData(items);
