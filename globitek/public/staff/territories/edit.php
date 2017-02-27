<?php
require_once('../../../private/initialize.php');
require_once('../../../private/functions.php');
require_once('../../../private/validation_functions.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}

$territories_result = find_territory_by_id($_GET['id']);
// No loop, only one result
$territory = db_fetch_assoc($territories_result);

$errors = array();
if(is_post_request()) {

  $position = "";
  $name = "";
  // Confirm that values are present before accessing them.
  if(isset($_POST['name'])) { $territory['name'] = sanitize_input($_POST['name']); }
  if(isset($_POST['position'])) { $territory['position'] = sanitize_input($_POST['position']); }

  // Perform Validations

  $i = 0;
  // Hint: Write these in private/validation_functions.php
  if(is_blank($territory['name'])){
    $errors[] = "Territory name cannot be blank";
    $i++;
  }
  if(is_blank($territory['position'])){
    $errors[] = "Territory position cannot be blank";
    $i++;
  }

  if(!has_length($territory['name'], array('min'=> 1, 'max'=>255))){
    $errors[] = "Territory name must be less than 255 characters";
    $i++;
  }
  if(!has_length($territory['position'], array('min'=> 2, 'max'=>2))){
    $errors[] = "Territory position must be 2 characters";
    $i++;
  }

  if(!has_valid_name($territory['position'])){
    $errors[] = "Territory name must only contain alphabetic character";
  }

  if(empty($errors)){

    $result = update_user($territory);
    if($result === true) {
      redirect_to('show.php?id=' . $territory['id']);
    } else {
      $errors = $result;
    }
  }
}
?>

?>
<?php $page_title = 'Staff: Edit Territory ' . $territory['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);

?>

<div id="main-content">
  <a href="../states/show.php?id=<?php echo $territory['state_id'];?>">Back to State Details</a><br />

  <h1>Edit Territory: <?php echo $territory['name']; ?></h1>

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
