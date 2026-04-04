<?php
// ================================================================
//  lib/JWT.php  —  Lightweight JWT (HS256) without dependencies
// ================================================================

class JWT {
    private static string $secret = 'kumaris_store_jwt_secret_2024';
    // In production: define('JWT_SECRET', getenv('JWT_SECRET'))

    public static function encode(array $payload, int $expireSeconds = 604800): string {
        $header  = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['iat'] = time();
        $payload['exp'] = time() + $expireSeconds;
        $body    = self::base64url(json_encode($payload));
        $sig     = self::base64url(hash_hmac('sha256', "$header.$body", self::$secret, true));
        return "$header.$body.$sig";
    }

    public static function decode(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$header, $body, $sig] = $parts;
        $expected = self::base64url(hash_hmac('sha256', "$header.$body", self::$secret, true));

        // Timing-safe comparison
        if (!hash_equals($expected, $sig)) return null;

        $payload = json_decode(self::base64urlDecode($body), true);
        if (!$payload || $payload['exp'] < time()) return null;   // expired

        return $payload;
    }

    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
    }
}
