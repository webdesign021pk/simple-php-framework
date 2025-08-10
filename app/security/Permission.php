<?php

class Permission
{
    protected static $cache = [];

    public static function all(): array
    {
        $stmt = DB::connection()->query("SELECT * FROM permissions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getForUser($userId): array
    {
        if (isset(self::$cache[$userId])) {
            return self::$cache[$userId];
        }

        $stmt = DB::connection()->prepare("
            SELECT p.name 
            FROM permissions p
            JOIN user_permissions up ON up.permission_id = p.id
            WHERE up.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);

        $perms = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name');
        self::$cache[$userId] = $perms;

        return $perms;
    }

    public static function userHas($permission, $userId = null): bool
    {
        if (!$userId && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        if (!$userId) return false;

        return in_array($permission, self::getForUser($userId));
    }

    public static function require($permission)
    {
        if (!self::userHas($permission)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Forbidden: Permission denied']);
            exit;
        }
    }
}
