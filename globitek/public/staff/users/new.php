<?php
require_once('../../../private/initialize.php');
require_once('../../../private/functions.php');
require_once('../../../private/initialize.php');
// Set default values for all variables the page needs.
$errors = array();
  $firstname = "";
  $lastname = "";
  $username = "";
  $email = "";

if(is_post_request()) {

  // Confirm that values are present before accessing them.
  if(isset($_POST['first_name'])) { $firstname = sanitize_input($_POST['first_name']); }
  if(isset($_POST['last_name'])) { $lastname = sanitize_input($_POST['last_name']); }
  if(isset($_POST['username'])) { $username = sanitize_input($_POST['username']); }
  if(isset($_POST['email'])) { $email = sanitize_input($_POST['email']); }

  // Confirm that POST values are present before accessing them.
      if(isset($_POST["firstname"])){
        $firstname = sanitize_input($_POST["firstname"]);
      }
      if(isset($_POST["lastname"])){
        $lastname = sanitize_input($_POST["lastname"]);
      }
      if(isset($_POST["username"])){
        $phone_no = sanitize_input($_POST["username"]);
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
      if(is_blank($username)){
        $errors[] = "Username cannot be blank";
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
      if(!has_length($username, array('min'=> 1, 'max'=>255))){
        $errors[] = "username must be less than 255 characters";
        $i++;
      }

   // if there were no errors, submit data to database
  if(empty($errors)){
    $db = db_connect();
    $now = date("Y-m-d H:i:s");
    // Write SQL INSERT statement
    $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `username`, `created_at`) VALUES ('$firstname', '$lastname', '$email', '$username', '$now')";

    // For INSERT statments, $result is just true/false
       $result = db_query($db, $sql);
      if($result === true) {
        $new_id = db_insert_id($db);
        redirect_to('show.php?id=' . $new_id);
      } else {
        $errors = $result;
      }
   }
}
?>
<?php $page_title = 'Staff: New User'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Users List</a><br />

  <h1>New User</h1>

  <?php echo display_errors($errors); ?>

  <form action="new.php" method="post">
    First name:<br />
    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" /><br />
    Last name:<br />
    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" /><br />
    Username:<br />
    <input type="text" name="username" value="<?php echo $user['username']; ?>" /><br />
    Email:<br />
    <input type="text" name="email" value="<?php echo $user['email']; ?>" /><br />
    <br />
    <input type="submit" name="submit" value="Create"  />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
