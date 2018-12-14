var backEndUrl = '/fypBackEnd';
var checkEditing = false;

$.ajax({
  url: backEndUrl+'/admin/',
  method: 'GET',
  dataType:'json',
  success: function(reply){
    $('#uName').html(reply.adminName);
}
});

$("#btnLogout").on("click", function(){
    $.ajax({
      url: backEndUrl+'/admin/logout/',
      method: 'GET',
      dataType:'json',
      success: function(reply){
      alert(reply.msg);
      window.location = "index.php";
    }
    });
});


$.ajax({
  url: backEndUrl+'/classes/pending/',
  method: 'GET',
  dataType: 'json',
  success: function(d){
    //items = d;
    for(var i = 0; i < d.length; i++){
      var item = d[i];
      items[item.id] = item;
    }
    console.log(items);
    addData(items);
  }
});

//insert Data
function newReCard(item){
    return createReCard(item.subjectCode, item.subjectName, item.oDate, item.type, item.reDate, item.reTime, item.duration, item.id, item.venue);
}

//create structure of the card
function createReCard(subjectCode, subjectName, oDate, type, rDate, rTime, duration, id, venue){
  var replacementCard = `
    <div class="content-layout" id="approval-request-`+id+`">
      <div style="padding-bottom:10px; position:relative; z-index:9999">
        <span class="left"></span>
        <button class="btn btn-default btn-sm pull-right btnEdit" onClick="editBtnClicked(`+id+`)">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </button>
      </div>
      <div style="position:relative; z-index:1000; top:-16px; left:20px">
        <h3><span class="left">Code:</span> <span class="right" id="subjectCode"></span></h3>
        <p class="class-reschedule_subject"><span class="left">Subject:</span> <span class="right" id="subjectName"></span></p>
        <p><span class="left">Type:</span> <span class="right" id="type"></span></p>
        <p><span class="left">Ori-Date:</span> <span class="right" id="oDate"></span></p>
        <p><span class="left">Duration:</span><span class="right" id="duration"></span></p>
        <p  style="color: blue;"><span class="left">Re-Date:</span> <span class="right date" id="reDate"></span></p>
        <p style="color: blue;"><span class="left">Re-Time:</span> <span class="right time" id="reTime"></span></p>
        <p style="color: blue;"><span class="left">Venue:</span> <span class="right venueApprove" >`+venue+`</span></p>
        <!--
        <p><span class="left">Venue:</span>
          <span class="right">
            <input type="text" name="venue" placeholder="Class Venue" id="venue" value="`+venue+`"  required/ size="14" disabled>
          </span>
        </p>
        -->
        <div class="btn-style">
          <!--<p><a class="btn btn-primary venueBtn" role="button">Check Venue &raquo;</a></p>-->
          <p><a class="btn btn-primary btn-style2 approveBtn" role="button" onClick="approveClass(`+id+`)">Approve &raquo;</a></p>
        </div>
        </div>
    </div>`;

  //jQueryselector
  var jqs = $(replacementCard);

  jqs.find("#subjectCode").html(subjectCode);
  jqs.find("#subjectName").html(subjectName);
  jqs.find("#type").html(type);
  jqs.find("#oDate").html(oDate);
  jqs.find("#reDate").html(rDate);
  jqs.find("#reTime").html(rTime);
  jqs.find("#duration").html(duration +"hour(s)");

  return jqs;
}

function addData(itemArray){
   $('#reContainer').html(""); //clear the container
   for(var i=0; i< items.length; i++){
     var item = itemArray[i];
     if(item === undefined)
        continue;
     var card = newReCard(item);
     $('#reContainer').append(card);
   }
}

function findData(items, query){
  var result = [];
  items.forEach( (el) => {
    if(el.subjectCode.match(query.toLowerCase())!=null){
      result.push(el);
    }
  });
  return result;
}

function approveClass(id){
  console.log(id);
  //console.log($('.venueApprove')[0].childNodes[0].nodeValue);
    var myVar = $(this).find('.venueApprove').val();
    console.log(myVar);
    //$('.venueApprove')[0].childNodes[0].nodeValue
      if ($('.venueApprove').val() == "NA"){
        createErrAlert("Please enter a class venue!");
        return;
      }else{
        if (confirm('Are you sure you approve this Class Reschedulement?')) {
            $.ajax({
                url : backEndUrl+"/classes/"+id+"/approve/",
                method : "GET",
                dataType : "json",
                success : function(r) {
                  removeClass(id);
                  createSucAlert(r.msg);

                },
        	error: function(r){
        	    r = r.responseJSON;
        	    console.log(r);
        	}
            });
        } else {
            return;
        }
    }
}

