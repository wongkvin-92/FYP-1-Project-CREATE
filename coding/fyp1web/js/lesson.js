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

/*
 * To add new Class Lesson
 */
function displayNewLesson(venue, type, lecturer, date, time, duration, subject){
  var newLessonRow =
  `<tr class="listContent">
        <td class="venue" data-field="venue">`+venue+`</td>
        <td class="type"  data-field="type">`+type+`</td>
        <td class="lecturer"  data-field="lecturer">`+lecturer+`</td>
        <td class="datetime"  data-field="date">`+date+ " "+time+`</td>
        <td class="duration"  data-field="duration">`+duration+`</td>
        <td class="subject"  data-field="subject">`+subject+`</td>
        <td class="edit"><button class="btn btn-default btn-sm edit-lesson-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td class="remove"><button class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-trash-o" aria-hidden="true"></button></td>
      </tr>
      `;
      $('#lessonTable').append($(newLessonRow));
}



function insertLessonData(data){
  $('#lessonTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewLesson( item.venue,  item.type, item.lecturer, item.date,item.time,item.duration, item.subjectID);


  }
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
        currentEdit.html('<td class="edit"><button class="btn btn-default btn-sm edit-lesson-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>');
        isEditing = false;
  }else {
     // Display editable input row
     isEditing = true;
     $(this).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');

     var tdVenueValue = tdVenue.html(),
         tdTypeValue = tdType.html(),
         tdDateTimeValue = tdDateTime.html(),
         tdDurationValue = tdDuration.html();


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
     tdVenue.html('<input type="text" name="venue" value="' + tdVenueValue + '">');
     tdType.html('<select name="type" value=""> <option value="' + tdTypeValue + '"> ' + tdTypeValue + ' </option></select>');
     tdDateTime.html('<input type="datetime" name="datetime" value="' + tdDateTimeValue + '">');
     tdDuration.html('<input type="text" name="duration" value="' + tdDurationValue + '">');
  }

});



//End of Editing Class Lesson

/*
 * To Remove a lesson data
 */

// Handles live/dynamic element events, i.e. for newly created trash buttons
$(document).on('click', '.remove-item-btn', function() {
   // Turn off editing if row is current input
  if(isEditing) {
  }else{
       var key = $(this).parent().parent().children('.venue').html();

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

  }
});

// End of removing Class Lesson
