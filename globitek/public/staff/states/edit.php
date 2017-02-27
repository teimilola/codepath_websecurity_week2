<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$states_result = find_state_by_id($_GET['id']);
// No loop, only one result
$state = db_fetch_assoc($states_result);
$errors = array();
if(is_post_request()) {


  // Confirm that values are present before accessing them.
  if(isset($_POST['name'])) { $state['name'] = sanitize_input($_POST['name']); }
  if(isset($_POST['code'])) { $state['code'] = sanitize_input($_POST['code']); }

  // Perform Validations

  $i = 0;
  // Hint: Write these in private/validation_functions.php
  if(is_blank($state['name'])){
    $errors[] = "State name cannot be blank";
    $i++;
  }
  if(is_blank($state['code'])){
    $errors[] = "State Code cannot be blank";
    $i++;
  }

  if(!has_length($state['name'], array('min'=> 1, 'max'=>255))){
    $errors[] = "State name must be less than 255 characters";
    $i++;
  }
  if(!has_length($state['code'], array('min'=> 2, 'max'=>2))){
    $errors[] = "State Code must be 2 characters";
    $i++;
  }

  if(!has_valid_state_code($state['code'])){
    $errors[] = "State Code must only contain alphabetic character";
  }

  if(empty($errors)){
    $state['code'] = strtoupper($state['code']);
    $result = update_user($state);
    if($result === true) {
      redirect_to('show.php?id=' . $state['id']);
    } else {
      $errors = $result;
    }
  }
}

?>
<?php $page_title = 'Staff: Edit State ' . $state['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);

?>

<div id="main-content">
  <a href="index.php">Back to States List</a><br />

  <h1>Edit State: <?php echo $state['name']; ?></h1>

  <!-- TODO add form -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
          Name: <br>
          <input type= "text" name = "name" value="<?php if(is_post_request()){ echo h($_POST["name"]); } else{echo $state['name'];} ?>">
          <br>
          Code: <br>
          <input type = "text" name = "code" value="<?php if(is_post_request()){ echo h($_POST["code"]); } else{echo $state['code'];}?>">
          <br>
          <input type="submit">
          <br>
   </form>

   <a href="show.php?id=<php? echo $state['id'];?>">Cancel</a><br />
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
