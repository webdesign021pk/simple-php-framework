<?php
// app/requests/users/User.php

if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("403 Forbidden");
}

require_once __DIR__ . '/../../app/config/bootstrap.php';

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function all()
    {
        if (!Permission::userHas('View All Users')) {
            return return_response('Access denied.', [], 403);
        }

        try {
            $stmt = $this->db->query(
                "
                    SELECT u.id, u.full_name, u.email, u.created_at, u.status, u.role, u.username
                    FROM users u
                "
            );
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$data) {
                return return_response("Users not found", [], 404);
            }
            return $data;
        } catch (PDOException $e) {
            return return_response($e->getMessage(), [], 404);
        }
    }

    public function find($id)
    {

        if (!Permission::userHas('View All Users')) {
            return return_response('Access denied.', [], 403);
        }

        try {
            if (!is_numeric($id) || !$id) {
                throw new PDOException("Invalid id");
            }

            $stmt = $this->db->prepare(
                "
                    SELECT u.id, u.username,u.full_name, u.email, u.status, u.role,  u.created_at, u.updated_at
                    FROM users u
                    WHERE u.id = :id
                "
            );
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return return_response("User not found", [], 404);
            }

            return $data;
        } catch (PDOException $e) {
            return return_response($e->getMessage(), [], 404);
        }
    }

    public function create($request)
    {

        if (!Permission::userHas('Add Users')) {
            return return_response('Access denied.', [], 403);
        }

        try {
            $this->db->beginTransaction();

            // Define validation rules
            $rules = [
                'full_name' => ['required|string|min:3|max:50'],
                'username' => ['required|string|min:3|max:50|unique:users,username'],
                'email' => ['required|email|unique:users,email'],
                'password' => ['required|string|min:3|max:50'],
                'role' => ['required|string|min:3|max:50'],
            ];

            // Perform validation
            $validator = new Validator($request);
            if (!$validator->validate($rules)) {
                flash('old', $request); // $request is your validated or original input
                Message::error('User@create', 'Validation failed', $validator->getErrors());
                return return_response("Validation failed", $validator->getErrors(), 422);
            }

            // Get validated data (optionally filtered)
            $request = $validator->getValidatedData();

            // Hash password before storing
            $hash = password_hash($request['password'], PASSWORD_DEFAULT);

            unset($request['password']);
            $request['password_hash'] = $hash;
            $request['created_at'] = date('Y-m-d H:i:s');
            $request['updated_at'] = date('Y-m-d H:i:s');

            // Create a new user
            $stmt = $this->db->prepare("
                INSERT INTO users (full_name, username, email, password_hash, role, created_at, updated_at) 
                VALUES (:full_name, :username, :email, :password_hash, :role, :created_at, :updated_at)
            ");

            $stmt->execute($request);

            $this->db->commit();

            Message::success('User@create', 'User created successfully');
            return return_response("User created successfully.", ['id' => $this->db->lastInsertId()], 201);
        } catch (PDOException $e) {

            $this->db->rollBack();
            flash('old', $request); // $request is your validated or original input
            return return_response($e->getMessage(), [], 500);
        }
    }

    public function update($id, $request)
    {

        if (!Permission::userHas('Modify Users')) {
            return return_response('Access denied.', [], 403);
        }

        try {
            $this->db->beginTransaction();

            // Define validation rules
            $rules = [
                'username' => ['sometimes|string|min:3|max:50|unique:users,username,' . $id . ',id'],
                'full_name' => ['sometimes|string|min:3|max:50'],
                'email' => ['sometimes|email|unique:users,email,' . $id . ',id'],
                'role' => ['sometimes|string|min:3|max:50'],
            ];

            // Perform validation
            $validator = new Validator($request);
            if (!$validator->validate($rules)) {
                flash('old', $request); // $request is your validated or original input
                Message::error('User@update', 'Validation failed', $validator->getErrors());
                return return_response("Validation failed", $validator->getErrors(), 422);
            }

            // Get validated data (optionally filtered)
            $request = $validator->getValidatedData();

            $request['updated_at'] = date('Y-m-d H:i:s');

            $setQuery = '';
            foreach ($request as $key => $value) {
                $setQuery .= $key . " = :$key, ";
            }

            $setQuery = rtrim($setQuery, ', ');

            $request['id'] = $id;

            // Update the user
            $stmt = $this->db->prepare("
                UPDATE users
                SET " . $setQuery . " 
                WHERE id = :id
            ");

            $stmt->execute($request);

            $this->db->commit();
            // Fetch updated user
            $updatedUser = $this->find($id);

            Message::success('User@update', 'User updated successfully');
            return return_response("User updated successfully.", $updatedUser, 200);
        } catch (PDOException $e) {

            $this->db->rollBack();
            return return_response($e->getMessage(), [], 500);
            // return false;
        }
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            $request = ['id' => $id];

            // Define validation rules
            $rules = [
                'id' => ['required|exists:users,id'],
            ];

            // Perform validation
            $validator = new Validator($request);
            if (!$validator->validate($rules)) {
                flash('old', $request); // $request is your validated or original input
                return return_response("Validation failed", $validator->getErrors(), 422);
            }

            // Get validated data (optionally filtered)
            $request = $validator->getValidatedData();
            $stmt = $this->db->prepare(
                "
                    DELETE FROM users
                    WHERE id = :id
                "
            );
            $stmt->execute($request);

            $this->db->commit();
            return return_response("User deleted successfully.", [], 200);
        } catch (PDOException $e) {

            $this->db->rollBack();
            return return_response($e->getMessage(), [], 500);
        }
    }
}
