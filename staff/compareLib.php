
function compareLib($database_array, $x){
  //return $database_array[$x]['age'] + 1;
  $compareAge = ($database_array[$x]['age'] == $_POST['age_'.$x]);
  $compareEmail = ($database_array[$x]['email'] == $_POST['email_'.$x]);
  $compareName = ($database_array[$x]['name'] == $_POST['name_'.$x]);

  return $compareAge && $compareEmail && $compareName;
}