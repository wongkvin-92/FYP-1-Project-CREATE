var backEndUrl='/fypBackEnd';

var options = {
  valueNames: [ 'id', 'code', 'name',  'lecturer' ],

    item: `<tr id="subject-`+id+`">
          <td class="id" style="display:none;">`+id+`</td>
          <td class="code">`+code+`</td>
          <td class="name">`+name+`</td>
          <td class="lecturer">`+lecturer+`</td>
          <td class="edit"><button class="edit-item-btn">Edit</button></td>
          <td class="remove"><button class="remove-item-btn">Remove</button></td>
        </tr>`;

};

var values = $('#subjectData').html("");
for(var i=0; i< data.length; i++){
  var item  = data[i];
  displayNewSubj(i, item.subjectID,  item.subjectName, item.lecturerName);
};

// Init list
var contactList = new List('contacts', options, values);

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

contactList.add({

  insertData();
});

function insertData(data){
  $('#subjectData').html("");
  for(var i=0; i< data.length; i++){
    var item  = data[i];
    displayNewSubj(i, item.subjectID,  item.subjectName, item.lecturerName);

  }
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


addBtn.click(function() {
  var data = {
    id: subjectList.length+1,
    code: codeField.val(),
    name: $('#name-field').val(),
    lecturer: $('#lecturer-field').val()
  };
  contactList.add({
    data;
  });

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

  clearFields();
  refreshCallbacks();
});

editBtn.click(function() {
  var item = contactList.get('id', idField.val())[0];
  item.values({
    id:idField.val(),
    name: nameField.val(),
    age: ageField.val(),
    city: cityField.val()
  });
  clearFields();
  editBtn.hide();
  addBtn.show();
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
  nameField.val('');
  ageField.val('');
  cityField.val('');
}

function displayNewSubj(id, code, name, lecturer){
  var newSubjRow =
  `<tr id="subject-`+id+`">
        <td class="id" style="display:none;">`+id+`</td>
        <td class="code">`+code+`</td>
        <td class="name">`+name+`</td>
        <td class="lecturer">`+lecturer+`</td>
        <td class="edit"><button class="edit-item-btn">Edit</button></td>
        <td class="remove"><button class="remove-item-btn">Remove</button></td>
      </tr>`;
      $('#subjectData').append($(newSubjRow));
}