var editBtnClicked = function(id) {
/*
  console.log(this);
    var el = $(this).parent().parent()[0];
  console.log(el);
  */
  var item = items[id];
  prevItem = JSON.stringify(item);
var subjectBox = $('#approval-request-'+id);

  var subjCard = `
      <div style="padding-bottom:10px; position:relative; z-index:9999">
        <span class="left"></span>
        <button class="btn btn-default btn-sm pull-right btnSave" onClick="goBackViewMode(`+id+`)">
          <i class="fa fa-close" aria-hidden="true"></i>
        </button>
      </div>
      <div style="position:relative; z-index:1000; top:-15px;">
        <h3 ><span class="left">Code:</span> <span class="right" id="subjectCode">`+item.subjectCode+`</span></h3>
        <p ><span class="left">Subject:</span> <span class="right" id="subjectName">`+item.subjectName+`</span></h5>
        <p ><span class="left">oDate:</span> <span class="right" id="oDate">`+item.oDate+`</span></h5>
                  <p ><span class="left">Duration:</span> <span class="right" id="duration">`+item.duration+`</span></p>
        <div class="redateBox">
          <p ><span class="left redateBox">Re-Date:</span> <span class="right date" id="reDate"><input id="newDate" type="date" name="datechanged" value="`+item.reDate+`" /></span></p>
          <p ><span class="left">Re-Time:</span> <span class="right time" id="reTime"><input id="newTime" type="time" name="timechanged" value="`+item.reTime+`" /></span></p>

          <p><span class="left">Venue:</span> <span class="right"><input id="newVenue"  type="text" name="venuechanged"  value="`+item.venue+`" required style="width:52px;" /></span></p>
          <div class="btn-style">
            <p><a class="btn btn-primary btn-style2 approveBtn" role="button" onClick="saveBtn(`+id+`)">Save &raquo;</a></p>
          </div>
        </div>
      </div>`;
   if(!checkEditing){
      subjectBox.html(subjCard);
      checkEditing = true;
    }
 };

 function goBackViewMode(id){
   checkEditing = false;
   var subjCard = newReCard(items[id]);
   var subjectBox = $('#approval-request-'+id);
   subjectBox.html(subjCard.html());
 }

/*
 * Update the record in the server
 */
var saveRecheduling = function(id ,date, time, venue){
  var newItem = items[id];
  newItem.reDate = date;
  newItem.reTime = time;
  newItem.venue = venue;
  var currentItem = JSON.stringify(newItem);
  if(currentItem==prevItem){
    createErrAlert("No changed detected");
    goBackViewMode(id);
  }else{
      $.ajax({
         "async": true,
         "crossDomain": true,
         "url": backEndUrl + "/requests/rescheduling/"+id+"/",
         "method": "PATCH",
         "processData": false,
         "dataType": "json",
         "data": "{\n\t\"date\": \""+date+"\",\n\t\"venue\": \""+venue+"\",\n\t\"time\": \""+time+"\"\n}",
         "success": function(response){
             items[id].reDate = date;
             items[id].reTime = time;
             items[id].venue = venue;
             goBackViewMode(id);
             createSucAlert(response.msg);
         },
         "error": function(r){
           r = r.responseJSON;
           createErrAlert(r.msg);
         }
      });
    }
}

/*
 * Save edited data and validate them
 */
var saveBtn = function(id){
  var    regexVenue = /^(sr[2]\.[1-3])$|(lh[2]\.[1-3])$|(ls[1-2])$|(tis)$/;
  var venue = $('#newVenue').val().toLowerCase(),
      date = $('#newDate').val(),
      time = $('#newTime').val();
  if (venue == "" || venue== "na"){
    createErrAlert("Please enter a class venue!");
    return;
  }else if(!(regexVenue.test(venue))){
    createErrAlert(venue + " is invalid!");
        return;
  }else{
    saveRecheduling(id, date, time, venue);
    return;
  }
}

/*
 * Delete Record
 */
function removeClass(id){
  $('#approval-request-' + id).remove();
}



$('#searchReplacement').on('change keyup paste',function(){
  var jqs = $(this);
  var query = jqs.val();

  //if find item words length more than 3 only search
  if (query.length > 3){

         addData(findData(items, query));
  }
  else{
        addData(items);
  }
});

var prevItem;
var items = [];
addData(items);


var newWarnAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newSuccAlert = function(msg){
 return `<div class="alert alert-success">
    `+msg+`
</div>`;
}

var newErrAlert = function(msg){
 return `<div class="alert alert-danger">
    `+msg+`
</div>`;
}

var newInfAlert = function(msg){
  return `    <div class="alert alert-info">
      `+msg+`
  </div>`
}

var disapMsg = function(){
    $('#general_msg').html('');
}

var createSucAlert = function(msg){
  $('#general_msg').html(newSuccAlert(msg));
  setTimeout(function(){
    disapMsg();
  }, 5000);
}
var createWarAlert = function(msg){
  $('#general_msg').html(newWarnAlert(msg));
  setTimeout(function(){
    disapMsg();
  }, 5000);
}

var createErrAlert = function(msg){
  $('#general_msg').html(newErrAlert(msg));
  setTimeout(function(){
    disapMsg();
  }, 5000);
}

var createInfAlert = function(msg){
  $('#general_msg').html(newInfAlert(msg));
  setTimeout(function(){
    disapMsg();
  }, 5000);
}
