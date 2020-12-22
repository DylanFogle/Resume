<?php
  // Allow the user to search through the food database to find common foods, in order to plan their diets.
  // These same foods will be used to track their eating.

  // Allow the user to select a food/drink from the database using a drop down menu.
  // From here just display the information about the chosen item.

  echo "<h4>Search the NutritionInfo Database!</h4>";
  echo "<form method=POST>";
  echo "Select a food/drink from the list<select name=pickedItem>";
    $itemDataResult = $pdo->query("SELECT NAME FROM NUTRITIONINFO;");
		$itemDataRows = $itemDataResult->fetchAll(PDO::FETCH_ASSOC);
		foreach($itemDataRows as $row){
			echo "<option value=".$row["NAME"].">".$row["NAME"]."</option>";	
		}
  echo "</select><br />";
  echo "<input type=submit value='Submit to see data about item!'>";
  echo "</form>";

  if(!empty($_POST["pickedItem"])){
    $itemName = $_POST["pickedItem"];
    $sql = "SELECT * FROM NUTRITIONINFO WHERE NAME=:Name;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":Name" => "$itemName"));
		if(!$success){
			echo "Error in query";
			die();
		}
		// From here we have the food/drink, and can simply show all the data associated with it.
    $rowItem = $prepared->fetchAll(PDO::FETCH_ASSOC);
    	echo "<table border=1>";
    	echo "<tr><th>Name</th><th>Vitamin A</th><th>Vitamin C</th><th>Calcium</th><th>Iron</th>";
			echo "<th>Fats</th><th>Carbohydrates</th><th>Protein</th><th>Size</th><th>Calories</th></tr>";
	foreach($rowItem as $rowsItem){
			echo "<tr><td>".$rowsItem["NAME"]."</td><td>".$rowsItem["VITAMIN_A"]."</td><td>".$rowsItem["VITAMIN_C"]."</td>";
			echo "<td>".$rowsItem["CALCIUM"]."</td><td>".$rowsItem["IRON"]."</td><td>".$rowsItem["FAT"]."</td>";
			echo "<td>".$rowsItem["CARBS"]."</td><td>".$rowsItem["PROTEIN"]."</td><td>".$rowsItem["SERVING_SIZE"]."</td>";
			echo "<td>".$rowsItem["CALORIES"]."</td></tr>";
	}
	echo "</table>";
  }
?>
