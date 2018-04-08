var backEndUrl='/fypBackEnd',
    venue = $('#venue-field').val(),
    subjCode = $('#subjCode-field').val(),
    lecturer = $('#lecturerName-field').val(),
    classType = $('#type-field').val(),
    date = $('date-field').val(),
    time = $('time-field').val(),
    duration = $('duration-field').val();

var dateTimeFormat = "dddd HH:MM";

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
        $('#subjCode-field').append($(tag));
    }
    console.log(reply);

  }
});

$.ajax({

  url: backEndUrl +'/lecturers/',
  method:'GET',
  dataType:'json',
  success: function(reply){
    for(var i = 0; i<reply.length; i++){
      record = reply[i];
      var recordTag = `
        <option value="`+record.lecturerID+`">`+record.lecturerName+`</option>
      `
        $('#lecturerName-field').append($(recordTag));
    }
  }
});

$('document').ready( function(){
 $('.s2').select2({
   placeholder: 'Subject Code',
   tags: true,
   createTags: function(params){
     return{
       id: params.term,
       text: params.term,
       newOption: true
     }
   }
 });
});

$('b[role="presentation"]').hide();
$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');

/*
 * To add new Class Lesson
 */
function displayNewLesson(id, venue, type, lecturer, datetime, duration, subject){
  var dateTimeStr = moment(datetime).format(dateTimeFormat);
  var newLessonRow =
  `<tr class="listContent">
        <input type="hidden" id="lesson_id" name='lessonID' value="`+id+`"/>
        <td colspan="1" class="venue" data-field="venue" >`+venue+`</td>
        <td colspan="1" class="type"  data-field="type" >`+type+`</td>
        <td colspan="1" class="lecturer"  data-field="lecturer" >`+lecturer+`</td>
        <td  class="datetime"  data-date="`+datetime+`" >`+dateTimeStr+`</td>
        <td   class="duration"  data-field="duration" style="position:relative; left:20px;" >`+duration+`</td>
        <td colspan="1" class="subject"  data-field="subject" >`+subject+`</td>
        <td class="edit" style="border-left:1px solid #d8d8d8; padding-left:30px"><button class="btn btn-default btn-sm edit-lesson-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td class="remove" ><button class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-trash-o" aria-hidden="true"></button></td>
      </tr>
      `;
      $('#lessonTable').append($(newLessonRow));
}



function insertLessonData(data){
  $('#lessonTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];

    displayNewLesson(item.id, item.venue,  item.type, item.lecturer, item.dateTime,item.duration, item.subjectID);
  }

listPaginate();
}

$.ajax({
  url: backEndUrl+'/lessons/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      insertLessonData(reply);
      lessonData = reply;
    }
    console.log(reply);
  }
});
/*
 * Retrieve Subject
 */
 $('#subjCode-field').change(function(e){
 var subjectID = $(this).val();
	$.ajax({
   url: "/fypBackEnd/subjects/"+subjectID+"/",
    dataType: 'json',
   success: function(r){
    console.log(r);
	$('#subjectName-field').prop('disabled', true);
	$('#lecturerName-field').prop('disabled', true);
	$('#subjectName-field').val(r.subjectName);
    $('#lecturerName-field').val(r.lecturerID);
  },
  error: function(r){
    $('#subjectName-field').val("");
      $('#lecturerName-field').val("");
    $('#subjectName-field').prop('disabled', false);
    $('#lecturerName-field').prop('disabled', false);
    alert("Subject does not exist. \nYou will create a new subject when you add the lesson");

    }
  });
})
/*
 * Add New Subject
 */

/*
 * Add lessons
*/
$('#add-btn').click(function(){

  var data = {
    venue : $('#venue-field').val(),
    subjCode : $('#subjCode-field').val(),
    classType : $('#type-field').val(),
    date : $('#date-field').val(),
    time : $('#time-field').val(),
    duration : $('#duration-field').val(),
    lecturer : $('#lecturerName-field').val(),
    subjName : $('#subjectName-field').val()

  };

  $.ajax({
    url : backEndUrl+'/lessons/',
    method : 'POST',
    dataType : 'json',
    data : {
      'venue' : data.venue,
      'subjectID' : data.subjCode,
      'type' : data.classType,
      'date' : data.date,
      'time' : data.time,
      'duration' : data.duration,
      'lecturer' : data.lecturer,
      'subjectName' : data.subjName
    },
    success: function(reply){

      var lecName = $('#lecturer-field option:selected').text();
      displayNewLesson(reply.classID, data.venue, reply.type, reply.lecturerName, reply.dateTime, data.duration, data.subjName);
      alert("Successfully added.");

        //apend option
    },
    error: function(reply){

      reply = reply.responseJSON;
      alert(reply.msg);
    }
  });
  console.log(data);

});

