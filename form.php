<?php
$nameErr = $emailErr = $genderErr = $phoneErr = $passwordErr = $confirmErr = $termsErr = $websiteErr = "";
$name = $email = $website = $gender = $phone = "";
$password = $confirmPassword = "";
$submitted = false;

// For submission counter
if (!isset($_POST['attempt'])) {
    $attempt = 0;
} else {
    $attempt = (int)$_POST['attempt'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
    $attempt++;

    // Name validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Website validation (Exercise 2 - show error if invalid)
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    // Phone validation (Exercise 1 - required)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format (digits, spaces, dashes, or +)";
        }
    }

    // Password validation (Exercise 3)
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }
    }

    // Confirm password (Exercise 3)
    if (empty($_POST["confirm_password"])) {
        $confirmErr = "Please confirm your password";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if ($password !== $confirmPassword) {
            $confirmErr = "Passwords do not match";
        }
    }

    // Terms checkbox (Exercise 4)
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }

    // Gender validation
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$formValid = $submitted && empty($nameErr) && empty($emailErr) && empty($genderErr) && 
             empty($phoneErr) && empty($passwordErr) && empty($confirmErr) && empty($termsErr) && 
             empty($websiteErr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern PHP Form</title>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --error-red: #ef4444;
            --success-green: #10b981;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px;
        }

        h2 {
            margin: 0 0 8px 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .required-note {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .attempt-badge {
            background: #f3f4f6;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 20px;
            color: var(--text-muted);
        }

        .field-row {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 6px;
            display: block;
        }

        input[type="text"], 
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .radio-item input {
            margin-right: 8px;
            accent-color: var(--primary-color);
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-item input {
            width: auto;
            margin: 0;
        }

        .error {
            color: var(--error-red);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .hint {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        button[type="submit"] {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        .success-box, .output-box {
            margin-top: 24px;
            padding: 16px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .success-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .output-box {
            background-color: #f3f4f6;
            border: 1px solid var(--border-color);
        }

        .output-box h3 {
            margin-top: 0;
            font-size: 1rem;
            color: var(--text-main);
        }

        .output-box p {
            margin: 4px 0;
            color: var(--text-muted);
        }

        .output-box strong {
            color: var(--text-main);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Get in Touch</h2>
    <p class="required-note">Fields marked with <span style="color:var(--error-red)">*</span> are required.</p>
    
    <!-- Exercise 5: Counter display -->
    <div class="attempt-badge">
        📝 Submission attempt: <?= $attempt ?>
    </div>

    <?php if ($formValid): ?>
        <div class="success-box">
            ✓ Form submitted successfully!
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <!-- Exercise 5: Hidden counter field -->
        <input type="hidden" name="attempt" value="<?= $attempt ?>">

        <div class="field-row">
            <label for="name">Name <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="name" name="name" placeholder="Jane Doe" value="<?= $name ?>">
            <?php if($nameErr): ?><span class="error"><?= $nameErr ?></span><?php endif; ?>
        </div>

        <div class="field-row">
            <label for="email">E-mail <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="email" name="email" placeholder="jane@example.com" value="<?= $email ?>">
            <?php if($emailErr): ?><span class="error"><?= $emailErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 1: Phone Number Field -->
        <div class="field-row">
            <label for="phone">Phone Number <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="phone" name="phone" placeholder="+1 234-567-8900" value="<?= $phone ?>">
            <?php if($phoneErr): ?><span class="error"><?= $phoneErr ?></span><?php endif; ?>
            <div class="hint">Format: digits, spaces, dashes, or + (7-15 characters)</div>
        </div>

        <!-- Exercise 2: Website with error message -->
        <div class="field-row">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" placeholder="https://example.com" value="<?= $website ?>">
            <?php if($websiteErr): ?><span class="error"><?= $websiteErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 3: Password and Confirm Password -->
        <div class="field-row">
            <label for="password">Password <span style="color:var(--error-red)">*</span></label>
            <input type="password" id="password" name="password">
            <?php if($passwordErr): ?><span class="error"><?= $passwordErr ?></span><?php endif; ?>
            <div class="hint">Minimum 8 characters</div>
        </div>

        <div class="field-row">
            <label for="confirm_password">Confirm Password <span style="color:var(--error-red)">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password">
            <?php if($confirmErr): ?><span class="error"><?= $confirmErr ?></span><?php endif; ?>
        </div>

        <div class="field-row">
            <label>Gender <span style="color:var(--error-red)">*</span></label>
            <div class="radio-group">
                <label class="radio-item"><input type="radio" name="gender" value="Female" <?= ($gender == "Female") ? "checked" : "" ?>> Female</label>
                <label class="radio-item"><input type="radio" name="gender" value="Male" <?= ($gender == "Male") ? "checked" : "" ?>> Male</label>
                <label class="radio-item"><input type="radio" name="gender" value="Other" <?= ($gender == "Other") ? "checked" : "" ?>> Other</label>
            </div>
            <?php if($genderErr): ?><span class="error"><?= $genderErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 4: Terms and Conditions Checkbox -->
        <div class="field-row checkbox-item">
            <input type="checkbox" id="terms" name="terms" <?= isset($_POST['terms']) ? "checked" : "" ?>>
            <label for="terms">I agree to the Terms and Conditions <span style="color:var(--error-red)">*</span></label>
        </div>
        <?php if($termsErr): ?><span class="error"><?= $termsErr ?></span><?php endif; ?>

        <button type="submit">Send Message</button>
    </form>

    <div class="output-box">
        <?php if ($submitted && $formValid): ?>
            <h3>Your Input:</h3>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>E-mail:</strong> <?= $email ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <?php if (!empty($website)): ?><p><strong>Website:</strong> <?= $website ?></p><?php endif; ?>
            <p><strong>Gender:</strong> <?= $gender ?></p>
            <p><strong>Password:</strong> [hidden for security]</p>
        <?php elseif ($submitted && !$formValid): ?>
            <p style="color:var(--error-red); margin:0;">Please fix the errors and try again.</p>
        <?php else: ?>
            <p style="margin:0; font-style: italic;">Results will appear here after submission.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>