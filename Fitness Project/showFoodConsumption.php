<?php
  // You must implement a page that shows a table that lists of all the food consumed over a selected period
  // of time, along with the calories and macros that are contained in the quantity of food specified. You
  // must implement this page in such a way that clicking on the column headings will make your page sort
  // based on that column. First click should sort in ascending order, a second should sort in descending
  // order, and subsequent clicks just toggle that back and forth.

  // Have the user enter two days that will define a time period.
  // Then we simply show the relevant informationfor each date.
  echo "<h4>Showing a user's food consumption!</h4>";

  echo "<form method=POST>";
    echo "Please enter the first day of the time period in the format YYYY-MM-DD!";
    echo "<input type=text name=FCFirstDate><br />";
    echo "Please enter the last day of the time period in the format YYYY-MM-DD!";
    echo "<input type=text name=FCLastDate><br />";
    echo "<input type=submit value='Submit to see Food/Drink consumed!'>";
  echo "</form>";

  if(!empty($_POST["FCFirstDate"]) && !empty($_POST["FCLastDate"])){
    $FCFirstDate = $_POST["FCFirstDate"];
    $FCLastDate = $_POST["FCLastDate"];
    $sql = "SELECT NAME,QUANTITY,CALORIES,DATE FROM FOOD_AND_DRINK WHERE DATE >= :FCFD AND DATE <= :FCLD;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":FCFD" => "$FCFirstDate", ":FCLD" => "$FCLastDate"));
		if(!$success){
			echo "Error in query";
			die();
		}
    $rowsCoW = $prepared->fetchAll(PDO::FETCH_ASSOC);
    $resultNI = $pdo->query("SELECT NAME,FAT,CARBS,PROTEIN,SERVING_SIZE FROM NUTRITIONINFO;");
    $rowsNI = $resultNI->fetchAll(PDO::FETCH_ASSOC);
    // We now have all the meals consumed by the user in the time period, as well as all the nutrition info.
    // We know the calories from each meal already.
	
    $arrayFat = array();
    $arrayCar = array();
    $arrayPro = array();
	foreach($rowsNI as $rowNI){
		$arrayFat[$rowNI["NAME"]] = 0;
        $arrayCar[$rowNI["NAME"]] = 0;
        $arrayPro[$rowNI["NAME"]] = 0;
	}
    foreach($rowsCoW as $rowCOW){
      // For each FD in the DB.
      foreach($rowsNI as $rowNI){
        // We have the same FD.
        if($rowCOW["NAME"] == $rowNI["NAME"]){
        	$arrayFat[$rowCOW["NAME"]] += ($rowCOW["QUANTITY"]/$rowNI["SERVING_SIZE"]) * $rowNI["FAT"];
          	$arrayCar[$rowCOW["NAME"]] += ($rowCOW["QUANTITY"]/$rowNI["SERVING_SIZE"]) * $rowNI["CARBS"];
          	$arrayPro[$rowCOW["NAME"]] += ($rowCOW["QUANTITY"]/$rowNI["SERVING_SIZE"]) * $rowNI["PROTEIN"];
        }  
      }
    }
    
	echo "<br />From $FCFirstDate to $FCLastDate you consumed these foods/drinks!";
    echo "<table border=1>";
    echo "<tr><th>Food/Drink Name</th><th>Quantity</th><th>Calories</th><th>Fat</th><th>Carbohydrates</th><th>Protein</th></tr>";
    foreach($rowsCoW as $rowCOW){
      echo "<tr><td>".$rowCOW["NAME"]."</td><td>".$rowCOW["QUANTITY"]."</td><td>".$rowCOW["CALORIES"]."</td>";
      echo "<td>".$arrayFat[$rowCOW["NAME"]]."</td><td>".$arrayCar[$rowCOW["NAME"]]."</td><td>".$arrayPro[$rowCOW["NAME"]]."</td></tr>";
    }
    echo "</table>";
  }
?>
