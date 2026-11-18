<div class="ban-overlay">

    <div class="ban-card">

        <h2 class="ban-title">Your account has been banned</h2>
        <p class="ban-text">An admin banned your account. This is the reason:</p>

        <div class="ban-reason">
            <?php echo htmlspecialchars($banReason); ?>
        </div>

        <a href="<?php echo BASE_URL; ?>/backend/form-actions/logout-action.php" class="ban-logout">Log Out</a>

    </div>

</div>
