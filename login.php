<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$message = "";

/* cleanup flags */
unset($_SESSION['needs_activation']);
unset($_SESSION['show_activation_popup']);
unset($_SESSION['pending_username']);
unset($_SESSION['activation_code_preview']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "<div class='alert alert-danger'>Συμπλήρωσε όλα τα πεδία.</div>";
    } else {

        $password_hash = hash("sha512", $password);

        $stmt = $pdo->prepare("
            SELECT id, username, is_active
            FROM users
            WHERE username = ? AND password_hash = ?
        ");
        $stmt->execute([$username, $password_hash]);

        if ($stmt->rowCount() === 0) {
            $message = "<div class='alert alert-danger'>Λάθος username ή κωδικός.</div>";
        } else {

            $user = $stmt->fetch();

            if ((int)$user["is_active"] === 0) {
                $message = "
                    <div class='alert alert-warning'>
                        Ο λογαριασμός δεν έχει ενεργοποιηθεί.<br>
                        <a href='activate.php' class='fw-bold'>Ενεργοποίησέ τον εδώ</a>
                    </div>
                ";
            } else {
                $_SESSION["user_id"]  = (int)$user["id"];
                $_SESSION["username"] = $user["username"];

                header("Location: index.php");
                exit;
            }
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<!-- ✅ ΟΛΟ ΤΟ ΠΕΡΙΕΧΟΜΕΝΟ ΜΕΣΑ ΣΤΟ app-content -->
<div id="app-content">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow fade-in">
          <div class="card-body">
            <h3 class="text-center mb-4">Σύνδεση Χρήστη</h3>

            <?= $message ?>

            <form method="post">
              <div class="mb-3">
                <label class="form-label">Username/Ψευδώνυμο</label>
                <input type="text" name="username" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Κωδικός</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

</div> <!-- /#app-content -->

<?php include "includes/footer.php"; ?>