// End of Adding Class Lesson

/*
 * To edit class lesson
 */

var isEditing = false,
    tempVenueValue = "",
    tempTypeValue = "",
    tempDateTimeValue = "",
    tempDurationValue = "";

$(document).on('click', '.edit-lesson-btn', function() {
  var parentRow = $(this).closest('tr'),
  tableBody = parentRow.closest('tbody'),
  tdVenue = parentRow.children('td.venue'),
  tdType = parentRow.children('td.type'),
  tdDateTime = parentRow.children('td.datetime'),
  tdDuration = parentRow.children('td.duration');

  if(isEditing) {
    var id = $(this).parent().parent().children('input[name=lessonID]').val();
    var el = $(this).parent().parent();
    /*string value*/
    var d = `{
      "venue": "`+$('.venueTest').val()+`",
      "type": "`+$('[name=type]').val()+`",
      "dateTime": "`+moment($('[name=datetime]').val()).format("YYYY-MM-DD HH:mm:ss")+`",
      "duration": "`+$('[name=duration]').val()+`"
    }`;
    /*json*/
    var x = {
      "venue": $('.venueTest').val(),
      "type": $('[name=type]').val(),
      "dateTime": $('input[name=datetime]').val(),
      "duration": $('[name=duration]').val()
    };

    $.ajax({
      url: backEndUrl + "/lessons/"+id+"/",
      dataType: 'json',
      method: "PATCH",
      data: d,
      processData: false,
      success: function(r){
        el.children(".venue").html(x.venue);
        el.children(".type").html(x.type);
        el.children(".datetime").html(moment(x.dateTime).format(dateTimeFormat));
        el.children(".duration").html(x.duration);
      }
    });

          var venueInput = tableBody.find('input[name="venue"]'),
              typeInput = tableBody.find('select[name="type"]'),
              dateTimeInput = tableBody.find('input[name="datetime"]'),
              durationInput = tableBody.find('input[name="duration"]'),
              tdVenueInput = venueInput.closest('td'),
              tdTypeInput = typeInput.closest('td'),
              tdDateTimeInput = dateTimeInput.closest('td'),
              tdDurationInput = durationInput.closest('td'),
              currentEdit = tdVenueInput.parent().find('td.edit');

              if($(this).is(currentEdit)) {
                //Save new values as static html

                var tdVenueValue = venueInput.prop('value'),
                    tdTypeValue = typeInput.prop('value'),
                    tdDateTimeValue = dateTimeInput.prop('value'),
                    tdDurationValue = durationInput.prop('value');
                    // Restore previous html values
                    tdVenueInput.empty();
                    tdTypeInput.empty();
                    tdDateTimeInput.empty();
                    tdDurationInput.empty();

                    tdVenueInput.html(tdVenueValue);
                    tdTypeInput.html(tdTypeValue);
                    tdDateTimeInput.html(tdDateTimeValue);
                    tdDurationInput.html(tdDurationValue);
              }  else {
                 // Restore previous html values
                 tdVenueInput.empty();
                 tdTypeInput.empty();
                 tdDateTimeInput.empty();
                 tdDurationInput.empty();

                 tdVenueInput.html(tempVenueValue);
                 tdTypeInput.html(tempTypeValue);
                 tdDateTimeInput.html(tempDateTimeValue);
                 tdDurationInput.html(tempDurationValue);
              }
              // Display static row
              currentEdit.html('<button class="btn btn-default btn-sm edit-lesson-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>');
              isEditing = false;

  }else {
     // Display editable input row
     isEditing = true;
     $(this).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');

     var tdVenueValue = tdVenue.html(),
         tdTypeValue = tdType.html(),
         tdDateTimeValue = tdDateTime.html(),
         tdDateTimeSQL = tdDateTime.data().date,
         tdDurationValue = tdDuration.html();
         //console.log(tdDateTimeValue);

     // Save current html values for canceling an edit
     tempVenueValue = tdVenueValue;
     tempTypeValue = tdTypeValue;
     tempDateTimeValue = tdDateTimeValue;
     tempDurationValue = tdDurationValue;

     // Remove existing html values
     tdVenue.empty();
     tdType.empty();
     tdDateTime.empty();
     tdDuration.empty();

     // Create input forms
     tdVenue.html('<input type="text" class="venueTest" name="venue" value="' + tdVenueValue + '" size="4">');
     tdType.html(`<select name="type" value="">
                    <option value="` + tdTypeValue + `">  `+ tdTypeValue + ` </option>
                    <option value="lecture1" >lecture1</option>
                    <option value="lecture2" >lecture2</option>
                    <option value="tutorial1" >tutorial1</option>
                    <option value="tutorial2" >tutorial2</option>
                  </select>`);
     //tdDateTime.html('<input type="datetime-local" name="datetime" value="' + tdDateTimeValue + '" style="width:165px">');
     //tdDateTime.html(`<input type='text' class="form-control" name="datetime" value="` + tdDateTimeValue + `" style="width:165px" id='datetimepicker1'/>`);
      tdDateTime.html(" <input type='text' name='datetime' id='datetimepicker4' />");
              $('#datetimepicker4').datetimepicker({
                  defaultDate: moment(tdDateTimeSQL)
              });

     tdDuration.html('<input type="text" name="duration" value="' + tdDurationValue + '" style="position:relative; left:-10px; text-align:center;" size="2">');


  }

});



