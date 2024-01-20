<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- LOGIN THE USER - REDIRECT LOGGED IN USERS - SAVE USER IN SESSION -->
<?php
session_start();

if (isset($_SESSION['user_email'])) {
  header("Location: bid.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (file_exists('./customer.xml')) {
        $xml = simplexml_load_file('./customer.xml');

        foreach ($xml->customer as $customer) {
            if ((string)$customer->Email == $email && (string)$customer->Password == $password) {
                $_SESSION['customer_id'] = (string)$customer->CustomerId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_first_name'] = (string)$customer->FirstName;
                $_SESSION['user_sur_name'] = (string)$customer->SurName;

                header("Location: bid.php");
                exit;
            }
        }
    }

    echo "Invalid email address or password. Please try again.";
}
?>

<!-- HTML CODE -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShopOnline - Login</title>
    <link rel="stylesheet" href="login.css">
  </head>
  <body>
    <header>
      <h1 class="logo">ShopOnline</h1>
      <div class="navbar">
        <a href="./login.php">Home</a><a href="./createListing.php">Listing</a><a  href="./bid.php">Bidding</a
        ><a href="./maintenence.php">Maintenence</a><a href="./register.php">Register</a>
      </div>
      <hr />
    </header>
    <main>
      <p class="loginRegText">
        Please login below or to register as a new user.
        <span> <a href="./register.php">Register Here</a> </span>
      </p>
      <div class="loginbox">
        <p class="logindetails">Login Details</p>
        <p class="requiredFieldText">Required Fields</p>
        <form action="login.php" method="post">
          <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" />
          </div>
          <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" />
          </div>
          <div style="display: flex; margin: 10px 0px; align-items: center">
            <button class="loginBtn" type="submit">Login</button>
            <button class="resetBtn" type="reset">Reset</button>
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
