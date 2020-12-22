<?php
  // A similar graph will be generated that shows how many calories were burnt each day of the week through workout.
  
  // Have the user enter two days that will define a week.
  // Then we simply calculate the amount of calories burned each day.
  echo "<h4>Showing how many calories a user burned in a week!</h4>";

  echo "<form method=POST>";
    echo "Please enter the first day of the week in the format YYYY-MM-DD!";
    echo "<input type=text name=CBFirstDate><br />";
    echo "Please enter the last day of the week in the format YYYY-MM-DD!";
    echo "<input type=text name=CBLastDate><br />";
    echo "<input type=submit value='Submit to see calories burned!'>";
  echo "</form>";

  if(!empty($_POST["CBFirstDate"]) && !empty($_POST["CBLastDate"])){
    $CBFirstDate = $_POST["CBFirstDate"];
    $CBLastDate = $_POST["CBLastDate"];
    $sql = "SELECT DISTINCT DATE FROM WORKOUT WHERE DATE >= :CBFD AND DATE <= :CBLD;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":CBFD" => "$CBFirstDate", ":CBLD" => "$CBLastDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
    $rowsDoW = $prepared->fetchAll(PDO::FETCH_ASSOC);
    // We now have every distinct day of possible workout given by the user
	$CBDay0 = "N/A";
    $CBDay1 = "N/A";
    $CBDay2 = "N/A";
    $CBDay3 = "N/A";
    $CBDay4 = "N/A";
    $CBDay5 = "N/A";
    $CBDay6 = "N/A"; 
    $i = 0;
    foreach($rowsDoW as $row){
      if($i == 0){
                $CBDay0 = $row["DATE"];
        }
        if($i == 1){
                $CBDay1 = $row["DATE"];
        }
        if($i == 2){
                $CBDay2 = $row["DATE"];
        }
        if($i == 3){
                $CBDay3 = $row["DATE"];
        }
        if($i == 4){
                $CBDay4 = $row["DATE"];
        }
        if($i == 5){
                $CBDay5 = $row["DATE"];
        }
        if($i == 6){
                $CBDay6 = $row["DATE"];
        }
      $i++;
    }
    // But we need to redo the query again to get all workouts done in that time.
    $sql1 = "SELECT NAME,DURATION,DATE FROM WORKOUT WHERE DATE >= :CBFD AND DATE <= :CBLD;";
    $prepared1 = $pdo->prepare($sql1);
    $success1 = $prepared1->execute(array(":CBFD" => "$CBFirstDate", ":CBLD" => "$CBLastDate"));
		if(!$success1){
			echo "Error in query";
			die();
		}
    $rowsWoW = $prepared1->fetchAll(PDO::FETCH_ASSOC);
    $resultCB = $pdo->query("SELECT NAME, CALORIES_BURNED_PER_MINUTE FROM WORKOUTINFO;");
    $rowsWI = $resultCB->fetchAll(PDO::FETCH_ASSOC);
    // We now have all the workouts done by the user in the week, as well as all the workout info.
    // In order to calculate calories burned, we simply do CALORIES_BURNED_PER_MINUTE * DURATION.
    $CBDay0Amount = 0;
    $CBDay1Amount = 0;
    $CBDay2Amount = 0;
    $CBDay3Amount = 0;
    $CBDay4Amount = 0;
    $CBDay5Amount = 0;
    $CBDay6Amount = 0;
    // For each workout done by the user.
    foreach($rowsWoW as $rowWOW){
      // For each workout in the DB.
      foreach($rowsWI as $rowWI){
        // We have the same workout.
        if($rowWOW["NAME"] == $rowWI["NAME"]){
          // Now check for day.
          if($CBDay0 == $rowWOW["DATE"]){
            $CBDay0Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay1 == $rowWOW["DATE"]){
            $CBDay1Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay2 == $rowWOW["DATE"]){
            $CBDay2Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay3 == $rowWOW["DATE"]){
            $CBDay3Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay4 == $rowWOW["DATE"]){
            $CBDay4Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay5 == $rowWOW["DATE"]){
            $CBDay5Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
          if($CBDay6 == $rowWOW["DATE"]){
            $CBDay6Amount += $rowWOW["DURATION"] * $rowWI["CALORIES_BURNED_PER_MINUTE"];
          }
        }  
      }
    }
    
    echo "<table border=1>";
    echo "<tr><th>$CBDay0</th><th>$CBDay1</th><th>$CBDay2</th>";
    echo "<th>$CBDay3</th><th>$CBDay4</th><th>$CBDay5</th><th>$CBDay6</th></tr>";
    echo "<tr><td>$CBDay0Amount</td><td>$CBDay1Amount</td><td>$CBDay2Amount</td><td>$CBDay3Amount</td>";
    echo "<td>$CBDay4Amount</td><td>$CBDay5Amount</td><td>$CBDay6Amount</td></tr>";
	echo "</table>";
  }
?>
