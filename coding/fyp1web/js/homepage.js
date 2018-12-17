var backEndUrl='/fypBackEnd';

(function() {
  $('.dashboard-nav__item').on('click', function(e) {
    var itemId;
    e.preventDefault();
    $('.dashboard-nav__item').removeClass('dashboard-nav__item--selected');
    $(this).addClass('dashboard-nav__item--selected');
    itemId = $(this).children().attr('href');
    $('.dashboard-content__panel').hide();
    $('.dashboard-content__panel[data-panel-id=' + itemId + ']').show();
    if(itemId == "timetable")
      refreshTimeTable();
    return console.log(itemId);
  });
}).call(this);



$('#submitSemdate').on('click', ()=> {
  var startSemDay = $('#start-sem-day').val();
  var endSemDay = $('#end-sem-day').val();

  /**
  * do validation here
  **/
    $.ajax({
      url:backEndUrl + "/admin/semester/",
      method: "POST",
      dataType: "json",
      data: JSON.stringify({
        'startDate' : startSemDay,
        'endDate' : endSemDay
      }),
      success: function(a){
        alert("successfully created");
        //newSuccessAlert("Successfully added!");
      },
    error: function(reply){
        reply = reply.responseJSON;
        newErrorAlert(reply.msg);
    }
  });

});


$.ajax({
  url:backEndUrl + "/classes/pending/count/",
  method: "GET",
  dataType: "json",
  success: function(a){
    $('#countRequest').html(a.count);
  }
});

$.ajax({
  url:backEndUrl + "/semester/",
  method: "GET",
  dataType: "json",
  success: function(a){
    $('#start-sem-day').val(a.start_date);
    $('#end-sem-day').val(a.end_date);
  }
});



function addCancellationClass(cItemArray){
   $('#cancellation_class').html(""); //clear the container
   for(var i=0; i< cancelledClassItems.length; i++){
     var cItem = cItemArray[i];
     if(cItem === undefined)
        continue;
     var card = newCancelledCard(cItem);
     $('#cancellation_class').append(card);
   }
}

$.ajax({
  url: backEndUrl+'/cancellation/today/',
  method: 'GET',
  dataType: 'json',
  success: function(d){
    //items = d;
    for(var i = 0; i < d.length; i++){
      var cItem = d[i];
      cancelledClassItems[cItem.id] = cItem;
    }
    addCancellationClass(cancelledClassItems);
  }
});

//insert Data
function newCancelledCard(cItem){
    return createCancelledCard( cItem.subjectCode, cItem.lecturer, cItem.type,  cItem.oriStartTime, cItem.oriEndTime, cItem.venue);
}
var dateTimeFormat = "HH:mm";
//create structure of the card
function createCancelledCard(subjectCode, lecturer, type, oriStartTime, oriEndTime, venue){
  var oriStime =   moment(oriStartTime, 'HH:mm:ss').format('HH:mm');
  var oriEtime =   moment(oriEndTime, 'HH:mm:ss').format('HH:mm');

  var cancelledCard = `

    <tr >

      <td ><h5 id="subjectCodeCancelled"></h5></td>
      <td><h5 id="lecturerCancelled"></h5></td>
      <td><h5 id="timeCancelled"><span id="oriStartTimeCancelled"></span>`+oriStime+`-<span id="oriEndTime">`+oriEtime+`</span></h5></td>
      <td><h5 id="typeCancelled"></h5></td>

      <td><h5 id="venueCancelled"></h5></td>

    </tr>
    `;

  //jQueryselector
  var ccDisplay = $(cancelledCard);

  ccDisplay.find("#subjectCodeCancelled").html(subjectCode);
  ccDisplay.find("#lecturerCancelled").html(lecturer);
  ccDisplay.find("#typeCancelled").html(type);
  ccDisplay.find("#venueCancelled").html(venue);

  return ccDisplay;
}

function addReplacementClass(rItemArray){
   $('#replaced_class').html(""); //clear the container
   for(var i=0; i< replacedItems.length; i++){
     var rItem = rItemArray[i];
     if(rItem === undefined)
        continue;
     var card = newReplaceCard(rItem);
     $('#replaced_class').append(card);
   }
}


$.ajax({
  url: backEndUrl+'/replacement/today/',
  method: 'GET',
  dataType: 'json',
  success: function(d){
    //items = d;
    for(var i = 0; i < d.length; i++){
      var rItem = d[i];
      replacedItems[rItem.id] = rItem;
    }
    console.log(replacedItems);
    addReplacementClass(replacedItems);
  }
});

//insert Data
function newReplaceCard(replacedItem){
    return createReplacedCard( replacedItem.subjectCode, replacedItem.lecturer, replacedItem.type,  replacedItem.newStartTime, replacedItem.newEndTime, replacedItem.newVenue);
}
var dateTimeFormat = "HH:mm";
//create structure of the card
function createReplacedCard(subjectCode, lecturer, type, newStartTime, newEndTime, newVenue){
  var newStime =   moment(newStartTime, 'HH:mm:ss').format('HH:mm');
  var newEtime =   moment(newEndTime, 'HH:mm:ss').format('HH:mm');

  var replacedCard = `

    <tr >

      <td ><h5 id="subjectCodeReplaced"></h5></td>
      <td><h5 id="lecturerReplaced"></h5></td>
      <td><h5 id="timeReplaced"><span id="oriStartTimeCancelled"></span>`+newStime+`-<span id="oriEndTime">`+newEtime+`</span></h5></td>
      <td><h5 id="typeReplaced"></h5></td>

      <td><h5 id="venueReplaced"></h5></td>

    </tr>
    `;

  //jQueryselector
  var rpcDisplay = $(replacedCard);

  rpcDisplay.find("#subjectCodeReplaced").html(subjectCode);
  rpcDisplay.find("#lecturerReplaced").html(lecturer);
  rpcDisplay.find("#typeReplaced").html(type);
  rpcDisplay.find("#venueReplaced").html(newVenue);

  return rpcDisplay;
}

var cancelledClassItems = [];
var replacedItems = [];

var newWarningAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newSuccessAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newErrorAlert = function(msg){
 return `<div class="alert alert-danger">
    `+msg+`
</div>`;
}

var newInfoAlert = function(msg){
  return `    <div class="alert alert-info">
      `+msg+`
  </div>`
}
