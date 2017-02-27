<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return !isset($value) || trim($value) == '';
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    $length = strlen($value);
    if(isset($options['max']) && ($length > $options['max'])) {
      return false;
    } elseif(isset($options['min']) && ($length < $options['min'])) {
      return false;
    } elseif(isset($options['exact']) && ($length != $options['exact'])) {
      return false;
    } else {
      return true;
    }
  }

  // has_valid_email_format('test@test.com')
  function has_valid_email_format($value) {
    // Function can be improved later to check for
    // more than just '@'.
    return (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', $value) == 1 );
    //return (preg_match("/^[a-zA-Z0-9_-@.]+$/", $value) == 1);
  }

  function has_valid_username($value){
    return (preg_match("/^[a-zA-Z0-9 _]+$/", $value) == 1);
  }

  function has_valid_phone($value){
    return (preg_match("/^[1-90-9 ()]{0,15}$/", $value) == 1);
  }

  //My custom validation
  function has_valid_name($value){
    return (preg_match("/^[a-zA-Z-]+$/", $value) == 1);
  }

   //My custom validation
   function has_valid_state_code($value){
     return (preg_match("/^[a-zA-Z]{0,2}$/", $value) == 1);
   }

?>
