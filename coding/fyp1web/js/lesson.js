var backEndUrl='/fypBackEnd',
    venue = $('#venue-field').val(),
    subjCode = $('#subjCode-field').val(),
    lecturer = $('#lecturerName-field').val(),
    classType = $('#type-field').val(),
    date = $('#date-field').val(),
    time = $('t#ime-field').val(),
    duration = $('#duration-field').val();

var subjectCodeModel = {
    isValid: false,
    selector: $('.s2'),
    value: "",
    container: $('.select2-container'),
    validate: function(sub){
	if(!(validateSubject(sub))){
	    $('.select2-container').css("border", "2px solid #ff0000");
	}else{
	    $('.select2-container').css("border", "2px solid #228B22");
	}
    }
};

var lessonCreateViewModel = {
    subjectCode: subjectCodeModel,
    subjectName: {
	selector: $("#subjectName-field")
    },
    lecturerName: {
	selector: $("#lecturerName-field")
    },
    room: {
	selector: $("#venue-field")
    },
    type: {
	selector: $("#type-field")
    },
    date: {
	selector: $("#date-field")
    },
    time: {
	selector: $("#time-field")
    },
    duration: {
	selector: $("#duration-field")
    },
    toJSON: function(){
	return {
	    subjectCode: this.subjectCode.selector.val(),
	    lecturerName: this.lecturerName.selector.val(),
	    subjectName: this.subjectName.selector.val(),
	    room: this.room.selector.val(),
	    type: this.type.selector.val(),
	    duration: this.duration.selector.val(),
	    date: this.date.selector.val(),
	    time: this.time.selector.val()
	};
    },
    isAllEmpty: function(){
	var d = this.toJSON();
	return d.subjectCode == "" &&
	    d.lecturerName == "" &&
	    d.subjectName == "" &&
	    d.room == "" &&
	    d.type == "" &&
	    d.duration == "" &&
	    d.date == "";
    }
};

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
  disappearMsg();
    if(!inEditMode){
	var editBtn  = $("#edit-btn-"+row) ;
	var removeBtn = $("#remove-btn-"+row);

	console.log("Editing lesson with row " + row);
	editBtn.children().attr("class", "fa fa-floppy-o");
	removeBtn.children().attr("class", "fa fa-times");

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

	removeBtn.attr("onclick", "cancelEditLesson("+row+")");
	editBtn.attr("onclick", "saveEditLesson("+row+")");
	inEditMode = true;
    }else{

    }
}

var saveEditLesson = function(row){
    var editBtn  = $("#edit-btn-"+row) ;
    var removeBtn = $("#remove-btn-"+row);

    editBtn.attr("onclick", "startEditLesson("+row+")");
    removeBtn.children().attr("class", "fa fa-trash-o");


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
	    removeBtn.attr("onclick", "removeLesson("+row+")");
	    editBtn.children().attr("class", "fa fa-pencil-square-o");
      createSuccessAlert("Successfully updated");
	},
	complete: function(){
	    document.body.style.cursor = "";
	}
    });



}

var cancelEditLesson = function(row){
    var editBtn  = $("#edit-btn-"+row) ;
    var removeBtn = $("#remove-btn-"+row);

    editBtn.attr("onclick", "startEditLesson("+row+")");
    removeBtn.children().attr("class", "fa fa-trash-o");


    var tdVenue = $(editBtn.parent().parent().find("td")[0]);
    var tdType = $(editBtn.parent().parent().find("td")[1]);
    var tdDateTime = $(editBtn.parent().parent().find("td")[3]);
    var tdDuration = $(editBtn.parent().parent().find("td")[4]);

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
    removeBtn.attr("onclick", "removeLesson("+row+")");
    editBtn.children().attr("class", "fa fa-pencil-square-o");

}

