<?php
session_start();
require_once "includes/db.php";

/* =========================
   ΚΑΘΑΡΙΣΜΑ ACTIVATION FLAGS
   (μόνο όταν ανοίγει η φόρμα)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['needs_activation']);
    unset($_SESSION['pending_username']);
    unset($_SESSION['show_activation_popup']);

    // CAPTCHA ΜΟΝΟ σε GET
    $_SESSION['captcha_register'] = rand(1000, 9999);
}

$message = "";

// default values (κρατιούνται ΜΟΝΟ σε error)
$username = "";
$first_name = "";
$last_name = "";
$email = "";
$phone = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username   = trim($_POST["username"] ?? "");
    $password   = $_POST["password"] ?? "";
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name  = trim($_POST["last_name"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $phone      = trim($_POST["phone"] ?? "");
    $captcha    = trim($_POST["captcha"] ?? "");

    /* ❌ Validation */
    if (
        $username === "" || $password === "" ||
        $first_name === "" || $last_name === "" ||
        $email === "" || $phone === "" || $captcha === ""
    ) {
        $message = "<div class='alert alert-danger'>Συμπλήρωσε όλα τα πεδία.</div>";

    } elseif (
        !isset($_SESSION['captcha_register']) ||
        $captcha !== (string)$_SESSION['captcha_register']
    ) {
        $message = "<div class='alert alert-danger'>Λάθος CAPTCHA.</div>";

    } elseif (strlen($password) < 10) {
        $message = "<div class='alert alert-danger'>Ο κωδικός πρέπει να έχει τουλάχιστον 10 χαρακτήρες.</div>";

    } else {

        /* ❌ Duplicate check */
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
            $message = "<div class='alert alert-warning'>Username ή Email υπάρχει ήδη.</div>";

        } else {

            /* ✅ ΕΠΙΤΥΧΗΣ ΕΓΓΡΑΦΗ */
            $password_hash   = hash("sha512", $password);
            $activation_code = rand(10000, 99999);

            $stmt = $pdo->prepare("
                INSERT INTO users
                (username, password_hash, first_name, last_name, email, phone, activation_code)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $username,
                $password_hash,
                $first_name,
                $last_name,
                $email,
                $phone,
                $activation_code
            ]);

            // Activation flags
            $_SESSION['needs_activation']        = true;
            $_SESSION['pending_username']        = $username;
            $_SESSION['activation_code_preview'] = $activation_code;
            $_SESSION['show_activation_popup']   = true;

            unset($_SESSION['captcha_register']);

            header("Location: index.php");
            exit;
        }
    }

    // νέο CAPTCHA σε αποτυχία
    $_SESSION['captcha_register'] = rand(1000, 9999);
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Εγγραφή | Used Cars</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include "includes/header.php"; ?>

<div class="container mt-5 fade-in">
    <div class="row justify-content-center auth-row">
        <div class="col-12">

            <div class="card register-card shadow p-4">
                <div class="card-body">

                    <h3 class="text-center mb-4">Εγγραφή Χρήστη</h3>

                    <?= $message ?>

                    <form method="post" novalidate>

                        <div class="mb-3">
                            <label class="form-label">Username / Ψευδώνυμο</label>
                            <input type="text" name="username" class="form-control"
                                   value="<?= htmlspecialchars($username) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Κωδικός</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Όνομα</label>
                            <input type="text" name="first_name" class="form-control"
                                   value="<?= htmlspecialchars($first_name) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Επώνυμο</label>
                            <input type="text" name="last_name" class="form-control"
                                   value="<?= htmlspecialchars($last_name) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Τηλέφωνο</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= htmlspecialchars($phone) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                CAPTCHA: <strong><?= $_SESSION['captcha_register'] ?></strong>
                            </label>
                            <input type="text" name="captcha" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Εγγραφή
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
