<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    const BASE_URL = <?= json_encode(rtrim(base_url(), '/')) ?>;
    const WS_BASE_URL = "<?= esc(env('api.wsBaseURL')) ?>";
    const userID = <?= json_encode(session('user')['id'] ?? 0) ?>;
</script>
<script src="<?= base_url('js/sidebar.js') ?>"></script>
<script src="<?= base_url('js/alert.js') ?>"></script>
<script src="<?= base_url('js/notification.js') ?>"></script>
<script src="<?= base_url('js/websocket.js') ?>"></script>
<script src="<?= base_url('js/post.js') ?>"></script>
<script src="<?= base_url('js/utils.js') ?>"></script>
<script src="<?= base_url('js/like.js') ?>"></script>