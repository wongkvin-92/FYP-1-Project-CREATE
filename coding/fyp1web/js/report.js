var backEndUrl='/fypBackEnd',
    venue = $('#venue-field').val(),
    subjCode = $('#subjCode-field').val(),
    lecturer = $('#lecturerName-field').val(),
    classType = $('#type-field').val(),
    date = $('#date-field').val(),
    time = $('t#ime-field').val(),
    duration = $('#duration-field').val();


    var reportTableModel = {

        dataTable: null, //initialized later


    };

$(document).ready(function(){
    //datatable for lesson
    reportTableModel.dataTable = $('#report_datatable').DataTable({
	ajax: {
	    url: backEndUrl+'/reports/',
	    dataType: 'json',
	    dataSrc: ""
	},
	columns: [
	    {"data": "lecturer"},
	    {"data": "subjectCode"},
	    {"data": "subStartTime"},
	    {"data": "duration"},
	    {"data": "actualDate"},
	    {"data": "reDate"},
      {"data": "reTime"},
      {"data": "status"},
      {"data": "venue"}
	]
    });
});

function displayNewLesson(lecturer, subjectCode, subStartTime, duration, actualDate, reDate, reTime, status, venue){
    
}

function insertReportData(data){
  $('#reportTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewLesson(item.lecturer, item.subjectCode,  item.subStartTime, item.duration, item.actualDate,item.reDate, item.reTime, item.status, item.venue);
  }
}

$.ajax({
  url: backEndUrl+'/reports/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      insertReportData(reply);
      reportData = reply;
    }
    console.log(reply);
  }
});
