<?php
  // You must implement a page that allows the user to enter the foods/drinks they have consumed, and
  // in what quantities (tracking page). This page must allow the amount consumed to be specified in any
  // relevant type of unit, and the rest of your app should be able to handle those conversions.

  echo "<h4>Entering Meal Data!</h4>";
  echo "<form method=POST>";
		echo "Select food/drink name here<select name=dietName>";
			$mealDataResult = $pdo->query("SELECT NAME FROM NUTRITIONINFO;");
			$mealDataRows = $mealDataResult->fetchAll(PDO::FETCH_ASSOC);
			foreach($mealDataRows as $row){
				echo "<option value=".$row["NAME"].">".$row["NAME"]."</option>";	
			}
		echo "</select><br />";
		echo "Input food/drink amount here<input type=text name=dietAmount>";
    echo "<select name=dietMeasurement>";
      foreach(MEASUREMENT as $dietM){
        echo "<option value=".$dietM.">".$dietM."</option>"; 
      }
    echo "</select><br />";
		echo "Input the date here, formatted as YYYY-MM-DD<input type=text name=dietDate><br />";
		echo "<input type=submit value='Submit to add meal!'/>";
  echo "</form>";

	if(!empty($_POST["dietName"]) && !empty($_POST["dietAmount"]) && !empty($_POST["dietDate"])){
			$dietName = $_POST["dietName"];
			$dietAmount = $_POST["dietAmount"];
			$dietMeasure = $_POST["dietMeasurement"];
			$dietDate = $_POST["dietDate"];
			$dietCalories = 0;
			// Convert amount to common measurement, with g for food and mL for drinks.
			// Thankfully it seems that 1g = 1mL so that will ease some of the burden.
			if($dietMeasure == "lb"){
				$dietAmount = $dietAmount*454;
			}
			if($dietMeasure == "oz"){
				$dietAmount = $dietAmount*28.35;
			}
			if($dietMeasure == "mg"){
				$dietAmount = $dietAmount/1000;
			}
			if($dietMeasure == "kg"){
				$dietAmount = $dietAmount*1000;
			}
			if($dietMeasure == "c"){
				$dietAmount = $dietAmount*237;
			}
			if($dietMeasure == "p"){
				$dietAmount = $dietAmount*473;
			}
			if($dietMeasure == "l"){
				$dietAmount = $dietAmount*1000;
			}
			if($dietMeasure == "dl"){
				$dietAmount = $dietAmount*100;
			}
		
			// Finally we need to get the amount of calories consumed from the meal.
			// This involves finding the food in the DB and dividing its serving size by amount consumed.
			$resultFD = $pdo->query("SELECT NAME,SERVING_SIZE,CALORIES FROM NUTRITIONINFO;");
			$rowsFD = $resultFD->fetchAll(PDO::FETCH_ASSOC);
			foreach($rowsFD as $rowFD){
				if($rowFD["NAME"] == $dietName){
						$dietCalories = ($dietAmount/$rowFD["SERVING_SIZE"])*$rowFD["CALORIES"]; 
				}
			}
		
			$sql = "INSERT INTO FOOD_AND_DRINK(NAME,QUANTITY,DATE,CALORIES) VALUES (:dietN,:dietA,:dietD,:dietC);";
			$prepared = $pdo->prepare($sql);
			$success = $prepared->execute(array(":dietN" => "$dietName", ":dietA" => "$dietAmount",
			 ":dietD" => "$dietDate", ":dietC" => "$dietCalories"));
			if(!$success){
				echo "Error in query";
				die();
			}
		}
?>
