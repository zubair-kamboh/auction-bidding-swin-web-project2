<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- REGISTER THE USER - REDIRECT TO BIDDING.PHP - SAVE USER IN SESSION -->
<?php
session_start();

if (isset($_SESSION['user_email'])) {
  header("Location: bid.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["First_Name"];
    $surName = $_POST["Sur_Name"];
    $email = $_POST["email"];
    $password = $_POST["Password"];
    $confirmPassword = $_POST["ConfirmPassword"];

    if(filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
      echo "Email invalid";
      exit;
    }

    if ($password != $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        if (!file_exists('./customer.xml')) {
          $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><customers></customers>');

        } else {
            $xml = simplexml_load_file('./customer.xml');
            foreach ($xml->customer as $customer) {
              if ($customer->Email == $email) {
                  echo "The email is already registered.";
                  exit; 
              }
            }
        }

        $customer = $xml->addChild('customer');
        $customerId = uniqid();
        $customer->addChild('CustomerId', $customerId);
        $customer->addChild('FirstName', $firstName);
        $customer->addChild('SurName', $surName);
        $customer->addChild('Email', $email);
        $customer->addChild('Password', $password); 

        $xml->asXML('./customer.xml');

        $_SESSION['customer_id'] = $customerId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_first_name'] = $firstName;
        $_SESSION['user_sur_name'] = $surName;

        // SEND EMAIL
        $to = $email;
        $subject = "Welcome to ShopOnline!";
        $message = "Dear $firstName, welcome to use ShopOnline! Your customer id is $email and the password is $password.";
        $headers = "From: registration@shoponline.com.au";
        mail($to, $subject, $message, $headers);
        
        header("Location: bid.php");
        exit; 
    }
}
?>

<!-- HTML CODE -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShopOnline - Register</title>
    <link rel="stylesheet" href="register.css">
  </head>
  <body>
    
    <header>
      <h1 class="logo">ShopOnline</h1>
      <div class="navbar">
        <a href="./login.php">Home</a>
        <a href="./createListing.php">Listing</a>
        <a href="./bid.php">Bidding</a>
        <a href="./maintenence.php">Maintenence</a>
        <a href="./login.php">Login</a>
      </div>
      <hr />
    </header>
    <main>
      <p class="loginRegText">Registration Details</p>
      <div class="loginbox">
        <p class="requiredFieldText">Required Fields</p>
        <form  method="post" action="register.php">
          <div>
            <label for="First Name">First Name:</label>
            <input type="text" name="First Name" id="FirstName" required="true"/>
          </div>
          <div>
            <label for="Sur Name">SurName:</label>
            <input type="text" name="Sur Name" id="SurName" required="true"/>
          </div>
          <div>
            <label for="Email">Email:</label>
            <input type="email" name="email" id="email" required="true"/>
          </div>
          <div>
            <label for="Password">Password:</label>
            <input type="password" name="Password" id="Password" required="true"/>
          </div>
          <div>
            <label for="ConfirmPassword">Confirm Password:</label>
            <input
              type="password"
              name="ConfirmPassword"
              id="ConfirmPassword"
              required="true"
            />
          </div>

          <div
            style="
              display: flex;
              margin: 10px 0px;
              align-items: center;
              justify-content: center;
            "
          >
            <button class="loginBtn" type="submit">Register</button>
            <button class="resetBtn" type="reset">Reset</button>
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
