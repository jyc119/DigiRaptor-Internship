<?php 

include('../config/db_connect.php');


$errors = array('startdate' => '', 'enddate' => '');

if(isset($_POST['submit'])){

  // check startdate
  if(empty($_POST['startdate'])){
  $errors['startdate'] = 'A start date is required <br />';
  }else{
    $startdate = $_POST['startdate'];
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['startdate'])){
      $errors['startdate'] = 'Start date must be a valid date in form YYYY-MM-DD';
    }
  }

  // check endate
  if(empty($_POST['enddate'])){
  $errors['enddate'] = 'An end date is required <br />';
  }else{
    $enddate = $_POST['enddate'];
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['enddate'])){
      $errors['enddate'] = 'End date must be a valid date in form YYYY-MM-DD';
    }
  }

  $begindate = $_POST['startdate'];
  $enddate = $_POST['enddate'];
  
  function createDateRangeArray($strDateFrom,$strDateTo)
  {
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange = [];

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
  }

  $dates = createDateRangeArray($begindate, $enddate);

}  

?>

<!DOCTYPE html>
<html>

<body>
<style>  
  .navbar {
  overflow: hidden;
  background-color: #333;
  position: fixed;
  top: 0;
  width: 100%;
}

.navbar a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.navbar a:hover {
  background: #ddd;
  color: black;
}
</style>
</body>

<br>
<br>

<head>
<style>

.content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
</head>
<div class="content">
<section class="container grey-text">
  <h4 class="center">Report Page</h4>
  <form class="white" action="report.php" method="POST" enctype="multipart/form-data">

  <input type="hidden" name = "id" value = ""> 

  <!-- Beginning Date -->
  <label>Beginning Date:</label>
  <input type="date" name = "startdate" value = ""> 
  <div style="color:red"><?php echo $errors['startdate']; ?></div>

  <br>
  <br>

  <!-- Ending Date Date -->
  <label>End Date:</label>
  <input type="date" name = "enddate" value = ""> 
  <div style="color:red"><?php echo $errors['enddate']; ?></div>

  <br>
  <br>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="submit">Filter</button>
  </div>

  </form>
</section>  

<table>
<?php 

  if (isset($dates)){
    // Echo the dates
    echo "<tr>";
    echo "<td></td>";
    foreach($dates as $date){
      echo "<td>" . $date. "</td>";
    } 
    echo "</tr>"; 

    // name query

    $sqlname = "SELECT * FROM newstaff"; 
    $nameresults = mysqli_query($conn,$sqlname);
    $names = mysqli_fetch_all($nameresults, MYSQLI_ASSOC);

    foreach($names as $name){
      echo "<tr><td>" .$name['name']. "</td>";
      // query to obtain the start and end time for each date

      foreach($dates as $date){
        // query to obtain the record of the appropriate staff and date
        $sqlTime = "SELECT * FROM attendance WHERE curdate = '$date' AND staffid = '".$name['id']."'";
        $timeRes = mysqli_query($conn,$sqlTime);
        $time = mysqli_fetch_all($timeRes, MYSQLI_ASSOC);

        if (count($time) != 0){
          //FIX THE TIME DIFFERENCE
          $t1 = strtotime(substr($time[0]['check_in'], 11));
          $t2 = strtotime(substr($time[0]['check_out'], 11));
          echo "<br><br>";
          $minsbefore = ($t2 - $t1);
          $hoursbefore = ($t2 - $t1)/3600;   //$hours = 1.7
          $actualtime = floor($hoursbefore) . ':' . ( ($hoursbefore-floor($hoursbefore)) * 60 );
          $hrs = substr($actualtime, 0, 1);
          $mins = floor(substr($actualtime, 2, strlen($actualtime)));
          $totalmins = 60*$hrs + $mins;
          echo $totalmins;
          if($hrs == 0){
            echo "<td>" .$mins. " mins </td>";
          }else{
            echo "<td>" .$hrs. " hrs " .$mins. " mins </td>"; 
          }
          //echo "<td>" . $timediff. "</td>";
        }
        else{
          echo "<td> 0 mins</td>";
        }
      }
      echo "</tr>";

    }
}

?>
</table>
</div>

<div class="navbar">
  <a href="/demo/staff/staffPage.php" class="brand-text">Staff Table</a>
  <a href="/demo/index.php" class="brand-text">Main Page</a>
</div> 

</html> 