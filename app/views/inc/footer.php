<footer id="footer" class="footer">
    <div class="copyright">&copy; Copyright <strong><span><?= SITENAME; ?></span></strong>.</div>
    <div class="credits">Designed by <a href="#">TeraCorporation on chon @copyright e todos direitos reservados </a></div>
</footer>

<?php if (isset($_SESSION['user_id']) && in_array('use_chat', $_SESSION['user_permissions'])): ?>
    <!-- Botão Flutuante do Chat -->
    <a href="#" id="chat-toggle-button" class="chat-toggle-btn d-flex align-items-center justify-content-center">
        <i class="bi bi-chat-left-text"></i>
    </a>

    <!-- Container do Widget de Chat -->
    <div id="chat-container" class="chat-widget-container"></div>
<?php endif; ?>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="<?= URLROOT; ?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/quill/quill.min.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/tinymce/tinymce.min.js"></script>
<!-- Timeago.js para formatação de tempo relativo -->
<script src="https://cdn.jsdelivr.net/npm/timeago.js@4.0.2/dist/timeago.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/timeago.js@4.0.2/dist/timeago.locales.min.js"></script>
<script src="<?= URLROOT; ?>/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS Files -->
<script src="<?= URLROOT; ?>/assets/js/main.js"></script>
<script src="<?= URLROOT; ?>/assets/js/forms-validation.js"></script>
<script src="<?= URLROOT; ?>/assets/js/chat.js"></script>
<script src="<?= URLROOT; ?>/assets/js/chat-widget.js"></script>
</body>

</html>
