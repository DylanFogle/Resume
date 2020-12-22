<?php
  // Track the consumption of a given micronutrient/chemical over a specified period of time. If there is a
  // recommended daily value, show a comparison of their consumption with the recommended amount.

  // Similar to macronutrientPercentage.php. For daily value I can just look up
  // and create a table with the recommended daily amounts.

  echo "<h4>Tracking the consumption of a micronutrient!</h4>";
  echo "<form method=POST>";
    echo "Please enter the name of the micronutrient to be tracked<br />";
    echo "You can choose between VITAMIN_A,VITAMIN_C,IRON, and CALCIUM!";
    echo "<input type=text name=microChosen><br / >";
  	echo "Please enter the date, in the format YYYY-MM-DD, for the beginning of a time period.";
  	echo "<input type=text name=microFirstDate><br />";
  	echo "Please enter the date, in the format YYYY-MM-DD, for the end of a time period.";
  	echo "<input type=text name=microLastDate><br />";
  	echo "<input type=submit value='Submit to see micronutrient consumption!'/>";
	echo "</form>";

  if(!empty($_POST["microFirstDate"]) && !empty($_POST["microLastDate"]) && !empty($_POST["microChosen"]){
    $microFirstDate = $_POST["microFirstDate"];
    $microLastDate = $_POST["microLastDate"];
    $microChosen = $_POST["microChosen"];
    $sql = "SELECT NAME,QUANTITY,DATE FROM FOOD_AND_DRINK WHERE DATE >= :MFD AND DATE < :MFD ORDER BY DATE;";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":MFD" => "$microFirstDate", ":MLD" => "$microLastDate"));
		if(!$success){
		 	echo "Error in query";
			die();
		}
    // Get all meals between the two dates.
    $rowsMicroDiet = $prepared->fetchAll(PDO::FETCH_ASSOC);
    $sql1 = "SELECT NAME,:Micro,SERVING_SIZE FROM NUTRITIONINFO;";
    $prepared1 = $pdo->prepare($sql1);
    $success1 = $prepared1->execute(array(":Micro" => "$microChosen"));
		if(!$success1){
		 	echo "Error in query";
			die();
		}
    // Get all food/drinks in the DB.
    $rowsFD = $prepared1->fetchAll(PDO::FETCH_ASSOC);
    
    // From here we have all the meals eaten by the user in a give time frame.
    // We also have every food/drink from the database regarding their micronutrients.
    // Now, for every food in the user's diet, we calculate how many servings were consumed
    // and find how much of that micronutrient was consumed for each date between the date range.
    
    $microAmount = 0;
    if($microChosen == "VITAMIN_C"){
      $microAmount = 0.09;
    }
    if($microChosen == "VITAMIN_A"){
      $microAmount = 0.0009;
    }
    if($microChosen == "CALCIUM"){
      $microAmount = 2.5;
    }
    if($microChosen == "IRON"){
      $microAmount = 0.0087;
    }
    echo "The daily recommended amount of ".$microChosen." is ".$microAmount." grams.";
    
    echo "<table border=1>";
    echo "<tr><th>Date</th><th>".$microChosen."</th></tr>";
    // For each food/drink consumed by the user.
    foreach($rowsMicroDiet as $rowM){
      echo "<tr><td>".$rowM["DATE"]."</td>";
      // For each food/drink in the DB.
      foreach($rowsFD as $rowFD){
        if($rowM["NAME"] == $rowFD["NAME"]){
          $foodServing =  $rowM["QUANTITY"] / $rowFD["SERVING_SIZE"];
          $microAmount = $rowFD[$microChosen] * $foodServing;
          echo "<td>".$microAmount."</td>";
        }
      }
      echo "</tr>";
    }
    echo "</table>";
  }
?>
