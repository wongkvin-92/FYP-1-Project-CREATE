<!DOCTYPE html>
<html lang="en" >
<?php require 'prevention.php';

?>
<head>
  <meta charset="UTF-8">
  <title>HELPiCT Admin CREATE</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jqc-1.12.3/dt-1.10.16/b-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/kt-2.3.2/datatables.min.css"/>
    <link rel="stylesheet" href="css/homepage.css">




</head>

<body>

<div class="wrapper">
<!-- Dashboard (Parent Block)-->
<div class="dashboard">
  <!-- Dashboard Sidebar (Block)-->
  <div class="dashboard-sidebar">
    <!-- Brand (Element)-->
    <div class="dashboard-sidebar__brand"><img src="logo.png"/></div>
    <!-- Dashboard Nav (Block)-->
    <div class="dashboard-nav">
      <ul>
        <!-- Item:Selected (Element:Modifier)        -->
        <li class="dashboard-nav__item dashboard-nav__item--selected"><a href="home"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
        <!-- Class Replacement (Element)-->
        <li class="dashboard-nav__item "><a href="class_replacement"><i class="fa fa-calendar" aria-hidden="true"></i>Class Rescheduling</a></li>
        <!-- Report (Element)-->
        <li class="dashboard-nav__item"><a href="report"><i class="fa fa-table" aria-hidden="true"></i>Report</a></li>
        <!-- Subject (Element)-->
        <li class="dashboard-nav__item"><a href="lesson"><i class="fa fa-tasks" aria-hidden="true"></i>Lesson</a></li>
      </ul>
    </div>
  </div>
  <!-- Dashboard Content (Block)-->
  <div class="dashboard-content">
    <!-- Dashboard Header (Block)-->

    <!-- Dashboard Content Panel (Element)-->
      <div class="dashboard-content__panel dashboard-content__panel--active" data-panel-id="home">
        <div id="userTitle">
        </div>
        <div class="cardsLayout">
        <ul class="flex cards">
          <li>
            <h2>Welcome to Create!</h2>
            <p class="cardsUser">
              User Name: <span id="uName"></span>
            </p>
          </li>
        </ul>
        <ul class="flex cards">
          <li>
            <h2>No. of Class Rescheduling Request</h2>
            <p>
              10
            </p>
          </li>
        </ul>
      </div>
      <div class="buttonLayout">
        <button class="btnSubmit" id="btnLogout"> logout </button>
      </div>
    </div>

    <!-- Dashboard Content Panel (Home)-->
    <div class="dashboard-content__panel " data-panel-id="class_replacement">
      <div class="dashboard-header">
        <!-- Search (Element)-->
        <div class="dashboard-header__search">
          <input type="search" placeholder="Search..." id="searchReplacement"/>
        </div>
        <!-- New (Element)-->
        <div class="dashboard-header__new"><i class="fa fa-search"></i></div>

      </div>
      <!-- Dashboard List (Block) -->
      <div class="dashboard-list">
        <!-- Dasboard List Item (Element)-->
        <!-- Rows and Ccolumns of the Page Contents-->

        <div class="row inner-row-content" id="reContainer"></div>

      </div>

    </div>


    <!-- Dashboard Content Panel (Element)-->
    <div class="dashboard-content__panel" data-panel-id="report">
      <div class="dashboard-list report-content">
         <div id="report" class="panel panel-primary panel-table  reportTable" style="border:none; ">
           <div class="panel-heading" >
             <div class="row">
               <div class="col col-xs-12">
                 <h3 class="panel-title">Report</h3>
                      <br/><br/><br/>
               </div>
             </div>
            </div>

          <div class="panel-body " >
            <table id="report-table" class="table table-hover"  width="100%" >
              <thead>
                <tr>
                <th>Lecturer</th>
                <th>Subject Code</th>
                <th>Subject Start Time (24h)</th>
                <th>Duration (hours)</th>
                <th>Cancellation Date</th>
                <th>Rescheduling Date</th>
                <th>Resceduling Start Time (24h)</th>
                <th>Venue</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Winson</td>
                <td>BMC 308</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>Anita</td>
                <td>BIT 200</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>Fong</td>
                <td>BIT 210</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>Seetha</td>
                <td>BIT 216</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>Kok</td>
                <td>BIT 208</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>Dewi</td>
                <td>BIT 103</td>
                <td>11:00</td>
                <td>3h</td>
                <td>2018/01/15</td>
                <td>2018/02/25</td>
                <td>12:00</td>
                <td>sr2.3</td>
                <td>Completed</td>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>Dewi</td>
              <td>BIT 103</td>
              <td>11:00</td>
              <td>3h</td>
              <td>2018/01/15</td>
              <td>2018/02/25</td>
              <td>12:00</td>
              <td>sr2.3</td>
              <td>Completed</td>
            </tr>
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
   </div>
  </div>
  </div>
    <!-- Dashboard Content Panel (subject)-->
    <!--
    <div class="dashboard-content__panel " data-panel-id="subject">
      <div class="dashboard-list">
        <div id="subjects" class="panel panel-primary panel-table subject_view table-responsive" style="border:none;">
          <div class="panel-heading">
            <div class="row">
              <div class="col-md-4 col-xs-6">
                <h3 class="panel-title">Subject</h3>
                <br/>
                <table >
                  <td class="code">
                    <input type="hidden" id="id-field" />
                    <input type="text" id="code-field" placeholder="Code" />
                  </td>
                  <td class="name">
                    <input type="text" id="name-field" placeholder="Name" style="margin-left:10px;"/>
                  </td>
                  <td class="lecturer">
                    <select  id="lecturer-field" style="margin-left:10px;">
                      <option value="" >Lecturer</option>
                    </select>
                  </td>
                  <td class="add" style="margin-left:10px;">
                    <button class="btn btn-default btn-sm" id="add-btn" style="margin-left:10px;">Add</button>
                  </td>
                </table>

                    <button id="printMe">Print me</button>
              </div>
            </div>
          </div>
          <table class="table  table-hover subjListTb "  id="subjTable" width="100%">
               <thead>
                 <tr>
                   <div class="pull-right searchBar">
                     <input  class="search" placeholder="Search contact" />
                   </div>
                 <tr>
                   <th >Code</th>
                   <th>Subj Name</th>
                   <th>Lecturer Name</th>
                   <th >Venue</th>
                   <th>Type</th>
                   <th colspan="1">Date Time</th>
                   <th>Duration</th>
                   <th colspan="2"> actions </th>
                 </tr>
               </thead>
               <tbody  id="subjectData">
               </tbody>
             </table>
           </div>
         </div>
       </div>
     -->
       <!-- Dashboard Content Panel (lesson)-->
       <div class="dashboard-content__panel " data-panel-id="lesson">
         <div class="dashboard-list">
           <div id="lessons" class="panel panel-primary panel-table list_view table-responsive lessonPage" style="border:none; ">
             <div class="panel-heading">
               <h3 class="panel-title">Subject</h3>
               <br/>
               <div id ="remove-me" class="row table-responsive borderline-style1">
                 <table>
                   <td>
                     <select class="s2" id="subjCode-field">
                       <option value="">Subject Code</option>
                     </select>
                   </td>
                   <td>
                     <input type="text" id="subjectName-field" placeholder="Subject Name" style="margin-left:10px;" />
                     <!-- <input type="text" id="lecturer_name" placeholder="Lecturer Name" style="margin-left:10px;" /> -->
                   </td>
                   <td>
                     <select  id="lecturerName-field" style="margin-left:10px;">
                       <option value="">Lecture Name</option>
                     </select>
                   </td>
                 </table>
               </div>
               <br/>
               <h3 class="panel-title">Lessons</h3>
               <br/>
               <div class="row table-responsive lesson-input_outline borderline-style">
                 <table >
                     <td class="venue">
                        <input type="text" id="venue-field" placeholder="Venue" />
                     </td>
                     <td class="type">
                       <select  id="type-field" style="margin-left:10px;">
                         <option value="lecture1" >lecture1</option>
                         <option value="lecture2" >lecture2</option>
                         <option value="tutorial1" >tutorial1</option>
                         <option value="tutorial2" >tutorial2</option>
                       </select>
                     </td>
                     <td class="date">
                       <input type="date" id="date-field" style="margin-left:10px;"/>
                     </td>
                     <td class="time">
                       <input type="time" id="time-field" style="margin-left:10px;"/>
                     </td>
                     <td class="duration">
                       <input type="text" id="duration-field" placeholder="Duration" style="margin-left:10px;"/>
                     </td>
                     <td class="add" style="margin-left:10px;">
                       <button class="btn btn-default btn-sm" id="add-btn" style="margin-left:10px;">Add</button>
                     </td>
                 </table>
               </div>
             </div>

             <div class="panel-body ">
                <table class="table  table-hover subjListTb "  width="100%" id="lesson_datatable">
                  <thead>
                    <tr>
                      <div class="pull-right searchBar">
                        <input  class="search" placeholder="Search contact" />
                      </div>
                    </tr>
                    <tr>
                      <th colspan="1">Venue</th>
                      <th colspan="1">Type</th>
                      <th colspan="1">Lecturer</th>
                      <th colspan="1">Date Time</th>
                      <th colspan="1">Duration</th>
                      <th colspan="1">Subject</th>
                      <th class="action-title" colspan="1" > Actions </th>
                    </tr>
                  </thead>
                  <tbody  id="lessonTable">
                  </tbody>
               </table>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>


  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jqc-1.12.3/dt-1.10.16/b-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/kt-2.3.2/datatables.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  dbg = $('#report-table').DataTable( {
    "pagingType": "full_numbers",
   "paging": true,
   "lengthMenu": [10, 25, 50, 75, 100],
    "dom": 'Bfrtip',
    "scroller":       false,
    "buttons": [
        'pageLength','copy', 'csv', 'excel', 'pdf', 'print'
      ]

    } );

  } );
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script  src="js/homepage.js"></script>
<script src='js/classRescheduling.js'></script>
<script src='js/lesson.js'></script>

</body>

</html>
