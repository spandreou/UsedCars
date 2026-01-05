<?php
require_once "includes/auth.php";
require_once "includes/db.php";

$message = "";

/* =========================
   HANDLE POST (LOGIC ONLY)
   ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $brand  = trim($_POST["brand"] ?? "");
    $model  = trim($_POST["model"] ?? "");
    $body_type = $_POST["body_type"] ?? "";
    $engine_cc = (int)($_POST["engine_cc"] ?? 0);
    $fuel_type = $_POST["fuel_type"] ?? "";
    $mileage = (int)($_POST["mileage"] ?? 0);
    $first_license_year = (int)($_POST["first_license_year"] ?? 0);
    $price = (float)($_POST["price"] ?? 0);

    $has_turbo    = isset($_POST["has_turbo"]) ? 1 : 0;
    $is_hybrid    = isset($_POST["is_hybrid"]) ? 1 : 0;
    $needs_repair = isset($_POST["needs_repair"]) ? 1 : 0;

    $allowed_body = ["mini", "hatchback", "sedan", "SUV"];
    $allowed_fuel = ["petrol", "diesel", "hybrid", "electric"];

    if ($brand === "" || $model === "" || $body_type === "" || $fuel_type === "") {
        $message = "Συμπλήρωσε όλα τα υποχρεωτικά πεδία.";
    } elseif (!in_array($body_type, $allowed_body, true)) {
        $message = "Μη έγκυρος τύπος αμαξώματος.";
    } elseif (!in_array($fuel_type, $allowed_fuel, true)) {
        $message = "Μη έγκυρο καύσιμο.";
    } elseif ($engine_cc <= 0 || $mileage < 0) {
        $message = "Λάθος κυβικά ή χιλιόμετρα.";
    } elseif ($first_license_year < 1950 || $first_license_year > (int)date("Y")) {
        $message = "Μη έγκυρο έτος πρώτης άδειας.";
    } elseif ($price <= 0) {
        $message = "Η τιμή πρέπει να είναι > 0.";
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO cars
            (user_id, brand, model, body_type, engine_cc, fuel_type,
             mileage, first_license_year, has_turbo, is_hybrid, needs_repair, price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION["user_id"],
            $brand,
            $model,
            $body_type,
            $engine_cc,
            $fuel_type,
            $mileage,
            $first_license_year,
            $has_turbo,
            $is_hybrid,
            $needs_repair,
            $price
        ]);

        header("Location: add_car_images.php?car_id=" . $pdo->lastInsertId());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Καταχώρηση Αυτοκινήτου</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include "includes/header.php"; ?>

<div id="app-content">

  <div class="container mt-5 fade-in">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">

        <!-- ✅ GLASS CARD -->
        <div class="card add-car-card p-4">
          <div class="card-body">

            <h3 class="text-center mb-4">Καταχώρηση Αυτοκινήτου</h3>

            <?php if ($message): ?>
              <div class="alert alert-danger">
                <?= htmlspecialchars($message) ?>
              </div>
            <?php endif; ?>

            <form method="post" novalidate>

              <div class="mb-3">
                <label class="form-label">Μάρκα</label>
                <input class="form-control" name="brand" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Μοντέλο</label>
                <input class="form-control" name="model" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Τύπος Αμαξώματος</label>
                <select class="form-select" name="body_type" required>
                  <option value="">Επίλεξε…</option>
                  <option value="mini">Mini</option>
                  <option value="hatchback">Hatchback</option>
                  <option value="sedan">Sedan</option>
                  <option value="SUV">SUV</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Κυβικά (cc)</label>
                <input class="form-control" type="number" name="engine_cc" min="1" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Καύσιμο</label>
                <select class="form-select" name="fuel_type" required>
                  <option value="">Επίλεξε…</option>
                  <option value="petrol">Βενζίνη</option>
                  <option value="diesel">Πετρέλαιο</option>
                  <option value="hybrid">Υβριδικό</option>
                  <option value="electric">Ηλεκτρικό</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Χιλιόμετρα</label>
                <input class="form-control" type="number" name="mileage" min="0" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Πρώτη άδεια (έτος)</label>
                <input
                  class="form-control"
                  type="number"
                  name="first_license_year"
                  min="1950"
                  max="<?= date('Y') ?>"
                  required
                >
              </div>

              <div class="mb-3">
                <label class="form-label">Τιμή (€)</label>
                <input
                  class="form-control"
                  type="number"
                  step="0.01"
                  name="price"
                  min="0.01"
                  required
                >
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="has_turbo" id="has_turbo">
                  <label class="form-check-label" for="has_turbo">Έχει Turbo</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_hybrid" id="is_hybrid">
                  <label class="form-check-label" for="is_hybrid">Είναι Υβριδικό</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="needs_repair" id="needs_repair">
                  <label class="form-check-label" for="needs_repair">Χρειάζεται επισκευή</label>
                </div>
              </div>

              <button class="btn btn-primary w-100">
                Καταχώρηση
              </button>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

</div> <!-- /#app-content -->

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
