var backEndUrl='/fypBackEnd';

$.ajax({
  url: backEndUrl+'/rooms/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      var tag = `
          <option value="`+d.roomID+`">`+d.name+`</option>
        `;
        $('#venue-field').append($(tag));
    }
    console.log(reply);
  }
});

var callRooms = function(){
  $.ajax({
    url: backEndUrl+'/rooms/',
    method: 'GET',
    dataType: 'json',
    success: function(reply){
      for(var i=0; i<reply.length; i++){
        d = reply[i];
        var tag = `
            <option value="`+d.roomID+`">`+d.name+`</option>
          `;
          $('#aaaa').append($(tag));
      }
      console.log(reply);

    }
  });
}

$.ajax({
  url: backEndUrl+'/subjects/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      var tag = `
          <option value="`+d.subjectID+`">`+d.subjectName+`</option>
        `;
        $('#subject-field').append($(tag));

    }
    console.log(reply);

  }
});

var callSubj = function(){
  $.ajax({
    url: backEndUrl+'/subjects/',
    method: 'GET',
    dataType: 'json',
    success: function(reply){
      for(var i=0; i<reply.length; i++){
        d = reply[i];
        var tag = `
            <option value="`+d.subjectID+`">`+d.subjectName+`</option>
          `;
          $('#yyyy').append($(tag));

      }
      console.log(reply);

    }
  });
}



$.ajax({
  url: backEndUrl+'/subjects/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      var tag = `
          <option value="`+d.subjectID+`">`+d.subjectName+`</option>
        `;
        $('#subject-field').append($(tag));

    }
    console.log(reply);

  }
});
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

function insertLessonData(data){
  $('#lessonTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewLesson( item.venue,  item.type, item.lecturer, item.date,item.time,item.duration, item.subject);

  }
}

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


var isEditing = false,
    tempNameValue = "",
    tempDataValue = "",
    tempVenueValue = "",
    tempTypeValue = "",
    tempDateTimeValue = "";

// Handles live/dynamic element events, i.e. for newly created edit buttons
$(document).on('click', '.edit-lesson-btn', function() {
   var parentRow = $(this).closest('tr'),
       tableBody = parentRow.closest('tbody'),
       tdName = parentRow.children('td.code'),
       tdData = parentRow.children('td.name'),
       tdVenue = parentRow.children('td.venue'),
       tdType = parentRow.children('td.type'),
       tdDateTime = parentRow.children('td.datetime');

   if(isEditing) {
      var nameInput = tableBody.find('input[name="code"]'),
          dataInput = tableBody.find('input[name="name"]'),
          venueSelect = tableBody.find('select[name="venue"]'),
          typeSelect = tableBody.find('select[name="type"]'),
          dateTimeInput = tableBody.find('input[name="datetime"]'),
          tdNameInput = nameInput.closest('td'),
          tdDataInput = dataInput.closest('td'),
          tdVenueSelect = venueSelect.closest('td'),
          tdTypeSelect = typeSelect.closest('td'),
          tdDateTimeInput = dateTimeInput.closest('td'),
          currentEdit = tdNameInput.parent().find('td.edit');

      if($(this).is(currentEdit)) {
         // Save new values as static html
         var tdNameValue = nameInput.prop('value'),
             tdDataValue = dataInput.prop('value'),
             tdVenueValue = venueSelect.prop('value'),
             tdTypeValue = typeSelect.prop('value'),
             tdDateTimeValue = dateTimeInput.prop('value');

         tdNameInput.empty();
         tdDataInput.empty();
         tdVenueSelect.empty();
         tdTypeSelect.empty();
         tdDateTimeInput.empty();

         tdNameInput.html(tdNameValue);
         tdDataInput.html(tdDataValue);
         tdVenueSelect.html(tdVenueValue);
         tdTypeSelect.html(tdTypeValue);
         tdDateTimeInput.html(tdDateTimeValue);
      }
      else {
         // Restore previous html values
        tdNameInput.empty();
        tdDataInput.empty();
        tdVenueSelect.empty();
        tdTypeSelect.empty();
        tdDateTimeInput.empty();

         tdNameInput.html(tempNameValue);
         tdDataInput.html(tempDataValue);
         tdVenueSelect.html(tempVenueValue);
         tdTypeSelect.html(tempTypeValue);
         tdDateTimeInput.html(tempDateTimeValue);
      }
      // Display static row
      currentEdit.html('<td class="edit"><button class="btn btn-default btn-sm edit-item-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>');
      isEditing = false;
   }
   else {
      // Display editable input row
      isEditing = true;
      $(this).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');

      var tdNameValue = tdName.html(),
          tdDataValue = tdData.html(),
          tdVenueValue = tdVenue.html(),
          tdTypeValue = tdType.html(),
          tdDateTimeValue = tdDateTime.html();

      // Save current html values for canceling an edit
      tempNameValue = tdNameValue;
      tempDataValue = tdDataValue;
      tempVenueValue = tdVenueValue;
      tempTypeValue = tdTypeValue;
      tempDateTimeValue = tdDateTimeValue;

      // Remove existing html values
      tdName.empty();
      tdData.empty();
      tdVenue.empty();
      tdType.empty();
      tdDateTime.empty();

      // Create input forms
      tdName.html('<input type="text" name="code" value="' + tdNameValue + '">');
      tdData.html('<input type="text" name="name" value="' + tdDataValue + '">');
      tdVenue.html('<select name="venue" value="" id="yyyy"> '+callRooms()+' <option value="">'+tdVenueValue+'</option></select>');
      tdType.html('<select name="type" value="" id="aaaa">  <option value="lecture" >'+tdTypeValue+'</option><option value="tutorial">Tutorial</option></select>');
      tdDateTime.html('<input type="datetime-local" name="datetime" value="' + tdDateTimeValue + '">');

   }
});

// Handles live/dynamic element events, i.e. for newly created trash buttons
$(document).on('click', '.remove-item-btn', function() {
   // Turn off editing if row is current input
  if(isEditing) {
  }else{
       var key = $(this).parent().parent().children('.code').html();

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

       $.ajax({
           "url": backEndUrl+ "/subjects/"+key+"/",
           "method": "DELETE",
           "dataType" : "json",
           "success" : function(response){
              deleteRow();
              alert(response.msg);
           }
       });
  }
});






$('document').ready( function(){
 $('.s2').select2({
   placeholder: 'None'

 });
});
