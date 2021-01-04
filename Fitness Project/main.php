<html><head><title>Fitness Project</title></head><body>
	<h1>Fitness Project</h1>
	<h4>Note: All units will be shown as grams, unless otherwise specified</h4>
<?php
  define("MEASUREMENT", ["lb","oz","mg","g","kg","c","p","ml","l","dl"]);
  try{
		$dsn = "mysql:host=DB_HOST;dbname=DB_NAME";
		$pdo = new PDO($dsn,$user,$password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo "Connection to database failed: ".$e->getMessage();
	}
  
  // Each of these files will be self contained.
  // However some variables may conflict with one another.
  // In that case try to make variable names unique to file.
  include("addToMealDatabase.php");
  include("caloriesBurnedPerWeek.php");
  include("caloriesConsumedPerWeek.php");
  include("enterMealData.php");
  include("enterWorkoutData.php");
  include("macronutrientPercentage.php");
  include("searchFoodDatabase.php");
  include("searchWorkoutDatabase.php");
  include("showFoodConsumption.php");
  include("showWorkoutUsage.php");
  include("trackMicronutrient.php");
  include("updateWeight.php");
  include("userWeightOverTime.php");
?>
</body></html>
