<?php
session_start();
require_once __DIR__ . "/includes/db.php";

/*
|--------------------------------------------------------------------------
| ⛔ Μπλοκάρουμε άμεση πρόσβαση ΜΟΝΟ σε GET
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_SESSION['needs_activation'])) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| CAPTCHA – ΜΟΝΟ ΣΕ GET
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['captcha_activate'] = rand(1000, 9999);
}

$prefill_username = $_SESSION['pending_username'] ?? "";
$message = "";

/*
|--------------------------------------------------------------------------
| POST LOGIC
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $code     = trim($_POST["code"] ?? "");
    $captcha  = trim($_POST["captcha"] ?? "");

    if ($username === "" || $code === "" || $captcha === "") {
        $message = "<div class='alert alert-danger'>Συμπλήρωσε όλα τα πεδία.</div>";

    } elseif (
        !isset($_SESSION['captcha_activate']) ||
        $captcha !== (string)$_SESSION['captcha_activate']
    ) {
        $message = "<div class='alert alert-danger'>Λάθος CAPTCHA.</div>";

    } else {

        $stmt = $pdo->prepare("
            SELECT id, activation_code, is_active
            FROM users
            WHERE username = ?
        ");
        $stmt->execute([$username]);

        if ($stmt->rowCount() === 0) {
            $message = "<div class='alert alert-danger'>Λάθος στοιχεία.</div>";
        } else {

            $user = $stmt->fetch();

            if ((int)$user["is_active"] === 1) {
                $message = "<div class='alert alert-info'>Ο λογαριασμός είναι ήδη ενεργός.</div>";

            } elseif ($user["activation_code"] != $code) {
                $message = "<div class='alert alert-danger'>Λάθος κωδικός ενεργοποίησης.</div>";

            } else {

                /* ✅ ΕΝΕΡΓΟΠΟΙΗΣΗ */
                $stmt = $pdo->prepare("
                    UPDATE users
                    SET is_active = 1
                    WHERE id = ?
                ");
                $stmt->execute([$user["id"]]);

                /* ✅ AUTO LOGIN */
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $username;

                /* 🧹 ΚΑΘΑΡΙΣΜΟΣ */
                unset(
                    $_SESSION['needs_activation'],
                    $_SESSION['pending_username'],
                    $_SESSION['show_activation_popup'],
                    $_SESSION['captcha_activate'],
                    $_SESSION['activation_code_preview']
                );

                header("Location: index.php");
                exit;
            }
        }
    }

    /* 🔁 ΝΕΟ CAPTCHA ΜΕΤΑ ΑΠΟ ΑΠΟΤΥΧΙΑ */
    $_SESSION['captcha_activate'] = rand(1000, 9999);
}
?>

<?php include "includes/header.php"; ?>

<div class="container mt-5 fade-in">
  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="card shadow">
        <div class="card-body">

          <h3 class="text-center mb-4">Ενεργοποίηση Λογαριασμού</h3>

          <?= $message ?>

          <form method="post">

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input
                type="text"
                name="username"
                class="form-control"
                value="<?= htmlspecialchars($prefill_username) ?>"
                required
              >
            </div>

            <div class="mb-3">
              <label class="form-label">Κωδικός Ενεργοποίησης</label>
              <input type="text" name="code" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">
                CAPTCHA:
                <strong><?= $_SESSION['captcha_activate'] ?></strong>
              </label>
              <input type="text" name="captcha" class="form-control" required>
            </div>

            <button class="btn btn-success w-100">
              Ενεργοποίηση
            </button>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
