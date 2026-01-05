<?php
require_once "includes/db.php";
require_once "includes/auth.php";
include "includes/header.php";

$car_id = (int)($_GET['id'] ?? 0);

/* Φόρτωση οχήματος + πωλητή */
$stmt = $pdo->prepare("
    SELECT c.*, u.username
    FROM cars c
    JOIN users u ON c.user_id = u.id
    WHERE c.id = ?
");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Δεν βρέθηκε όχημα.");
}

/* Φόρτωση εικόνων */
$stmt = $pdo->prepare("SELECT * FROM car_images WHERE car_id = ?");
$stmt->execute([$car_id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Ownership */
$is_owner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $car['user_id'];
?>

<style>
.car-hero img {
  max-height: 420px;
  object-fit: cover;
  width: 100%;
  transition: opacity .2s ease;
}
.car-thumb img {
  cursor: pointer;
  transition: transform .2s ease, opacity .2s ease;
}
.car-thumb img:hover {
  transform: scale(1.05);
}
</style>

<div class="container mt-5 fade-in">

  <div class="card shadow mb-4">
    <div class="card-body">

      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 class="mb-0">
            <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?>
          </h3>
          <small class="text-muted">
            Πωλητής: <?= htmlspecialchars($car['username']) ?>
          </small>
        </div>

        <?php if ($is_owner): ?>
          <a href="add_car_images.php?car_id=<?= $car_id ?>" class="btn btn-primary btn-sm">
            ➕ Προσθήκη εικόνων
          </a>
        <?php endif; ?>
      </div>

      <div class="row">

        <!-- LEFT: IMAGES -->
        <div class="col-md-7 mb-4">

          <?php if (!empty($images)): ?>

            <!-- HERO IMAGE -->
            <div class="car-hero mb-3">
              <img
                id="mainImage"
                src="uploads/<?= htmlspecialchars($images[0]['file_name']) ?>"
                data-src="<?= htmlspecialchars($images[0]['file_name']) ?>"
                class="img-fluid rounded shadow"
                alt="Κεντρική εικόνα οχήματος"
              >
            </div>

            <!-- THUMBNAILS -->
            <div class="row">
              <?php foreach ($images as $index => $img): ?>
                <?php if ($index === 0) continue; ?>

                <div class="col-4 car-thumb mb-3 text-center">
                  <img
                    src="uploads/<?= htmlspecialchars($img['file_name']) ?>"
                    data-src="<?= htmlspecialchars($img['file_name']) ?>"
                    class="img-fluid rounded shadow-sm thumb-img"
                    alt="<?= htmlspecialchars($img['caption'] ?? 'Εικόνα οχήματος') ?>"
                  >

                  <?php if ($is_owner): ?>
                    <div class="mt-1">
                      <a
                        href="delete_image.php?id=<?= $img['id'] ?>&car_id=<?= $car_id ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Να διαγραφεί η εικόνα;')"
                      >
                        ✖
                      </a>
                    </div>
                  <?php endif; ?>
                </div>

              <?php endforeach; ?>
            </div>

          <?php else: ?>
            <div class="alert alert-secondary">
              Δεν υπάρχουν εικόνες.
            </div>
          <?php endif; ?>

        </div>

        <!-- RIGHT: DETAILS -->
        <div class="col-md-5">

          <ul class="list-group mb-3">
            <li class="list-group-item">
              Τύπος: <strong><?= htmlspecialchars($car['body_type']) ?></strong>
            </li>
            <li class="list-group-item">
              Κυβικά: <?= $car['engine_cc'] ?> cc
            </li>
            <li class="list-group-item">
              Καύσιμο: <?= htmlspecialchars($car['fuel_type']) ?>
            </li>
            <li class="list-group-item">
              Χιλιόμετρα: <?= number_format($car['mileage']) ?>
            </li>
            <li class="list-group-item">
              Πρώτη άδεια: <?= $car['first_license_year'] ?>
            </li>
            <li class="list-group-item">
              Τιμή:
              <strong class="text-success fs-4">
                <?= number_format($car['price'], 2) ?> €
              </strong>
            </li>
            <li class="list-group-item">
              Turbo: <?= $car['has_turbo'] ? "Ναι" : "Όχι" ?> |
              Υβριδικό: <?= $car['is_hybrid'] ? "Ναι" : "Όχι" ?> |
              Επισκευή: <?= $car['needs_repair'] ? "Ναι" : "Όχι" ?>
            </li>
          </ul>

        </div>

      </div>

    </div>
  </div>

</div>

<script>
document.querySelectorAll('.thumb-img').forEach(thumb => {
  thumb.addEventListener('click', () => {

    const mainImg = document.getElementById('mainImage');

    // fade out
    mainImg.style.opacity = 0;

    setTimeout(() => {
      // swap src
      const tempSrc = mainImg.src;
      const tempData = mainImg.dataset.src;

      mainImg.src = thumb.src;
      mainImg.dataset.src = thumb.dataset.src;

      thumb.src = tempSrc;
      thumb.dataset.src = tempData;

      // fade in
      mainImg.style.opacity = 1;
    }, 150);
  });
});
</script>

<?php include "includes/footer.php"; ?>
