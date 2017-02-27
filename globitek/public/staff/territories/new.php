<?php
require_once('../../../private/initialize.php');
require_once('../../../private/functions.php');
require_once('../../../private/validation_functions.php');

$errors = array();
if(is_post_request()) {

  $position = "";
  $name = "";
  // Confirm that values are present before accessing them.
  if(isset($_POST['name'])) { $name = sanitize_input($_POST['name']); }
  if(isset($_POST['position'])) { $position = sanitize_input($_POST['position']); }

  // Perform Validations

  $i = 0;
  // Hint: Write these in private/validation_functions.php
  if(is_blank($name)){
    $errors[] = "Territory name cannot be blank";
    $i++;
  }
  if(is_blank($position)){
    $errors[] = "Territory position cannot be blank";
    $i++;
  }

  if(!has_length($name, array('min'=> 1, 'max'=>255))){
    $errors[] = "Territory name must be less than 255 characters";
    $i++;
  }
  if(!has_length($position, array('min'=> 2, 'max'=>2))){
    $errors[] = "Territory position must be 2 characters";
    $i++;
  }

  if(!has_valid_name($position)){
    $errors[] = "Territory name must only contain alphabetic character";
  }

  if(empty($errors)){

    $db = db_connect();
    // Write SQL INSERT statement
    $sql = "INSERT INTO `territories` (`name`, `state_id`, `position`) VALUES ('$name', '$state_id', '$position')";

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
<?php $page_title = 'Staff: New Territory'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<?php
if(!isset($_GET['state_id'])) {
  redirect_to('index.php');
}
$state_id = $_GET['state_id'];
?>

<?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);

?>

<div id="main-content">
  <a href="../states/show.php?id=<?php echo $state_id;?>">Back to State Details</a><br />

  <h1>New Territory</h1>

  <!-- TODO add form -->

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
          Name: <br>
          <input type= "text" name = "name" value="<?php if(is_post_request()){ echo h($_POST["name"]); } ?>">
          <br>
          Position: <br>
          <input type = "text" name = "position" value="<?php if(is_post_request()){ echo h($_POST["position"]); } ?>">
          <br><br>
          <input type="submit">
          <br>
     </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
