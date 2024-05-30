<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check and clean the data submitted...
    
    // Validate name field
    if (empty($_POST["name"])) {
        $err_name = "* Name is required."; // Display error if no value submitted
    } else {
        $name = make_safe($_POST["name"]); // Clean data and make sure it's valid
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $err_name = "* Only letters and white space allowed"; 
        }
    }
    
    // Validate email field 
    if (empty($_POST["email"])) {
        $err_email = "* Email is required."; // Display error if no value submitted
    } else {
        $email = make_safe($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $err_email = "Email entered is invalid"; 
        }
    }
    
    // Validate message
    if (empty($_POST["message"])) {
        $err_msg = "* Message required."; // Display error if no value submitted
    } else {
        $msg = make_safe($_POST["message"]);
    }
    
    // If no errors found, send email
    if (empty($err_name) && empty($err_email) && empty($err_msg)) {
        // To
        $to = "youremailaddress@example.com";

        // Subject
        $sub = "Contact Form Submission";

        // Message
        $msg = "
        <html>
          <head>
            <title>Contact Form</title>
          </head>
          <body>
            <p>You have received a message through your contact form.</p>
            <table>
                <tr>
                    <td>Name: </td>
                    <td>$name</td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td>$email</td>
                </tr>
                <tr>
                    <td>Message: </td>
                    <td>$msg</td>
                </tr>
            </table>
          </body>
        </html>
        ";

        // Headers *** You MUST set the content type to send an HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <youremail@example.com>' . "\r\n";

        // Send the email
        mail($to,$sub,$msg,$headers);
        
        $success = "<h3>Your message has been sent!</h3>";
    } 
    
}

// Make input safe - prevents hacking 
function make_safe($value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return $value;
}

?>

<html>
    <head>
        <title>Example Contact Form</title>
    </head>
    <body>
        <h1>Contact Us</h1>
        <?php 
            if (!empty($success)) {
                echo $success;
            } else {
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <p><span class="error"><?php if (!empty($err_name)) echo $err_name; ?></span></p>
            <p>Name: <input type="text" name="name" value="<?php if (!empty($name)) echo $name; ?>"></p>
            
            <p><span class="error"><?php if (!empty($err_email)) echo $err_email; ?></span></p>
            <p>Email: <input type="text" name="email" value="<?php if (!empty($email)) echo $email; ?>"></p>
            
            <p><span class="error"><?php if (!empty($err_msg)) echo $err_msg; ?></span></p>
            <p>Message: <textarea name="message" rows="5" cols="40"><?php if (!empty($msg)) echo $msg; ?>"</textarea></p>
            
            <p><input type="submit" name="submit" value="Send"></p>  
        </form>
        <?php } ?>
    </body>    
</html>
