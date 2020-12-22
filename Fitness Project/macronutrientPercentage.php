<?php
  // Show a pie graph of the percentage of the diet made up of each macronutrient during a given time period (day, week, month).

  // For this script the NutritionInfo Database will need to be referenced to get the Macronutrient data.
  // Ask the user for two dates and show the data between those dates.

  echo "<h4>Macronutrient consumption over time!</h4>";
	echo "<form method=POST>";
  	echo "Please enter the date, in the format YYYY-MM-DD, for the beginning of a time period.";
  	echo "<input type=text name=macroFirstDate><br />";
  	echo "Please enter the date, in the format YYYY-MM-DD, for the end of a time period.";
  	echo "<input type=text name=macroLastDate><br />";
  	echo "<input type=submit value='Submit to see macronutrient breakdown!'/>";
	echo "</form>";

  if(!empty($_POST["macroFirstDate"]) && !empty($_POST["macroLastDate"])){
    $macroFirstDate = $_POST["macroFirstDate"];
    $macroLastDate = $_POST["macroLastDate"];
    $sql = "SELECT NAME, QUANTITY FROM FOOD_AND_DRINK WHERE Date >= :MFD AND Date < :MLD;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":MFD" => "$macroFirstDate", ":MLD" => "$macroLastDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
    $rowsMacroDiet = $prepared->fetchAll(PDO::FETCH_ASSOC);
    $resultFD = $pdo->query("SELECT NAME,FAT,CARBS,PROTEIN FROM NUTRITIONINFO;");
    $rowsFD = $resultFD->fetchAll(PDO::FETCH_ASSOC);
    
    // From here, we have the date range specified by the user, and all foods/drinks from the DB.
    // What we will now do is, for every food in the user's diet, calculate how many servings were consumed
    // and add that to the total amount of each Macronutrient. From here we will show how much of the diet
    // was made up of what Macronutrient.
    
    $fatsAmount = 0;
    $carboAmount = 0;
    $proteinAmount = 0;
    
    // For each food/drink consumed by the user.
    foreach($rowsMacroDiet as $rowM){
      // For each food/drink in the DB.
      foreach($rowsFD as $rowFD){
        if($rowM["NAME"] == $rowFD["NAME"]){
          // See how many servings the user ate of that food.
          $foodServing = $rowM["QUANTITY"] / $rowFD["SERVING_SIZE"];
          // Calculate how many grams of each Macro were eaten.
          $fatsAmount += $rowFD["FAT"] * $foodServing;
          $carboAmount += $rowFD["CARBS"] * $foodServing;
          $proteinAmount += $rowFD["PROTEIN"] * $foodServing;
        }
      }
    }
    
    // Add up the amounts to get percentages.
    $macroTotal = $fatsAmount + $carboAmount + $proteinAmount;
	if($macroTotal == 0){
		$macroTotal = 1;
	}
    $fatsP = $fatsAmount / $macroTotal;
    $carboP = $carboAmount / $macroTotal;
    $proteinP = $proteinAmount / $macroTotal;
    
    echo "<table border=1>";
      echo "<tr>$macroFirstDate to $macroLastDate</tr>";
      echo "<tr><th>Fats</th><th>Carbohydrates</th><th>Protein</th></tr>";
      echo "<tr><td>$fatsP"."%"."</td><td>$carboP"."%"."</td><td>$proteinP"."%"."</td></tr>";
    echo "</table><br />";
  }
?>
