<?php
$name = $email = $gender = $comment = $website = $phone = $password = $confirm_password = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirm_passwordErr = $termsErr = "";
$successMessage = "";


if (!isset($_POST['submit_attempt'])) {
    $attempt = 0;
} else {
    $attempt = (int)$_POST['submit_attempt'];
}


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$isValid = true;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $attempt++;
    
    
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $isValid = false;
    } else {
        $name = test_input($_POST["name"]);
        
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
            $isValid = false;
        }
    }

    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $isValid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $isValid = false;
        }
    }

    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
        $isValid = false;
    } else {
        $gender = test_input($_POST["gender"]);
    }

    
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
        $isValid = false;
    } else {
        $phone = test_input($_POST["phone"]);
        
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format. Use digits, spaces, dashes, or leading + (7-15 characters)";
            $isValid = false;
        }
    }

    
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format (e.g., https://example.com)";
            $isValid = false;
        }
    } else {
        $website = ""; // optional, so empty is fine
    }

    
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $isValid = false;
    } else {
        $password = $_POST["password"]; 
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
            $isValid = false;
        }
    }

    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "Please confirm your password";
        $isValid = false;
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            $confirm_passwordErr = "Passwords do not match";
            $isValid = false;
        }
    }

    
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
        $isValid = false;
    }

    
    if (!empty($_POST["comment"])) {
        $comment = test_input($_POST["comment"]);
    }

    
    if ($isValid) {
        $successMessage = "Form submitted successfully!";
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-left: 10px;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            padding: 10px;
            background: #e6ffe6;
            border-radius: 4px;
        }
        input[type="text"], input[type="email"], input[type="url"], input[type="tel"], input[type="password"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            margin-right: 8px;
        }
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        hr {
            margin: 20px 0;
        }
        .attempt-counter {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>PHP Form Validation Lab</h2>

    
    <div class="attempt-counter">
        <strong>Submission attempt: <?php echo $attempt; ?></strong>
    </div>

    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        
        <input type="hidden" name="submit_attempt" value="<?php echo $attempt; ?>">

        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error">* <?php echo $nameErr; ?></span>
        </div>

        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error">* <?php echo $emailErr; ?></span>
        </div>

        
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="e.g., +1 234-567-8900">
            <span class="error">* <?php echo $phoneErr; ?></span>
        </div>

        
        <div class="form-group">
            <label for="website">Website (optional):</label>
            <input type="url" id="website" name="website" value="<?php echo htmlspecialchars($website); ?>" placeholder="https://example.com">
            <span class="error"><?php echo $websiteErr; ?></span>
        </div>


        <div class="form-group">
            <label>Gender:</label><br>
            <input type="radio" id="female" name="gender" value="female" <?php if (isset($gender) && $gender == "female") echo "checked"; ?>>
            <label for="female" style="font-weight:normal;">Female</label>
            <input type="radio" id="male" name="gender" value="male" <?php if (isset($gender) && $gender == "male") echo "checked"; ?>>
            <label for="male" style="font-weight:normal;">Male</label>
            <input type="radio" id="other" name="gender" value="other" <?php if (isset($gender) && $gender == "other") echo "checked"; ?>>
            <label for="other" style="font-weight:normal;">Other</label>
            <span class="error">* <?php echo $genderErr; ?></span>
        </div>

        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <span class="error">* <?php echo $passwordErr; ?></span>
            <small style="display:block; color:#666;">Minimum 8 characters</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <span class="error">* <?php echo $confirm_passwordErr; ?></span>
        </div>

        
        <div class="form-group">
            <label for="comment">Comment (optional):</label>
            <textarea id="comment" name="comment" rows="4"><?php echo htmlspecialchars($comment); ?></textarea>
        </div>

        
        <div class="form-group">
            <input type="checkbox" id="terms" name="terms" <?php if (isset($_POST['terms'])) echo "checked"; ?>>
            <label for="terms">I agree to the terms and conditions</label>
            <span class="error">* <?php echo $termsErr; ?></span>
        </div>

        
        <div class="form-group">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>

    <hr>
    
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $isValid): ?>
        <div class="success">
            <?php echo $successMessage; ?>
            <h3>Your Submitted Data:</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <p><strong>Website:</strong> <?php echo htmlspecialchars($website); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>
            <p><strong>Comment:</strong> <?php echo htmlspecialchars($comment); ?></p>
            <p><strong>Password:</strong> [hidden for security]</p>
            <p><strong>Terms Accepted:</strong> Yes</p>
        </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !$isValid): ?>
        <div class="error" style="background:#ffe6e6; padding:10px; border-radius:4px;">
            <strong>Please fix the errors above and resubmit.</strong>
        </div>
    <?php endif; ?>
</div>

</body>
</html>