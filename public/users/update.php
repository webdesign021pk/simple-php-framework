<?php
// public/users/update.php

include_once(__DIR__ . '/../resources/layout/header_main.php');
$page_title = "Update User";
include_once(__DIR__ . '/../resources/layout/navigation_main.php');

require_once(REQUESTS_PATH . '/users/User.php');

$id = clean_input($_GET['id']);
$user = (new User)->find($id);
?>

<div class="container py-4">

    <?php
    if (!$user) {
        echo "<div class='alert alert-danger'>User not found.</div>";
        include_once(__DIR__ . '/../resources/layout/footer_main.php');
        exit;
    }
    ?>

    <form method="POST" action="<?= request_url('users/update.php') ?>" id="updateUserForm">

        <!-- Add the user ID as a hidden field -->
        <input type="hidden" name="id" value="<?= value($user, 'id') ?>" />

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= value($user, 'username') ?>" required>
            <span id="usernameError" class="text-danger"><?= Message::getError('User@update','username') ?></span>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?= value($user, 'email') ?>" required>
            <span id="emailError" class="text-danger"><?= Message::getError('User@update','email') ?></span>
        
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= value($user, 'full_name') ?>">
            <span id="full_nameError" class="text-danger"><?= Message::getError('User@update','full_name') ?></span>
        
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role">
                <option value="admin" <?= value($user, 'role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= value($user, 'role') === 'user' ? 'selected' : '' ?>>User</option>
            </select>
            <span id="roleError" class="text-danger"><?= Message::getError('User@update','role') ?></span>

        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="1" <?= value($user, 'status') == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= value($user, 'status') == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
            <span id="statusError" class="text-danger"><?= Message::getError('User@update','status') ?></span>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
        <div class="row mb-3">
           <?=Message::getMessage('User@update')?>
        </div>
    </form>
</div>

<?php
include_once(__DIR__ . '/../resources/layout/footer_main.php');
?>