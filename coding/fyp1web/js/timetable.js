let timeTableData = null;

let downloadTimeTable = e => $.ajax({
    url: "/fypBackEnd/admin/all/schedule/",
    method: "GET",
    success: e
});


function refreshTimeTable(){
    //timetableController.showDate(moment(new Date).format(timetableController.timetableDateFormat));
    timetableController.showDate(moment(new Date));
}


var timetable = new Timetable();
timetable.setScope(8, 18) //; only whole hours between 8 and 18

var timetableController = {
    timetableData: null,
    venues: [],
    timetableDateFormat: "DD-MM-YYYY",
    fetchPermanantClasses: (day, timetabledata)  =>
	timetabledata.filter(e => e.day == day &&
				  e.oldDateTime == null &&
			     e.newDateTime == null),
    fetchCancelledClasses: (date, timetabledata) =>
	timetabledata.filter(e => e.oldDateTime != null && e.oldDateTime == date),
    fetchReplacementClasses: (date, timetabledata) =>
	timetabledata.filter(e => e.newDateTime != null && e.status == "approved" && e.newDateTime == date),
    showDate: function(dateStr){
	var timetable = new Timetable();
	timetable.setScope(8, 18);
	timetable.addLocations(this.venues);

	if(this.timetableData){
	    let current_day = dateStr.format("dddd");
	    let current_date = dateStr.format("YYYY-MM-DD");
	    let permanantClasses = this.fetchPermanantClasses(current_day, this.timetableData);
	    let cancelledClasses = this.fetchCancelledClasses(current_date, this.timetableData);
	    let replacementClass = this.fetchReplacementClasses(current_date, this.timetableData);

	    let cancelledClassIDs = cancelledClasses.map(el => el.classID);
	    console.log("Replacement classes", replacementClass);

	    let addClasses = item => {
    		let cdt = dateStr.format(this.timetableDateFormat).split("-");

    		//This is to fix a bug because we had issues with new DateTime("yyy-mm-dd hh:m:s")
    		let s = item.startTime.split(":");
    		let e = item.endTime.split(":");
    		let startDateTime = new Date(cdt[2], cdt[1], cdt[0], s[0], s[1], 0);
    		let endDateTime = new Date(cdt[2], cdt[1], cdt[0], e[0], e[1], 0);
		let isReplacement = item.newDateTime != null;
		let isCancelled = cancelledClassIDs.indexOf(item.classID) != -1;
		let classType = !isCancelled?'permenantClassEvent':'cancelledClassEvent';
		if(isReplacement)
		    classType = "replacementClassEvent";

    		var options = {
    		    url: '#',
    		    class: classType,
    		    data: item,
    		    onClick: function(event, timetable, clickEvent) {

              alert(item.subjectName);
            } // custom click handler, which is passed the event object and full timetable as context
    		};

    		timetable.addEvent(item.subjectID, item.newVenue,
    				   startDateTime,
    				   endDateTime,
    				   options
    				  );
	    };

	    //add all permanant classes
	    permanantClasses.forEach(addClasses );

	    //add all cancelled classes
	    replacementClass.forEach(addClasses);

	    console.log("Permenant classes for ", current_day, permanantClasses);
	}


	let renderer = new Timetable.Renderer(timetable);
	renderer.draw(".timetable");


      /*$.ajax({
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
*/
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

//timetableController.showDate(moment(new Date).format(timetableController.timetableDateFormat));
//var timeTableData = null;

$(document).ready(downloadTimeTable(function(tdata){
    timetableController.timetableData = JSON.parse(tdata);
    timetableController.venues = timetableController.timetableData
	.map(e => e.newVenue)
	.filter( (e, i, self) => self.indexOf(e) == i && e != "") ;

    var dateTimeInput = $(`<input id="timetable-date" name="timetable-datetime" type="text"/>`);

    dateTimeInput.datetimepicker({
	defaultDate: moment(new Date()),
	format: 'DD-MM-YYYY'
	/*onSelect: function(s){
	  timetableController.showDate(s);
	  }*/
    }).on('dp.change', function(date){
	//timetableController.showDate(date.date.format(timetableController.timetableDateFormat));
	timetableController.showDate(date.date);
    });

    $('#timetable-input').html(dateTimeInput);
}));


//var renderer = new Timetable.Renderer(timetable);
//renderer.draw(".timetable");
