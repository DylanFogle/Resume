<?php
  // You must implement a page that allows the user to update their weight.

  echo "<h4>Update your weight!</h4>";
  echo "<form method=POST>";
    echo "Enter your current weight, in pounds, here<input type=text name=newWeight><br />";
    echo "Enter the date, in in the formation YYYY-MM-DD, here<input type=text name=weightDate><br />";
    echo "<input type=submit value='Submit to log your weight!'>";
  echo "</form>";

  if(!empty($_POST["newWeight"]) && !empty($_POST["weightDate"])){
    $newWeight = $_POST["newWeight"];
    $weightDate = $_POST["weightDate"];
    
    $sql = "INSERT INTO WEIGHT(NUMERIC_WEIGHT,DATE) VALUES (:Weight,:Date);";
    $prepared = $pdo->prepare($sql);
    $success = $prepared->execute(array(":Weight" => "$newWeight", ":Date" => "$weightDate"));
    if(!$success){
			echo "Error in query";
			die();
		}
  }
?>
