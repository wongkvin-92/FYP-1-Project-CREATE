
var backEndUrl='/fypBackEnd';

var subjectList= [];

/*
 * To assign the table fields and textbox value to variables
 */
var idField = $('#id-field'),
    codeField = $('#code-field'),
    nameField = $('#name-field'),
    lecturerField = $('#lecturer-field'),
    addBtn = $('#add-btn'),
    removeBtns = $('.remove-item-btn'),
    editBtns = $('.edit-item-btn');

var isEditing = false,
    tempNameValue = "",
    tempDataValue = "",
    tempVenueValue = "",
    tempTypeValue = "",
    tempDateTimeValue = "";

//reply.lecturerName = json object
$.ajax({
  url: backEndUrl+'/lecturers/',
  method: 'GET',
  dataType: 'json',
  success: function(reply){
    for(var i=0; i<reply.length; i++){
      d = reply[i];
      var tag = `
          <option value="`+d.lecturerID+`">`+d.lecturerName+`</option>
        `;
        $('#lecturer-field').append($(tag));
    }

  }
});


/*
 * Add subjects
*/
addBtn.click(function(){
  var data = {
    id: subjectList.length+1,
    code: codeField.val(),
    name: $('#name-field').val(),
    lecturer: $('#lecturer-field').val()
  };

  $.ajax({
    url : backEndUrl+'/subjects/',
    method : 'POST',
    dataType : 'json',
    data : {
      'code' : data.code,
      'name' : data.name,
      'lecturer' : data.lecturer
    },
    success: function(reply){

      var lecName = $('#lecturer-field option:selected').text();
      displayNewSubj(data.id, data.code, data.name, lecName);
        alert(reply.msg);

        //apend option
    },
    error: function(reply){

      reply = reply.responseJSON;
      alert(reply.msg);
    }
  });
  console.log(data);
});

/*
 * To insert Data one by one in the table
 * from function displayNewSubj
 */
function insertData(data){
  $('#subjectData').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewSubj(i, item.subjectID,  item.subjectName, item.lecturerName);

  }
    // to have the data in the table in pagination if exceeds the amount
    // defined in the listPaginate function
    listPaginate();
}

/*
 * To display the subject, lecturer, rooms and lessons
 * info accordingly with the table field
 */
function displayNewSubj(id, code, name, lecturer, venue, type, date, time, duration){
  var newSubjRow =
  `<tr class="listContent" id="subject-`+id+`">
        <td class="id"  style="display:none;">`+id+`</td>
        <td class="code" data-field="code">`+code+`</td>
        <td class="name"  data-field="name">`+name+`</td>
        <td class="lecturer"  data-field="lecturer">`+lecturer+`</td>

        <td class="edit"><button class="btn btn-default btn-sm edit-item-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td class="remove"><button class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-trash-o" aria-hidden="true"></button></td>

      </tr>

      `;
      $('#subjectData').append($(newSubjRow));

}

/*
 * To retrieve subjects list from backend
 * and call function insertData to display subjects data
 */
$.ajax({
  url: backEndUrl+'/subjects/',
  method: 'GET',
  dataType:'json',
  success: function(reply){
    insertData(reply);
    subjectData = reply;
  }
});



// Handles live/dynamic element events, i.e. for newly created edit buttons
$(document).on('click', '.edit-item-btn', function() {
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
      tdVenue.html('<select name="venue" value=""> <option value="' + tdVenueValue + '"> ' + tdVenueValue + ' </option></select>');
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


function printData()
{
   var divToPrint=document.getElementById("subjTable");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}

$('#printMe').on('click',function(){
printData();
})

//pagination
var listPaginate = function(){
(function($){

    var lPaginate = {
        startPos: function(pageNumber, perPage) {
            // determine what array position to start from
            // based on current page and # per page
            return pageNumber * perPage;
        },

        getPage: function(items, startPos, perPage) {
            // declare an empty array to hold our page items
            var page = [];

            // only get items after the starting position
            items = items.slice(startPos, items.length);

            // loop remaining items until max per page
            for (var i=0; i < perPage; i++) {
                page.push(items[i]); }

            return page;
        },

        totalPages: function(items, perPage) {
            // determine total number of pages
            return Math.ceil(items.length / perPage);
        },

        createBtns: function(totalPages, currentPage) {
            // create buttons to manipulate current page
            var p = $('<div class="lPage" />');

            // add a "first" button
            p.append('<span class="lPage-button">&laquo;</span>');

            // add pages inbetween
            for (var i=1; i <= totalPages; i++) {
                // truncate list when too large
                if (totalPages > 5 && currentPage !== i) {
                    // if on first two pages
                    if (currentPage === 1 || currentPage === 2) {
                        // show first 5 pages
                        if (i > 5) continue;
                    // if on last two pages
                    } else if (currentPage === totalPages || currentPage === totalPages - 1) {
                        // show last 5 pages
                        if (i < totalPages - 4) continue;
                    // otherwise show 5 pages w/ current in middle
                    } else {
                        if (i < currentPage - 2 || i > currentPage + 2) {
                            continue; }
                    }
                }

                // markup for page button
                var pageBtn = $('<span class="lPage-button page-num" />');

                // add active class for current page
                if (i == currentPage) {
                    pageBtn.addClass('active'); }

                // set text to the page number
                pageBtn.text(i);

                // add button to the container
                p.append(pageBtn);
            }

            // add a "last" button
            p.append($('<span class="lPage-button">&raquo;</span>'));

            return p;
        },

        createPage: function(items, currentPage, perPage) {
            // remove pagination from the page
            $('.lPage').remove();

            // set context for the items
            var container = items.parent(),
                // detach items from the page and cast as array
                items = items.detach().toArray(),
                // get start position and select items for page
                startPos = this.startPos(currentPage - 1, perPage),
                page = this.getPage(items, startPos, perPage);

            // loop items and readd to page
            $.each(page, function(){
                // prevent empty items that return as Window
                if (this.window === undefined) {
                    container.append($(this)); }
            });

            // prep pagination buttons and add to page
            var totalPages = this.totalPages(items, perPage),
                pageButtons = this.createBtns(totalPages, currentPage);

            container.after(pageButtons);
        }
    };

    // stuff it all into a jQuery method!
    $.fn.lPaginate = function(perPage) {
        var items = $(this);

        // default perPage to 5
        if (isNaN(perPage) || perPage === undefined) {
            perPage = 5; }

        // don't fire if fewer items than perPage
        if (items.length <= perPage) {
            return true; }

        // ensure items stay in the same DOM position
        if (items.length !== items.parent()[0].children.length) {
            items.wrapAll('<div class="lPage-items" />');
        }

        // paginate the items starting at page 1
        lPaginate.createPage(items, 1, perPage);

        // handle click events on the buttons
        $(document).on('click', '.lPage-button', function(e) {
            // get current page from active button
            var currentPage = parseInt($('.lPage-button.active').text(), 9),
                newPage = currentPage,
                totalPages = lPaginate.totalPages(items, perPage),
                target = $(e.target);

            // get numbered page
            newPage = parseInt(target.text(), 9);
            if (target.text() == '«') newPage = 1;
            if (target.text() == '»') newPage = totalPages;

            // ensure newPage is in available range
            if (newPage > 0 && newPage <= totalPages) {
                lPaginate.createPage(items, newPage, perPage); }
        });
    };

})(jQuery);
if (items.length > 8)
  $('.lPage-button').hide();
else
  $('.lPage-button').show();
//var l = (items.length > 6)? 6 : items.length;

$('.listContent').lPaginate(8);
}
