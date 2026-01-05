<?php
require_once "includes/db.php";
include "includes/header.php";
?>

<?php if (isset($_SESSION['show_activation_popup'])): ?>
<!-- ✅ Bootstrap Modal (σωστό) -->
<div class="modal fade show" id="activationModal" tabindex="-1" aria-modal="true" role="dialog" style="display:block;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
     style="
       background: rgba(20,20,20,0.9);
       color: #f1f1f1;
       backdrop-filter: blur(14px);
       -webkit-backdrop-filter: blur(14px);
       border: 1px solid rgba(255,255,255,0.15);
       box-shadow: 0 15px 40px rgba(0,0,0,0.7);
     ">

      <div class="modal-header">
        <h5 class="modal-title">Ενεργοποίηση Λογαριασμού</h5>
      </div>

      <div class="modal-body">
        <p>
          Η εγγραφή σας ολοκληρώθηκε επιτυχώς 🎉<br><br>

          <?php if (isset($_SESSION['activation_code_preview'])): ?>
            <strong>Κωδικός Ενεργοποίησης:</strong>
            <span class="fw-bold text-primary">
              <?= htmlspecialchars($_SESSION['activation_code_preview']) ?>
            </span><br><br>
          <?php endif; ?>

          Για να ενεργοποιήσετε τον λογαριασμό σας,
          <a href="activate.php" class="fw-bold text-primary">πατήστε εδώ</a>.
        </p>
      </div>

      <div class="modal-footer">
        <a href="activate.php" class="btn btn-warning">Ενεργοποίηση</a>
      </div>

    </div>
  </div>
</div>

<?php
unset($_SESSION['show_activation_popup'], $_SESSION['activation_code_preview']);
endif;
?>

<!-- ✅ ΚΥΡΙΟ ΠΕΡΙΕΧΟΜΕΝΟ (ΗΔΗ ΜΕΣΑ ΣΤΟ #app-content από το header.php) -->
<div class="container mt-5">

  <div class="card shadow fade-in mb-5">
    <div class="card-body">
      <?php include "includes/search_form.php"; ?>
    </div>
  </div>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="alert alert-info text-center shadow fade-in">
      🔐 Για να καταχωρήσετε αγγελία οχήματος,<br>
      <a href="login.php" class="fw-bold">συνδεθείτε</a>
      ή
      <a href="register.php" class="fw-bold">δημιουργήστε λογαριασμό</a>.
    </div>
  <?php else: ?>
    <div class="alert alert-success text-center shadow fade-in">
      ✅ Είστε συνδεδεμένος και μπορείτε να καταχωρήσετε αγγελία.
      <br>
      <a href="add_car.php" class="btn btn-primary mt-3">
        ➕ Καταχώρηση Αυτοκινήτου
      </a>
    </div>
  <?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>
