<?php
session_start();
  if(!isset($_SESSION['credentials']))
  {
    print ("
    <script>
      alert(\"You must log in!\");
      window.location = 'index.php';
    </script>
  ");
  
  function objectToObject($instance, $className) {
      return unserialize(sprintf(
          'O:%d:"%s"%s',
          strlen($className),
          $className,
          strstr(strstr(serialize($instance), '"'), ':')
      ));
  }
  $obj = $_SESSION['credentials']['user'];

  $admin = objectToObject($obj, 'User');
   exit();
  }
?>
