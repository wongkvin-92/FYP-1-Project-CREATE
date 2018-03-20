
var backEndUrl='/fypBackEnd';

var subjectList= [];

function insertData(data){
  $('#subjectData').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewSubj(i, item.subjectID,  item.subjectName, item.lecturerName);

  }
   listPaginate();
}

$.ajax({
  url: backEndUrl+'/subjects/',
  method: 'GET',
  dataType:'json',
  success: function(reply){
    insertData(reply);
    subjectData = reply;

  }

});

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



var idField = $('#id-field'),
    codeField = $('#code-field'),
    nameField = $('#name-field'),
    lecturerField = $('#lecturer-field'),
    addBtn = $('#add-btn'),
    editBtn = $('#edit-btn').hide(),
    removeBtns = $('.remove-item-btn'),
    editBtns = $('.edit-item-btn');

// Sets callbacks to the buttons in the list
refreshCallbacks();
/*
addBtn.click(function() {
  contactList.add({
    id: subjectList.length+1,
    code: codeField.val(),
    name: nameField.val(),
    lecturer: lecturerField.val()
  });
  clearFields();
  refreshCallbacks();
});*/

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

    },
    error: function(reply){
      reply = reply.responseJSON;
      alert(reply.msg);
    }
  });

  console.log(data);
});

editBtn.click(function() {
  var item = contactList.get('id', idField.val())[0];
  item.values({
    id:   idField.val(),
    code: codeField.val(),
    name: $('#name-field').val(),
    lecturer: $('#lecturer-field').val()
  });
  clearFields();
  editBtn.show();
  addBtn.hide();
});

function refreshCallbacks() {
  // Needed to add new buttons to jQuery-extended object
  removeBtns = $(removeBtns.selector);
  editBtns = $(editBtns.selector);

  removeBtns.click(function() {
    var itemId = $(this).closest('tr').find('.id').text();
    contactList.remove('id', itemId);
  });

  editBtns.click(function() {
    var itemId = $(this).closest('tr').find('.id').text();
    var itemValues = contactList.get('id', itemId)[0].values();
    idField.val(itemValues.id);
    codeField.val(itemValues.code);
    nameField.val(itemValues.name);
    lecturerField.val(itemValues.lecturer);

    editBtn.show();
    addBtn.hide();
  });
}

function clearFields() {
  codeField.val('');
  nameField.val('');
  lecturerField.val('');
}


function displayNewSubj(id, code, name, lecturer){
  var newSubjRow =
  `<tr class="listContent" id="subject-`+id+`">
        <td class="id" >`+id+`</td>
        <td class="code">`+code+`</td>
        <td class="name">`+name+`</td>
        <td class="lecturer">`+lecturer+`</td>
        <td class="edit"><button class="btn btn-default btn-sm edit-item-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td class="remove"><button class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-trash-o" aria-hidden="true"></button></td>
      </tr>`;
      $('#subjectData').append($(newSubjRow));

}


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
            var currentPage = parseInt($('.lPage-button.active').text(), 10),
                newPage = currentPage,
                totalPages = lPaginate.totalPages(items, perPage),
                target = $(e.target);

            // get numbered page
            newPage = parseInt(target.text(), 10);
            if (target.text() == '«') newPage = 1;
            if (target.text() == '»') newPage = totalPages;

            // ensure newPage is in available range
            if (newPage > 0 && newPage <= totalPages) {
                lPaginate.createPage(items, newPage, perPage); }
        });
    };

})(jQuery);
if (items.length > 9)
  $('.lPage-button').hide();
else
  $('.lPage-button').show();
//var l = (items.length > 6)? 6 : items.length;

$('.listContent').lPaginate(9);
}
