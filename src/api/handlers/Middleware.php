<?php

declare(strict_types=1);

namespace Api\Handler;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Middleware
{
    public function check(string $bearer): ?string
    {
        // $aclFile = BASE_PATH . '/security/acl.cache';
        $url = parse_url($_SERVER['REQUEST_URI']);
        // $parts = explode('/', $url['path']);
        // $acl = unserialize(file_get_contents($aclFile));
        try {
            $key = "example_key";
            $decoded = JWT::decode($bearer, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            return $decoded_array['role'];
            // if (!$role || true !== $acl->isAllowed($role, $parts[2], $parts[3])) {

            //     echo '<h2>Access denied :(</h2>';
            //     die;
            // }
        } catch (\Exception $e) {
            echo "Try with accessible token please !";
            die;
        }
    }
}
