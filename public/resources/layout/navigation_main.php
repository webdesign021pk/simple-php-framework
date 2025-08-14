    <div class="bg-light shadow-sm py-2 d-print-none">

        <div class="container my-2">
            <header class="d-flex flex-wrap justify-content-center py-3">
                <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none pe-none" tabindex="-1" aria-disabled="true">
                    <!-- <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg> -->
                    <span class="fs-4"><?= $page_title ?></span>
                </a>

                <ul class="nav nav-pills">
                    <li class="nav-item text-light">
                        <a href="<?= base_url() ?>" class="nav-link <?= is_current_path('index.php') ?>" aria-current="page">
                            Home
                        </a>
                    </li>
                    <li class="nav-item text-light">
                        <a href="<?= base_url('about.php') ?>" class="nav-link <?= is_current_path('about.php') ?>" aria-current="page">
                            About
                        </a>
                    </li>
                    <!-- Dropdown Menu -->
                    <?php
                    if (Auth::isAuthenticated()) {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Users
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item <?= is_current_path('users/index.php') ?>" href="<?= base_url('users/index.php') ?>">
                                        List
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item <?= is_current_path('users/create.php') ?>" href="<?= base_url('users/create.php') ?>">
                                        Add New
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="<?= base_url('auth/logout.php') ?>" class="nav-link text-danger">Logout</a></li>
                    <?php } else {
                    ?>
                        <li class="nav-item"><a href="<?= base_url('auth/login.php') ?>" class="nav-link">Login</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </header>
        </div>
    </div>