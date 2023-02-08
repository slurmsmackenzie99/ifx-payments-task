<html>
  <head>
    <title>Bank Account and Payment</title>
  </head>
  <body>
    <h1>Bank Account and Payment</h1>
    <form action="index.php" method="post">
      <label for="account_number">Account Number:</label>
      <input type="text" id="account_number" name="account_number"><br><br>
      <label for="amount">Amount:</label>
      <input type="text" id="amount" name="amount"><br><br>
      <label for="currency">Currency:</label>
      <input type="text" id="currency" name="currency"><br><br>
      <input type="submit" name="submit" value="Add Money">
      <input type="submit" name="submit" value="Make Payment">
    </form>
    <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include 'src/Bank.php';

        $conn = mysqli_connect("localhost", "root", "", "bank");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $bank = new Bank();

        $account_number = $_POST['account_number'];
        $amount = $_POST['amount'];
        $currency = $_POST['currency'];
        $button_value = "";

        if (array_key_exists('submit', $_POST)) {
          $button_value = $_POST['submit'];
        }

        if ($button_value === 'Add Money') {
          $bank->addMoney($account_number, $amount, $currency);
          echo "Money added successfully";
        } elseif ($button_value === 'Make Payment') {
          $payment_made = $bank->makePayment($account_number, $amount, $currency);
          if ($payment_made) {
              echo "Payment made successfully";
          } else {
              echo "Cannot make more than 3 payments per day";
          }
        }

        mysqli_close($conn);
      }
    ?>
  </body>
</html>