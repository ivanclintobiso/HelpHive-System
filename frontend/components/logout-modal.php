<div class="logout-overlay" id="logout-overlay">

    <div class="logout-card">

        <h2 class="logout-title">Log out of HelpHive?</h2>
        <p class="logout-text">Are you sure you want to log out?</p>

        <div class="logout-buttons">
            <button type="button" class="logout-cancel" id="logout-cancel">Cancel</button>
            <a href="<?php echo BASE_URL; ?>/backend/form-actions/logout-action.php" class="logout-confirm">Log Out</a>
        </div>

    </div>

</div>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/logout-modal.js"></script>