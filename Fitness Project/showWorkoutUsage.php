<?php
  // You must implement a page showing workouts over a given time period, including estimated calories
  // burned, and any other relevant information, for each workout. Statistics such as total calories and
  // average calories per workout should be displayed on this page as well.

  // Have the user enter two days that will define a time period.
  // Then we simply show the relevant informationfor each date.
  echo "<h4>Showing a user's workout usage!</h4>";

  echo "<form method=POST>";
    echo "Please enter the first day of the time period in the format YYYY-MM-DD!";
    echo "<input type=text name=WUFirstDate><br />";
    echo "Please enter the last day of the time period in the format YYYY-MM-DD!";
    echo "<input type=text name=WULastDate><br />";
    echo "<input type=submit value='Submit to see workout usage!'>";
  echo "</form>";

  if(!empty($_POST["WUFirstDate"]) && !empty($_POST["WULastDate"])){
    $WUFirstDate = $_POST["WUFirstDate"];
    $WULastDate = $_POST["WULastDate"];
    $sql = "SELECT NAME,DURATION,DATE FROM WORKOUT WHERE DATE >= :WUFD AND DATE <= :WULD;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":WUFD" => "$WUFirstDate", ":WULD" => "$WULastDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
    $rowsWoW = $prepared->fetchAll(PDO::FETCH_ASSOC);
    $resultWI = $pdo->query("SELECT NAME,TYPE,CALORIES_BURNED_PER_MINUTE FROM WORKOUTINFO;");
    $rowsWI = $resultWI->fetchAll(PDO::FETCH_ASSOC);
    // We now have all the workouts done by the user in the week, as well as all the workout info.
    // In order to calculate calories burned, we simply do CALORIES_BURNED_PER_MINUTE * DURATION.
    // For each workout done by the user.
	
	$workout = array();
	foreach($rowsWI as $rowWI){
		$workout[$rowWI["NAME"]] = 0;
	}
	$totalCal = 0;
    foreach($rowsWoW as $rowWOW){
      // For each workout in the DB.
      foreach($rowsWI as $rowWI){
        // We have the same workout.
        if($rowWOW["NAME"] == $rowWI["NAME"]){
        	$workout[$rowWOW["NAME"]] += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
			$totalCal += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
        }  
      }
    }
    
	echo "<br />From $WUFirstDate to $WULastDate you burned $totalCal calories!<br />";
    echo "<table border=1>";
    echo "<tr><th>Workout Name</th><th>Type</th><th>Intensity</th><th>Calories Burned</th></tr>";
    foreach($rowsWI as $rowWI){
    	echo "<tr><td>".$rowWI["NAME"]."</td><td>".$rowWI["TYPE"]."</td>";
		echo "<td>".$workout[$rowWI["NAME"]]."</td></tr>";
    }
    echo "</table>";
  }
?>
