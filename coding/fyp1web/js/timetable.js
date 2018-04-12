var timetable = new Timetable();
timetable.setScope(9, 5);


//timetable.addLocations(['SR2.2', 'SR2.3', 'SR2.1', 'SR1.1']);

//timetable.addEvent('BIT203', 'SR2.2',
//		   new Date(2015,7,17,10,45),
//		   new Date(2015,7,17,12,30));
$.ajax({
    method: 'get',
    url: "/fypBackEnd/timetable/12-04-2018/",
    dataType: 'json',
    success: function(r){
	timetable.addLocations(r.venues);
	var classes = r.classes;
	for(var i=0; i<classes.length; i++){
	    var c=classes[i];
	    timetable.addEvent(c.code, c.venue,
	    		   new Date(c.start),
	    		   new Date(c.end));	    
	}
	var renderer = new Timetable.Renderer(timetable);
	renderer.draw(".timetable");
    }
});

//var renderer = new Timetable.Renderer(timetable);
//renderer.draw(".timetable");


