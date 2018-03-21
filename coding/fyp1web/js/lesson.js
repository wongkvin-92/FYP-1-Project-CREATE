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
});

function insertLessonData(data){
  $('#lessonTable').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewLesson( item.venue,  item.type, item.lecturer,item.lecturer,item.lecturer);

  }
}

function displayNewLesson(venue, type, lecturer, date, time, duration, subject){
  var newLessonRow =
  `<tr class="listContent">
        <td class="venue" data-field="venue">`+venue+`</td>
        <td class="lecturer"  data-field="type">`+type+`</td>
        <td class="lecturer"  data-field="lecturer">`+lecturer+`</td>
        <td class="datetime"  data-field="date">`+date+ " "+time +`</td>
        <td class="duration"  data-field="subject">`+subject+`</td>
        <td class="edit"><button class="btn btn-default btn-sm edit-item-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td class="remove"><button class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-trash-o" aria-hidden="true"></button></td>
      </tr>

      `;
      $('#lessonTable').append($(newLessonRow));
}
