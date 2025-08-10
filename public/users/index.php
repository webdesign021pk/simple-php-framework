<?php
include_once(__DIR__ . '/../resources/layout/header_main.php');

$page_title = "User List";

include_once(__DIR__ . '/../resources/layout/navigation_main.php');

require_once(REQUESTS_PATH . '/users/User.php');

$users = (new User)->all();

?>

<div class="container py-4">
    <!-- Date and Time Inputs -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="table-responsive">
                <!-- Data Table -->
                <table id="users" class="pt-4 table table-hover" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th style="min-width: 240px">Full Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($users['status']) && $users['status'] === 'error' ) { ?>
                            <tr>
                                <td colspan='8' class='text-center'>
                                    <p class='error'>Failed to fetch users. Please try again later.</p>
                                </td>
                            </tr>
                            <?php } else {
                            foreach ($users as $user) { ?>
                                <tr>
                                    <td>
                                        <?= value($user, 'id') ?>
                                    </td>
                                    <td>
                                        <?= value($user, 'username') ?>
                                    </td>
                                    <td>
                                        <?= value($user, 'full_name') ?>
                                    </td>
                                    <td>
                                        <?= value($user, 'role') ?>
                                    </td>
                                    <td>
                                        <?= value($user, 'status') ? 'Active' : 'Inactive' ?>
                                    </td>
                                    <td>
                                        <?= value($user, 'created_at') ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary me-2" href="<?= base_url('/users/update.php?id=' . value($user, 'id')) ?>">Edit</a>
                                        <a class="btn btn-sm btn-danger me-2" href="<?= request_url('/users/delete.php?id=' . value($user, 'id')) ?>">Delete</a>
                                    </td>
                                    <td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12">
            <?= Message::getMessage('User@all') ?>
        </div>
    </div>
</div>

<?php

include_once(__DIR__ . '/../resources/layout/footer_main.php');
?>