var backEndUrl='/fypBackEnd',
    venue = $('#venue-field').val(),
    subjCode = $('#subjCode-field').val(),
    lecturer = $('#lecturerName-field').val(),
    classType = $('#type-field').val(),
    date = $('date-field').val(),
    time = $('time-field').val(),
    duration = $('duration-field').val();

var dateTimeFormat = "dddd HH:MM";
var lessonTable = null;

var changeEditMode = function (row){
    lesson = fetchLessonByRow(row);
}

var fetchLesson = function(id){
    return lessonTable.rows().data().filter( e => e.id == id )[0];
}
var fetchLessonByRow = function(row){
    return lessonTable.row(row);
}

var inEditMode = false;

var startEditLesson = function(row){
    if(!inEditMode){
	var editBtn  = $("#edit-btn-"+row) ;
	console.log("Editing lesson with row " + row);
	editBtn.children().attr("class", "fa fa-floppy-o");
	
	var tr = editBtn.parent().parent();	
	
	var tdVenue = $(editBtn.parent().parent().find("td")[0]);
	var tdType = $(editBtn.parent().parent().find("td")[1]);
	var tdDateTime = $(editBtn.parent().parent().find("td")[3]);
	var tdDuration = $(editBtn.parent().parent().find("td")[4]);
	
	var tdVenueInput = $('<input type="text" class="venueTest" name="venue" value="' + tdVenue.html() + '" size="4">');
	var tdTypeInput = $(`<select name="type" value="">
                    <option value="` + tdType.html() + `">  `+ tdType.html() + ` </option>
                    <option value="lecture1" >lecture1</option>
                    <option value="lecture2" >lecture2</option>
                    <option value="tutorial1" >tutorial1</option>
                    <option value="tutorial2" >tutorial2</option>
                  </select>`);
	var tdDateTimeInput = $("<input type='text' name='datetime' id='datetimepicker4' />");
	var tdDurationInput = $(`<input type="text" name="duration" value="` + tdDuration.html() +`" style="position:relative; left:-10px; text-align:center;" size="2">`);
	
	tdDateTimeInput.datetimepicker({
    	    defaultDate: moment(tdDateTimeInput.data().date)
	});

	
	tdVenue.html(tdVenueInput);
	tdType.html(tdTypeInput);
	tdDateTime.html(tdDateTimeInput);
	tdDuration.html(tdDurationInput);
	
	editBtn.attr("onclick", "saveEditLesson("+row+")");
	inEditMode = true;
    }else{

    }
}

var saveEditLesson = function(row){
    console.log("Saving lesson with row "+row);
    var editBtn  = $("#edit-btn-"+row) ;   
    editBtn.attr("onclick", "startEditLesson("+row+")");

    var tdVenue = $(editBtn.parent().parent().find("td")[0]);
    var tdType = $(editBtn.parent().parent().find("td")[1]);
    var tdDateTime = $(editBtn.parent().parent().find("td")[3]);
    var tdDuration = $(editBtn.parent().parent().find("td")[4]);

    var dataToSend = `{
      "venue": "`+tdVenue.children("input").val()+`",
      "type": "`+tdType.children("select").val()+`",
      "dateTime": "`+moment(tdDateTime.children("input").val()).format("YYYY-MM-DD HH:mm:ss")+`",
      "duration": "`+tdDuration.children("input").val()+`"
    }`;

    document.body.style.cursor = "wait";
    $.ajax({
	url: backEndUrl + "/lessons/"+fetchLessonByRow(row).data().id+"/",
	dataType: 'json',
	method: "PATCH",
	data: dataToSend,
	processData: false,
	success: function(r){	    
	    var inputs = [tdVenue, tdDateTime, tdDuration];
	    for(var i =0; i<inputs.length; i++){
		var val = inputs[i].children("input").val();
		if( inputs[i] === tdDateTime ){
		    val = moment(val).format(dateTimeFormat);
		}
		inputs[i].html(val);
	    }
	    tdType.html(tdType.children("select").val());    
	    
	    inEditMode = false;
	    editBtn.children().attr("class", "fa fa-pencil-square-o");    
	},
	complete: function(){
	    document.body.style.cursor = "";
	}
    });


    
}

var cancelEditLesson = function(id){

}

var removeLesson = function(row){
    lesson = fetchLessonByRow(row);
    if(lesson !== undefined){
	var key = lesson.data().id;
	if (confirm('Are you sure you want to delete this record?')) {
            $.ajax(
		{
		    "url" : backEndUrl +"/lessons/" + key + "/",
		    "method" : "DELETE",
		    "dataType" : "json",
		    "success" : function(response){
			lesson.remove();
			lessonTable.draw();
		    }
		});
	}else{
            return;
	}

    }
}

$(document).ready(function(){
    //datatable for lesson
    lessonTable = $('#lesson_datatable').DataTable({
	ajax: {
	    url: backEndUrl+'/lessons/',
	    dataType: 'json',
	    dataSrc: ""
	},
	columns: [
	    {"data": "venue"},
	    {"data": "type"},
	    {"data": "lecturer"},
	    {"data": "dateTime"},
	    {"data": "duration"},
	    {"data": "subjectID"},
	    {"data": "id"}
	],
	columnDefs:[
	    {
                "render": function ( data, type, row , meta) {
		    return createEditButton(meta.row) + createRemoveButton(meta.row);
                },
                "targets": 6
            },
	    {
                "render": function ( data, type, row , meta) {
		    var dateTimeStr = moment(data).format(dateTimeFormat);
		    return `<td  class="datetime"  data-date="`+data+`" >`+dateTimeStr+`</td>`;
                },
                "targets": 3
            },

	]
    });
    //                { "visible": false,  "targets": [ 0 ] } // removes the first column
    
    
    //select2 for subject code
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

function viewRow(id){
    console.log(fetchLesson(id));
}

function createEditButton(row){
    return `
         <td class="edit" style="border-left:1px solid #d8d8d8; padding-left:30px"><button id="edit-btn-`+row+`" class="btn btn-default btn-sm edit-lesson-btn" onClick="startEditLesson(`+row+`)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
`;
}

function createRemoveButton(row){
    return `<td class="remove" ><button class="btn btn-danger btn-sm remove-item-btn" onClick="removeLesson(`+row+`)" ><i class="fa fa-trash-o" aria-hidden="true"></button></td>`;
}



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


$('b[role="presentation"]').hide();
$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');

/*
 * To add new Class Lesson
 */
function displayNewLesson(id, venue, type, lecturer, datetime, duration, subject){
  var dateTimeStr = moment(datetime).format(dateTimeFormat);
    
    /*
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
*/
}



function insertLessonData(data){
  $('#lessonTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewLesson(item.id, item.venue,  item.type, item.lecturer, item.dateTime,item.duration, item.subjectID);
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
    //console.log(reply);
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
 });


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
      displayNewLesson(reply.classID, data.venue, reply.type, reply.lecturerName, reply.dateTime, data.duration, data.subj);
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

/*
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
    //string value
    var d = `{
      "venue": "`+$('.venueTest').val()+`",
      "type": "`+$('[name=type]').val()+`",
      "dateTime": "`+moment($('[name=datetime]').val()).format("YYYY-MM-DD HH:mm:ss")+`",
      "duration": "`+$('[name=duration]').val()+`"
    }`;
    //json
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
2                    tdDateTimeInput.empty();
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
*/


//End of Editing Class Lesson

/*
 * To Remove a lesson data
 */

//var dbg = null;

// Handles live/dynamic element events, i.e. for newly created trash buttons
/*$(document).on('click', '.remove-item-btn', function() {
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

*/
