<?php
require_once "includes/auth.php";
require_once "includes/db.php";

$car_id = (int)($_GET['car_id'] ?? 0);

// 🔐 ownership check
$stmt = $pdo->prepare("SELECT id FROM cars WHERE id = ? AND user_id = ?");
$stmt->execute([$car_id, $_SESSION['user_id']]);
if ($stmt->rowCount() === 0) {
    header("Location: index.php");
    exit;
}

$message = "";

$MIN_IMAGES = 2;
$MAX_IMAGES = 8;
$MAX_SIZE   = 250 * 1024;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {

    $files = array_filter($_FILES['images']['tmp_name']);
    $totalSelected = count($files);

    if ($totalSelected < $MIN_IMAGES) {
        $message = "<div class='alert alert-danger'>
            Πρέπει να ανεβάσετε τουλάχιστον {$MIN_IMAGES} εικόνες.
        </div>";
    } elseif ($totalSelected > $MAX_IMAGES) {
        $message = "<div class='alert alert-danger'>
            Μπορείτε να ανεβάσετε έως {$MAX_IMAGES} εικόνες.
        </div>";
    } else {

        $uploaded = 0;
        $errors   = [];

        foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {

            if ($tmpName === '') continue;

            $size = $_FILES['images']['size'][$i];
            $mime = mime_content_type($tmpName);

            $originalName = $_FILES['images']['name'][$i];
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if (!in_array($extension, ['jpg', 'jpeg'])) {
    $errors[] = "Κάποια εικόνα δεν είναι JPG.";
    continue;
}


            if ($size > $MAX_SIZE) {
                $errors[] = "Κάποια εικόνα ξεπερνά τα 250KB.";
                continue;
            }

            $filename = uniqid("car_", true) . ".jpg";
            $uploadPath = __DIR__ . "/uploads/" . $filename;

            if (move_uploaded_file($tmpName, $uploadPath)) {

                $stmt = $pdo->prepare("
                    INSERT INTO car_images (car_id, file_name, caption)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([
                    $car_id,
                    $filename,
                    trim($_POST['caption'] ?? '')
                ]);

                $uploaded++;
            }
        }

        if ($uploaded >= $MIN_IMAGES) {
            $message = "<div class='alert alert-success'>
                Ανέβηκαν επιτυχώς {$uploaded} εικόνες ✔
            </div>";
        } else {
            $message = "<div class='alert alert-danger'>
                Δεν ανέβηκαν αρκετές έγκυρες εικόνες.
            </div>";
        }

        if (!empty($errors)) {
            $message .= "<div class='alert alert-warning mt-2'>" .
                        implode("<br>", array_unique($errors)) .
                        "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Μεταφόρτωση Εικόνων</title>

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

            <h4 class="text-center mb-3">Μεταφόρτωση Εικόνων</h4>

            <p class="text-center small mb-4">
              Εικόνες JPG, έως 250KB (<?= $MIN_IMAGES ?>–<?= $MAX_IMAGES ?>)
            </p>

            <?= $message ?>

            <form method="post" enctype="multipart/form-data">

              <div class="mb-3">
                <input
                  type="file"
                  name="images[]"
                  multiple
                  accept=".jpg,.jpeg"
                  class="form-control"
                  required
                >
              </div>

              <div class="mb-3">
                <label class="form-label">Λεζάντα (προαιρετική)</label>
                <input type="text" name="caption" class="form-control">
              </div>

              <button class="btn btn-primary w-100 mb-3">
                Upload
              </button>

              <a
                href="car_view.php?id=<?= $car_id ?>"
                class="btn btn-outline-secondary w-100"
              >
                Προβολή οχήματος
              </a>

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
