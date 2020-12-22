<?php
  // The user will search through a list of workouts to find the one closest to the one they’re going to do,
  // in order to track the activity.

  // Essentially the same as the searchFoodDatabase.php script. Show the user the available workouts,
  // and when one is chosen, show the information on it.

  echo "<h4>Search the WorkoutInfo Database!</h4>";
  echo "<form method=POST>";
  echo "Select a workout from the list<select name=pickedWorkout>";
    $pickedWorkoutResult = $pdo->query("SELECT NAME FROM WORKOUTINFO;");
		$pickedWorkoutRows = $pickedWorkoutResult->fetchAll(PDO::FETCH_ASSOC);
		foreach($pickedWorkoutRows as $row){
			echo "<option value=".$row["NAME"].">".$row["NAME"]."</option>";	
		}
  echo "</select><br />";
  echo "<input type=submit value='Submit to see data about workout!'>";
  echo "</form>";

  if(!empty($_POST["pickedWorkout"])){
    $pickedWorkout = $_POST["pickedWorkout"];
    $sql = "SELECT * FROM WORKOUTINFO WHERE NAME=:Name;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":Name" => "$pickedWorkout"));
	if(!$success){
		echo "Error in query";
		die();
	}
	// From here we have the workout, and can simply show all the data associated with it.
    $rowWorkout = $prepared->fetchAll(PDO::FETCH_ASSOC);
    	echo "<table border=1>";
    		echo "<tr><th>Name</th><th>Type</th><th>Intensity</th><th>Calories burned per minute</th></tr>";
	  	foreach($rowWorkout as $rowWorkout){
    		echo "<tr><td>".$rowsWorkout["NAME"]."</td><td>".$rowsWorkout["TYPE"]."</td>";
			echo "<td>".$rowsWorkout["CALORIES_BURNED_PER_MINUTE"]."</td></tr>";
	  	}
		echo "</table>";
  }
?>
