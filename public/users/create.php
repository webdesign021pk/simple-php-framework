<?php
// public/users/create.php

include_once(__DIR__ . '/../resources/layout/header_main.php');
$page_title = "Create User";
include_once(__DIR__ . '/../resources/layout/navigation_main.php');
?>

<div class="container py-4">

    <form method="POST" action="<?= request_url('users/create.php') ?>" id="updateUserForm">

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>">
            <span id="usernameError" class="text-danger"><?= Message::getError('User@create', 'username') ?></span>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" id="password" name="password" value="<?= old('password') ?>">
            <span id="passwordError" class="text-danger"><?= Message::getError('User@create', 'password') ?></span>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?= old('email') ?>">
            <span id="emailError" class="text-danger"><?= Message::getError('User@create', 'email') ?></span>

        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= old('full_name') ?>">
            <span id="full_nameError" class="text-danger"><?= Message::getError('User@create', 'full_name') ?></span>

        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role">
                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>User</option>
            </select>
            <span id="roleError" class="text-danger"><?= Message::getError('User@create', 'role') ?></span>

        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="1" <?= old('status') == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= old('status') == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
            <span id="statusError" class="text-danger"><?= Message::getError('User@create', 'status') ?></span>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
        <div class="row mb-3">
            <?= Message::getMessage('User@create') ?>
        </div>
    </form>
</div>

<?php
include_once(__DIR__ . '/../resources/layout/footer_main.php');
?>