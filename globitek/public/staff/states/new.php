<?php
require_once('../../../private/initialize.php');
require_once('../../../private/functions.php');
require_once('../../../private/validation_functions.php');

$errors = array();
if(is_post_request()) {

  $code = "";
  $name = "";
  // Confirm that values are present before accessing them.
  if(isset($_POST['name'])) { $name = sanitize_input($_POST['name']); }
  if(isset($_POST['code'])) { $code = sanitize_input($_POST['code']); }

  // Perform Validations

  $i = 0;
  // Hint: Write these in private/validation_functions.php
  if(is_blank($name)){
    $errors[] = "State name cannot be blank";
    $i++;
  }
  if(is_blank($code)){
    $errors[] = "State Code cannot be blank";
    $i++;
  }

  if(!has_length($name, array('min'=> 1, 'max'=>255))){
    $errors[] = "State name must be less than 255 characters";
    $i++;
  }
  if(!has_length($code, array('min'=> 2, 'max'=>2))){
    $errors[] = "State Code must be 2 characters";
    $i++;
  }

  if(!has_valid_state_code($code)){
    $errors[] = "State Code must only contain alphabetic character";
  }

  if(!has_valid_name($name)){
      $errors[] = "State name must only contain alphabetic character";
  }

  if(empty($errors)){
    $code = strtoupper($code);

    $db = db_connect();
    // Write SQL INSERT statement
    $sql = "INSERT INTO `states` (`name`, `code`, `country_id`) VALUES ('$name', '$code', '1')";

    // For INSERT statements, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      $new_id = db_insert_id($db);
      db_close($db);
      redirect_to('show.php?id='.$new_id);
    } else {
      //   // The SQL INSERT statement failed.
      //   // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }
}

?>
<?php $page_title = 'Staff: New State'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);

?>

<div id="main-content">
  <a href="index.php">Back to States List</a><br />

  <h1>New State</h1>

  <!-- TODO add form -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
        Name: <br>
        <input type= "text" name = "name" value="<?php if(is_post_request()){ echo h($_POST["name"]); } ?>">
        <br>
        Code: <br>
        <input type = "text" name = "code" value="<?php if(is_post_request()){ echo h($_POST["code"]); } ?>">
        <br><br>
        <input type="submit">
        <br>
   </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
