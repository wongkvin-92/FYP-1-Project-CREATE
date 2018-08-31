
//timetable.addLocations(['SR2.2', 'SR2.3', 'SR2.1', 'SR1.1']);

//timetable.addEvent('BIT203', 'SR2.2',
//		   new Date(2015,7,17,10,45),
//		   new Date(2015,7,17,12,30));
/*$.ajax({
    method: 'get',
    url: "/fypBackEnd/timetable/15-04-2018/",
    dataType: 'json',
    success: function(r){
	     timetable.addLocations(r.venues);
	var classes = r.classes;
	for(var i=0; i<classes.length; i++){
	    var c=classes[i];

      var s = c.start.split(" ")[0].split("-").concat(c.start.split(" ")[1].split(":"));
      var e = c.end.split(" ")[0].split("-").concat(c.end.split(" ")[1].split(":"));

      timetable.addEvent(c.code, c.venue,
	    		   new Date(s[0], s[1], s[2], s[3], s[4]),
	    		   new Date(e[0], e[1], e[2], e[3], e[4])
           );

	}
	var renderer = new Timetable.Renderer(timetable);
	renderer.draw(".timetable");
    }
});*/


function refreshTimeTable(){
  timetableController.showDate(moment(new Date).format(timetableController.timetableDateFormat));
}


var timetable = new Timetable();
timetable.setScope(8, 18);

var timetableController = {
  timetableDateFormat: "DD-MM-YYYY",
  showDate: function(e){
    $.ajax({
        method: 'get',
        url: "/fypBackEnd/timetable/"+e+"/",
        dataType: 'json',
        success: function(r){
          timetable = new Timetable();
          timetable.setScope(8, 18);

    	     timetable.addLocations(r.venues);
    	var classes = r.classes;
      if(classes.length == 0){
        timetableController.alert("No classes on this date");
      }else{
        $('#timetable_alert').html("");
      }
    	for(var i=0; i<classes.length; i++){
    	    var c=classes[i];

          var s = c.start.split(" ")[0].split("-").concat(c.start.split(" ")[1].split(":"));
          var e = c.end.split(" ")[0].split("-").concat(c.end.split(" ")[1].split(":"));

          timetable.addEvent(c.code, c.venue,
    	    		   new Date(s[0], s[1], s[2], s[3], s[4]),
    	    		   new Date(e[0], e[1], e[2], e[3], e[4])
               );

    	}
    	var renderer = new Timetable.Renderer(timetable);
    	renderer.draw(".timetable");
        }
    });
  },
  refresh: function(){
    var renderer = new Timetable.Renderer(timetable);
    renderer.draw(".timetable");
  },

  alert: function(msg){
    $('#timetable_alert').html(newSuccessAlert(msg));
    setTimeout(function(){
      disappearMsg();
    }, 2000);
  }
};

timetableController.showDate(moment(new Date).format(timetableController.timetableDateFormat));
var timeTableData = null;

$(document).ready(function(){
  var dateTimeInput = $(`<input id="timetable-date" name="timetable-datetime" type="text"/>`);
  dateTimeInput.datetimepicker({
    defaultDate: moment(new Date()),
    format: 'DD-MM-YYYY'
    /*onSelect: function(s){
      timetableController.showDate(s);
    }*/
  }).on('dp.change', function(date){
    timetableController.showDate(date.date.format(timetableController.timetableDateFormat));
  });

  $('#timetable-input').html(dateTimeInput);
});

//var renderer = new Timetable.Renderer(timetable);
//renderer.draw(".timetable");
