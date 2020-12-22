<?php
  // You must implement a page that allows the user to enter their workouts.

  echo "<h4>Entering Workout Data!</h4>";
  echo "<form method=POST>";
		echo "Select workout name here<select name=workoutName>";
			$workoutDataResult = $pdo->query("SELECT NAME FROM WORKOUTINFO;");
			$workoutDataRows = $workoutDataResult->fetchAll(PDO::FETCH_ASSOC);
			foreach($workoutDataRows as $row){
				echo "<option value=".$row["NAME"].">".$row["NAME"]."</option>";	
			}
		echo "<select/><br />";
		echo "Input workout intensity here<input type=text name=workoutIntensity><br />";
		echo "Input workout duration, in minutes, here<input type=text name=workoutDuration><br />";
		echo "Input the date here, formatted as YYYY-MM-DD<input type=text name=workoutDate><br />";
		echo "<input type=submit value='Submit to add workout!'/>";
  echo "</form>";

	if(!empty($_POST["workoutName"]) && !empty($_POST["workoutIntensity"]) 
		&& !empty($_POST["workoutDuration"]) && !empty($_POST["workoutDate"])){
		$workoutName = $_POST["workoutName"];
		$workoutIntensity = $_POST["workoutIntensity"];
		$workoutDuration = $_POST["workoutDuration"];
		$workoutDate = $_POST["workoutDate"];
		
		$sql0 = "INSERT INTO WORKOUT(NAME,INTENSITY,DURATION,DATE) ";
      		$sql1 = "VALUES (:wName,:wIntensity,:wDuration,:wDate);";
      		$sql = $sql0.$sql;
		$prepared = $pdo->prepare($sql);
		$success = $prepared->execute(array(":wName" => "$workoutName", ":wIntensity" => "$workoutIntensity",
			":wDuration" => "$workoutDuration", ":wDate" => "$workoutDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
	}
?>
