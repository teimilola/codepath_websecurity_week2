<?php
  require_once('../../../private/initialize.php');
  require_once('../private/functions.php');
  require_once('../private/validation_functions.php');
  require_once('../private/database.php');
  // Set default values for all variables the page needs.
  $firstname = "";
  $lastname = "";
  $phone_no = "";
  $email = "";
  $errors = array();
  // if this is a POST request, process the form
  if(is_post_request()){

    // Confirm that POST values are present before accessing them.
    if(isset($_POST["firstname"])){
      $firstname = sanitize_input($_POST["firstname"]);
    }
    if(isset($_POST["lastname"])){
      $lastname = sanitize_input($_POST["lastname"]);
    }
    if(isset($_POST["phone_no"])){
      $phone_no = sanitize_input($_POST["phone_no"]);
    }
    if(isset($_POST["email"])){
      $email = sanitize_input($_POST["email"]);
    }


    // Perform Validations

    $i = 0;
    // Hint: Write these in private/validation_functions.php
    if(is_blank($firstname)){
      $errors[] = "Firstname cannot be blank";
      $i++;
    }
    if(is_blank($lastname)){
      $errors[] = "Lastname cannot be blank";
      $i++;
    }
    if(is_blank($email)){
      $errors[] = "email cannot be blank";
      $i++;
    }
    if(is_blank($phone_no)){
      $errors[] = "Phone Number cannot be blank";
      $i++;
    }

    if(!has_valid_email_format($email)){
      $errors[] = "Invalid email";
      $i++;
    }
    if(!has_length($firstname, array('min'=> 1, 'max'=>255))){
      $errors[] = "Firstname must be less than 255 characters";
      $i++;
    }
    if(!has_length($lastname, array('min'=> 1, 'max'=>255))){
      $errors[] = "Lastname must be less than 255 characters";
      $i++;
    }
    if(!has_length($email, array('min'=> 1, 'max'=>255))){
      $errors[] = "email must be less than 255 characters";
      $i++;
    }
    if(!has_length($phone_no, array('min'=> 1, 'max'=>255))){
      $errors[] = "Phone Number must be less than 255 characters";
      $i++;
    }


    // if there were no errors, submit data to database
    if(empty($errors)){
      $db = db_connect();
      // Write SQL INSERT statement
      $sql = "INSERT INTO `salespeople` (`first_name`, `last_name`, `email`, `phone`) VALUES ('$firstname', '$lastname', '$email', '$phone_no')";

      // For INSERT statments, $result is just true/false
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

<?php $page_title = 'Staff: New Salesperson'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Salespeople List</a><br />

  <h1>New Salesperson</h1>

  <?php
      // TODO: display any form errors here
      // Hint: private/functions.php can help
      echo display_errors($errors);

  ?>

  <!-- TODO add form -->
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
      FirstName: <br>
      <input type= "text" name = "firstname" value="<?php if(is_post_request()){ echo h($_POST["firstname"]); } ?>">
      <br>
      LastName: <br>
      <input type = "text" name = "lastname" value="<?php if(is_post_request()){ echo h($_POST["lastname"]); } ?>">
      <br>
      Email: <br>
      <input type = "text" name = "email" value="<?php if(is_post_request()){ echo h($_POST["email"]); } ?>">
      <br>
      Phone No: <br>
      <input type = "text" name = "phone_no" value = "<?php if(is_post_request()){ echo h($_POST["phone_no"]); } ?>">
      <br> <br>
      <input type="submit">
      <br>
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
