<?php
require_once('../../../private/initialize.php');
require_once('../../../private/functions.php');
require_once('../../../private/validation_functions.php');
require_once('../../../private/query_functions.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$salespeople_result = find_salesperson_by_id($_GET['id']);
// No loop, only one result
$salesperson = db_fetch_assoc($salespeople_result);

$errors = array();
  // if this is a POST request, process the form
  if(is_post_request()){

    // Confirm that POST values are present before accessing them.
    if(isset($_POST["firstname"])){
      $salesperson['first_name'] = sanitize_input($_POST["firstname"]);
    }
    if(isset($_POST["lastname"])){
      $salesperson['last_name'] = sanitize_input($_POST["lastname"]);
    }
    if(isset($_POST["phone_no"])){
      $salesperson['phone'] = sanitize_input($_POST["phone_no"]);
    }
    if(isset($_POST["email"])){
      $salesperson['email'] = sanitize_input($_POST["email"]);
    }

    // Perform Validations

    $i = 0;
    // Hint: Write these in private/validation_functions.php
    if(is_blank($salesperson['first_name'])){
      $errors[] = "Firstname cannot be blank";
      $i++;
    }
    if(is_blank($salesperson['last_name'])){
      $errors[] = "Lastname cannot be blank";
      $i++;
    }
    if(is_blank($salesperson['email'])){
      $errors[] = "email cannot be blank";
      $i++;
    }
    if(is_blank($salesperson['phone'])){
      $errors[] = "Phone Number cannot be blank";
      $i++;
    }

    if(!has_valid_email_format($salesperson['email'])){
      $errors[] = "Invalid email";
      $i++;
    }
    if(!has_length($salesperson['first_name'], array('min'=> 1, 'max'=>255))){
      $errors[] = "Firstname must be less than 255 characters";
      $i++;
    }
    if(!has_length($salesperson['last_name'], array('min'=> 1, 'max'=>255))){
      $errors[] = "Lastname must be less than 255 characters";
      $i++;
    }
    if(!has_length($salesperson['email'], array('min'=> 1, 'max'=>255))){
      $errors[] = "email must be less than 255 characters";
      $i++;
    }
    if(!has_length($salesperson['phone'], array('min'=> 1, 'max'=>255))){
      $errors[] = "Phone Number must be less than 255 characters";
      $i++;
    }
    if(!has_valid_name($salesperson['last_name'])){
      $errors[] = "Invalid last name";
      $i++;
    }
    if(!has_valid_name($salesperson['first_name'])){
      $errors[] = "Invalid first name";
      $i++;
    }

    if(!has_valid_phone($salesperson['phone'])){
      $errors[] = "Invalid phone no";
      $i++;
    }
  if(empty($errors)){
    $result = update_salesperson($salesperson);
    if($result === true) {
      header("Location: show.php?id=".$salesperson['id']);
      //redirect_to('show.php?id='.$salesperson['id']);
    } else {
      $errors = $result;
    }
  }
}


?>
<?php $page_title = 'Staff: Edit Salesperson ' . $salesperson['first_name'] . " " . $salesperson['last_name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);

?>

<div id="main-content">
  <a href="index.php">Back to Salespeople List</a><br />

  <h1>Edit Salesperson: <?php echo $salesperson['first_name'] . " " . $salesperson['last_name']; ?></h1>

  <!-- TODO add form -->
  <form method="POST" action="edit.php?id=<?php echo $salesperson['id'];?>">
        FirstName: <br>
        <input type= "text" name = "firstname" value="<?php if(is_post_request()){ echo h($_POST["firstname"]); } else{echo $salesperson['first_name'];} ?>">
        <br>
        LastName: <br>
        <input type = "text" name = "lastname" value="<?php if(is_post_request()){ echo h($_POST["lastname"]); } else{echo $salesperson['last_name'];} ?>">
        <br>
        Email: <br>
        <input type = "text" name = "email" value="<?php if(is_post_request()){ echo h($_POST["email"]); } else{echo $salesperson['email'];}?>">
        <br>
        Phone No: <br>
        <input type = "text" name = "phone_no" value = "<?php if(is_post_request()){ echo h($_POST["phone_no"]); } else{echo $salesperson['phone'];} ?>">
        <br> <br>
        <input type="submit">
        <br>
    </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
