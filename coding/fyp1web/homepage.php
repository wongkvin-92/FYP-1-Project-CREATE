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
    <link rel="stylesheet" href="bower_components/timetable.js/dist/styles/plugin.css">
    <link rel="stylesheet" href="bower_components/jqueryui-datepicker/datepicker.css">
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
          <li class="dashboard-nav__item"><a href="timetable"><i class="fa fa-tasks" aria-hidden="true"></i>Timetable</a></li>
          <li class="dashboard-nav__item"><a href="semester"><i class="fa fa-tasks" aria-hidden="true"></i>Semester</a></li>
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
          <ul class="flex cards ">
            <div class="horizontal_cards">
            <li >
              <h2>Welcome to Create!</h2>
              <p class="cardsUser">
                User Name: <span id="uName"></span>
              </p>
            </li>
            <li >
              <h2>No. of Rescheduling Request</h2>
              <p id="countRequest">

              </p>
            </li>
          </div>
            <div class="horizontal_cards" >
            <li  >
              <h2>Today's Class Cancellation</h2>
              <table class="table table-hover table-responsive" >
              <thead >
                <tr>
                  <th >Subject</th>
                  <th>Lecturer</th>
                  <th  >Time</th>
                  <th>Type</th>
                  <th>Venue</th>
                </tr>
              </thead>
              <tbody  id="cancellation_class">
              </tbody>
            </table>
            </li>
            <li >
              <h2>Today's Class Replacement</h2>

                  <table class="table table-hover table-responsive" >
                  <thead >
                    <tr>
                      <th>Subject</th>
                      <th>Lecturer</th>
                      <th>Time</th>
                      <th>Type</th>
                      <th>Venue</th>
                    </tr>
                  </thead>
                  <tbody  id="replaced_class">
                  </tbody>
                </table>

            </li>
          </div>
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
        <div id="general_msg"></div>
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
           <div id="report" class="panel panel-primary panel-table table-responsive reportTable" style="border:none; ">
             <div class="panel-heading" >
               <div class="row">
                 <div class="col col-xs-12">
                   <h3 class="panel-title">Report</h3>
                        <br/><br/><br/>
                 </div>
               </div>
              </div>

            <div class="panel-body " >
              <table class="table table-hover"  width="100%" id="report_datatable">
                <thead>
                  <tr>
                  <th>id</th>
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

                <tbody id="reportTable">
                </tbody>
              </table>
            </div>
     </div>
    </div>
    </div>
         <!-- Dashboard Content Panel (lesson)-->
         <div class="dashboard-content__panel " data-panel-id="lesson">
           <div class="dashboard-list">
             <div id="lessons" class="panel panel-primary panel-table list_view table-responsive lessonPage" style="border:none; ">
               <div class="panel-heading">
                 <h3 class="panel-title">Subject</h3>
                 <br/>
                 <div id ="remove-me" class="row table-responsive borderline-style1">
                   <table>
                     <tr>
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
                         <option value="">Lecturer Name</option>
                       </select>
                     </td>
                     <td>
                       <input
                        type="file" name="file" id="file_subject_csv" accept=".csv">
                        <button class="btn btn-default btn-sm" id="import_subject" name="import_subject">Import</button>

                     </td>
                   </tr>
                   </table>
                 </div>
                 <br/>

                 <h3 class="panel-title">Lessons</h3>
                 <br/>
                 <div class="row table-responsive lesson-input_outline borderline-style">
                   <table>
                       <td class="venue">
                          <input type="text" id="venue-field" placeholder="Venue" />
                       </td>
                       <td class="type">
                         <select  id="type-field" style="margin-left:10px;">
                           <option value="">Class Type</option>
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
                       <td>
                         <input
                          type="file" name="file" id="file_lesson_csv" accept=".csv">
                          <button class="btn btn-default btn-sm" type="submit" id="import_lesson" name="import_lesson">Import</button>
                       </td>
                   </table>
                 </div>
               </div>

               <div class="panel-body table-responsive">
                     <div id="lesson_table_msg"></div>
                  <table class="table  table-hover subjListTb "  width="100%" id="lesson_datatable">
                    <thead>
                      <tr >

                        <th colspan="1">Venue</th>
                        <th colspan="1">Type</th>
                        <th colspan="1">Lecturer</th>
                        <th colspan="1" id="dayTimeCss">Day Time</th>
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
         <!-- Dashboard Content Panel (Element)-->
         <div class="dashboard-content__panel" data-panel-id="timetable">
          <div class="dashboard-list">
            <div class="panel panel-primary panel-table list_view table-responsive lessonPage" style="border:none; ">
              <div class="panel-heading">
                <h3 class="panel-title">Daily Timetable</h3>

          </div>
          <div id="timetable-input" style="position:relative; margin-top:1em; color: "black";">
          </div>
          <div style="display:block; margin-top:2.5em; ">
           <div id="timetable_alert"></div>
         </div>
            <div class="panel-body table-responsive">
              <div class="timetable">
              </div>
             </div>
              </div>
           </div>
         </div>
         <!-- Dashboard Content Panel (lesson)-->
         <div class="dashboard-content__panel " data-panel-id="semester">
          <div class="dashboard-list ">
            <div class="cardsLayout">

            <ul class="flex cards" id="set-semester">
              <li >
                <h2>Semester Dates</h2>
                <div class="panel panel-table start_end_date table-responsive ">
                  <div class="panel-heading">
                    <div  class="row table-responsive borderline-style1">
                         <table>
                              <tr>
                                <td>
                                  <h4 style="margin-bottom: 5px; color: black; font-weight: bold">SEM Start Date</h4>
                                  <input type="date" id="start-sem-day" />
                                </td>

                                <td>
                                  <div style="margin-left:20px;">
                                    <h4 style="margin-bottom: 5px; color: black; font-weight: bold">SEM End Date</h4>
                                    <input type="date" id="end-sem-day" />
                                  </div>
                                </td>

                              </tr>
                              <tr>
                                <td>
                                <div>
                                  <button class="btnSubmit" id="submitSemdate" > submit </button>
                                </div>
                              </td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </li>
            </ul>

            </div>
          </div>
         </div>
       </div>
     </div>
 </div>


  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>

  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>


<!--
<script type="text/javascript">
$(document).ready(function() {
  dbg = $('#report_datatable').DataTable( {
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
</script>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script  src="js/homepage.js"></script>
<script src='js/classRescheduling.js'></script>
<script src='js/lesson.js'></script>
<script src='js/report.js'></script>
<script src="bower_components/timetable.js/dist/scripts/timetable.js"></script>
<script src="bower_components/jqueryui-datepicker/datepicker.js"></script>
<script src='js/timetable.js'></script>

</body>

</html>
