<?php
  // You must implement a page that facilitates the addition of new foods/drinks into the database.
  // This should allow any of the relevant information (calories, macros, micronutrients, etc.) to be added.

  echo "<h4>Food/Drink Database Insertion!</h4>";
  echo "<form method=POST>";
		// Micronutrients will be in milligrams, Macronutrients will be in grams.
		echo "Input food/drink name here - <input type=text name=newFDName><br />";
		echo "Input micronutrients of Vitamin A per milligram here - <input type=text name=newFDVitA><br />";
		echo "Input micronutrients of Vitamin C per milligram here - <input type=text name=newFDVitC><br />";
		echo "Input micronutrients of Calcium per milligram here - <input type=text name=newFDCalcium><br />";
		echo "Input micronutrients of Iron per milligram here - <input type=text name=newFDIron><br />";
		echo "Input macronutrients of fats per serving here - <input type=text name=newFDFats><br />";
		echo "Input macronutrients of carbohydrates per serving here - <input type=text name=newFDCarbo><br />";
		echo "Input macronutrients of protein per serving here - <input type=text name=newFDProtein><br />";
		echo "Input the size of a serving, in grams or mL, here - <input type=text name=newFDSize><br />";
		echo "Input the amount of calories, in one serving, here - <input type=text name=newFDCal><br />";
		echo "<input type=submit value='Submit to add food/drink!'/>";
	echo "</form>";
  if(!empty($_POST["newFDName"]) && !empty($_POST["newFDVitA"]) && !empty($_POST["newFDVitC"]) && !empty($_POST["newFDCalcium"]) 
     		&& !empty($_POST["newFDIron"]) && !empty($_POST["newFDFats"]) && !empty($_POST["newFDCarbo"]) 
     		&& !empty($_POST["newFDProtein"]) && !empty($_POST["newFDSize"]) && !empty($_POST["newFDCal"])){
		$newFDName = $_POST["newFDName"];
		$newFDVitA = ($_POST["newFDVitA"]/1000);
	  	$newFDVitC = ($_POST["newFDVitC"]/1000);
	  	$newFDCalcium = ($_POST["newFDCalcium"]/1000);
	  	$newFDIron = ($_POST["newFDIron"]/1000);
		$newFDFats = $_POST["newFDFats"];
		$newFDCarbo = $_POST["newFDCarbo"];
		$newFDProtein = $_POST["newFDProtein"];
	  	$newFDSize = $_POST["newFDSize"];
	  	$newFDCal = $_POST["newFDCal"];
		$sql = "INSERT INTO NUTRITIONINFO (Name,VITAMIN_A,VITAMIN_C,CALCIUM,IRON,FAT,CARBS,PROTEIN,SERVING_SIZE,CALORIES) ";
		$sql2 = "VALUES (:Name,:VitA,:VitC,:Calc,:Iron,:Fats,:Carbo,:Protein,:Size,:Cal);";
		$sql = $sql.$sql2;
		$prepared = $pdo->prepare($sql);
	  	// All values entered are in grams.
	  	// It seems as though 1g = 1mL so we don't have to worry about that.
		$success = $prepared->execute(array(":Name" => "$newFDName", ":VitA" => "$newFDVitA",
			 ":VitC" => "$newFDVitC", ":Calc" => "$newFDCalcium", ":Iron" => "$newFDIron",
			 ":Fats" => "$newFDFats", ":Carbo" => "$newFDCarbo", ":Protein" => "newFDProtein",
			 ":Size" => "$newFDSize", ":Cal" => "$newFDCal"));
		if(!$success){
			echo "Error in query";
			die();
		}
	}
?>