var removeLesson = function(row){
    if(inEditMode){
	alert("Cannot remove while in edit mode");
	return;
    }
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
			lessonTable.ajax.reload(); //quick bugfix
			createSuccessAlert("Successfully removed!");
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
});
    //                { "visible": false,  "targets": [ 0 ] } // removes the first column


    //select2 for subject code
    $('.s2').select2({
    	placeholder: 'Subject Code',
    	tags: true,
    	createTags: function(params){
        if(validateSubject(params.term)){
    	    return{
    		      id: params.term,
    		      text: params.term,
    		      newOption: true
    	    };
        }else{
          return null;
        }
    	},
      createSearchChoice:function(term, data) {
        /*if ( $(data).filter( function() {
          return this.text.localeCompare(term)===0;
        }).length===0) {
          return {id:term, text:term};
        }*/
        return {id:term, text: term};
      }

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
    return `<td class="remove" ><button id="remove-btn-`+row+`" class="btn btn-danger btn-sm remove-item-btn" onClick="removeLesson(`+row+`)" ><i class="fa fa-trash-o" aria-hidden="true"></button></td>`;
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
	     subjectCodeModel.validate(subjectID);
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

function validateSubject(subjectCode){
  //var regexSubjCode = /^(bit)[1-3]{1}.[0-2]{1}.[0-9]{1}|^(bgm)[1-3]{1}.[0-2]{1}.[0-9]{1}|^(bis)[1-3]{1}.[0-2]{1}.[0-9]{1}|^(dip)[1-2]{1}.[0-2]{1}.[0-9]{1}$/;
  var regexSubjCode = /^(bgm|bit|bmc|dip)[1-3][0-2][0-9]$/;
  subjectCodeModel.isValid =  regexSubjCode.test(subjectCode);
  return subjectCodeModel.isValid;
}

function validateLesson(){

    if (lessonCreateViewModel.isAllEmpty()){
	$('.select2-container').css("border", "2px solid #ff0000");
	$('#venue-field').css("border", "2px solid #ff0000");
	$('#lecturerName-field').css("border", "2px solid #ff0000");
	$('#type-field').css("border", "2px solid #ff0000");
	$('#date-field').css("border", "2px solid #ff0000");
	$('#time-field').css("border", "2px solid #ff0000");
	$('#duration-field').css("border", "2px solid #ff0000");
	createErrorAlert("All entries are empty!");
	return false;
/*  } else if (!(validateSubject(subjCode))){
    $('.select2-container').css("border", "2px solid #ff0000");
      alert("Subject entry is not a valid!");*/
    }
    return true;
}

$(document).on('keyup', '.select2-search__field', function (e) {
    var subjCode = $(this).val();
    subjectCodeModel.value = subjCode;
    subjectCodeModel.validate(subjCode);
});

/*
$('.select2-container').on('keyup', function () { {
    if(!(validateSubject(subjCode))){
      $('.select2-container').css("border", "2px solid #ff0000");
    }else{
      $('.select2-container').css("border", "2px solid #228B22");
    }
});*/

/*
 * Add lessons
*/
$('#add-btn').click(function(){
  /*var data = {
    venue : $('#venue-field').val(),
    subjCode : $('#subjCode-field').val(),
    classType : $('#type-field').val(),
    date : $('#date-field').val(),
    time : $('#time-field').val(),
    duration : $('#duration-field').val(),
    lecturer : $('#lecturerName-field').val(),
    subjName : $('#subjectName-field').val()
    };*/
    var data = lessonCreateViewModel.toJSON();

  if(validateLesson()){
      $.ajax({
	  url : backEndUrl+'/lessons/',
	  method : 'POST',
	  dataType : 'json',
	  data : {
	      'venue' : data.room,
	      'subjectID' : data.subjectCode,
	      'type' : data.type,
	      'date' : data.date,
	      'time' : data.time,
	      'duration' : data.duration,
	      'lecturer' : data.lecturerName,
	      'subjectName' : data.subjectName
	  },
	  success: function(reply){
	      var lecName = $('#lecturer-field option:selected').text();
	      //displayNewLesson(reply.classID, data.venue, reply.type, reply.lecturerName, reply.dateTime, data.duration, data.subj);
	      lessonTable.ajax.reload();
	      createSuccessAlert("Successfully added!");
              //apend option
	  },
	  error: function(reply){
	      reply = reply.responseJSON;
	      createErrorAlert(reply.msg);
	  }
      });
  }  

});


var disappearMsg = function(){
    $('#lesson_table_msg').html('');
}

var createSuccessAlert = function(msg){
  $('#lesson_table_msg').html(newSuccessAlert(msg));
  setTimeout(function(){
    disappearMsg();
  }, 2000);
}
var createWarningAlert = function(msg){
  $('#lesson_table_msg').html(newWarningAlert(msg));
  setTimeout(function(){
    disappearMsg();
  }, 2000);
}

var createErrorAlert = function(msg){
  $('#lesson_table_msg').html(newErrorAlert(msg));
  setTimeout(function(){
    disappearMsg();
  }, 2000);
}