//End of Editing Class Lesson

/*
 * To Remove a lesson data
 */

//var dbg = null;

// Handles live/dynamic element events, i.e. for newly created trash buttons
$(document).on('click', '.remove-item-btn', function() {
   // Turn off editing if row is current input
  if(isEditing) {
  }else{
       var key = $(this).parent().parent().children('#lesson_id').val();
    //   dbg = $(this);
    //   console.log(dbg);
       var el = this;
       var deleteRow = function(){

            var parentRow = $(el).closest('tr'),
                tableBody = parentRow.closest('tbody'),
                tdInput = tableBody.find('input').closest('td'),
                currentEdit = tdInput.parent().find('td.edit'),
                thisEdit = parentRow.find('td.edit');

            if(thisEdit.is(thisEdit)) {
               isEditing = false;
            }

            // Remove selected row from table
            $(el).closest('tr').remove();

         }
           if (confirm('Are you sure you want to delete this record?')) {
         $.ajax(
           {
             "url" : backEndUrl +"/lessons/" + key + "/",
             "method" : "DELETE",
             "dataType" : "json",
             "success" : function(response){
               deleteRow();
             }
           });
       }else{
         return;
       }
  }
});

// End of removing Class Lesson

// Beginning of pagination
//pagination
var listPaginate = function(){
(function($){

    var lPaginate = {
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
            var p = $('<div class="lPage" />');

            // add a "first" button
            p.append('<span class="lPage-button">&laquo;</span>');

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
                var pageBtn = $('<span class="lPage-button page-num" />');

                // add active class for current page
                if (i == currentPage) {
                    pageBtn.addClass('active'); }

                // set text to the page number
                pageBtn.text(i);

                // add button to the container
                p.append(pageBtn);
            }

            // add a "last" button
            p.append($('<span class="lPage-button">&raquo;</span>'));

            return p;
        },

        createPage: function(items, currentPage, perPage) {
            // remove pagination from the page
            $('.lPage').remove();

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
    $.fn.lPaginate = function(perPage) {
        var items = $(this);

        // default perPage to 5
        if (isNaN(perPage) || perPage === undefined) {
            perPage = 5; }

        // don't fire if fewer items than perPage
        if (items.length <= perPage) {
            return true; }

        // ensure items stay in the same DOM position
        if (items.length !== items.parent()[0].children.length) {
            items.wrapAll('<div class="lPage-items" />');
        }

        // paginate the items starting at page 1
        lPaginate.createPage(items, 1, perPage);

        // handle click events on the buttons
        $(document).on('click', '.lPage-button', function(e) {
            // get current page from active button
            var currentPage = parseInt($('.lPage-button.active').text(), 9),
                newPage = currentPage,
                totalPages = lPaginate.totalPages(items, perPage),
                target = $(e.target);

            // get numbered page
            newPage = parseInt(target.text(), 9);
            if (target.text() == '«') newPage = 1;
            if (target.text() == '»') newPage = totalPages;

            // ensure newPage is in available range
            if (newPage > 0 && newPage <= totalPages) {
                lPaginate.createPage(items, newPage, perPage); }
        });
    };

})(jQuery);


if($(window).width() >= 1920){
  if (items.length > 12){
    $('.lPage-button').hide();
  }
  else{
    $('.lPage-button').show();
  }
  $( window ).load(function() {
      if (window.location.href.indexOf('reload')==-1) {
           window.location.replace(window.location.href+'?reload');
      }
  });
  $('.listContent').lPaginate(12);
}else if (($(window).width() <= 1919)){
  if (items.length > 6){
    $('.lPage-button').hide();
  }
  else{
    $('.lPage-button').show();
  }
  //var l = (items.length > 6)? 6 : items.length;
  /*
  $( window ).load(function() {
      if (window.location.href.indexOf('reload')==-1) {
           window.location.replace(window.location.href+'?reload');
      }
  });*/
  $('.listContent').lPaginate(6);
}
}
//End of Pagination

/*Customized screen size on specific task running Jquery*/
