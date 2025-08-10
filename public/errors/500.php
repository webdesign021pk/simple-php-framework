<?php
// public/errors/403.php
http_response_code(500);

include_once(__DIR__ . '/../resources/layout/header_main.php');
$page_title = " Forbidden";

?>

<div class="container py-4 vh-100">

    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-center border border-danger">

        <div class="container">
            <h1 class="display-1 fw-bold text-primary">500</h1>
            <p class="fs-3"><span class="text-primary">Internal Server Error</span> – Something went wrong.</p>
            <a href="<?= base_url('/') ?>" class="btn btn-primary mt-3">Go Back Home</a>
        </div>

    </div>

</div>

<?php
include_once(__DIR__ . '/../resources/layout/footer_main.php');
?>