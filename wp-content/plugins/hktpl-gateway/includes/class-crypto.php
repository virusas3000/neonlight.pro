<?php
/**
 * HKTPL Crypto helper — Verified against HKTPL v1.0.62
 *
 * - RSA encryption: RSA/ECB/OAEPWithSHA-1AndMGF1Padding with 4096-bit key
 * - HMAC-SHA512 signature generation/verification with ksort + & join
 * - Base64URL encode/decode
 *
 * @package HKTPL_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class HKTPL_Crypto {

	/**
	 * RSA encrypt payment payload.
	 *
	 * Algorithm: RSA/ECB/OAEPWithSHA-1AndMGF1Padding
	 * Public Key: Base64-encoded X.509, 4096-bit
	 *
	 * @param string $data      JSON-encoded payload string.
	 * @param string $publicKey Base64-encoded public key or PEM.
	 * @return string|false     Base64-encoded ciphertext or false on failure.
	 */
	public static function rsa_encrypt( $data, $publicKey ) {
		if ( empty( $publicKey ) ) {
			return false;
		}

		$publicKey = self::normalize_key( $publicKey, 'PUBLIC' );
		$key       = openssl_pkey_get_public( $publicKey );

		if ( ! $key ) {
			return false;
		}

		$encrypted = '';
		$success   = openssl_public_encrypt(
			$data,
			$encrypted,
			$key,
			OPENSSL_PKCS1_OAEP_PADDING // Maps to RSA/ECB/OAEPWithSHA-1AndMGF1Padding in PHP OpenSSL
		);

		if ( ! $success ) {
			return false;
		}

		return base64_encode( $encrypted );
	}

	/**
	 * Generate HMAC-SHA512 signature per HKTPL spec.
	 *
	 * Steps:
	 * 1. Sort parameters alphabetically (ksort)
	 * 2. Exclude null/empty values and 'sign' parameter
	 * 3. Join as key=value&key2=value2
	 * 4. HMAC-SHA512 with API Key (raw binary)
	 * 5. Base64-encode the result (NOT base64url)
	 *
	 * @param array  $params Parameters to sign.
	 * @param string $secret API Key for signing.
	 * @return string Base64-encoded signature.
	 */
	public static function generate_signature( $params, $secret ) {
		ksort( $params );
		$parts = array();
		foreach ( $params as $key => $value ) {
			if ( $key === 'sign' || $value === null || $value === '' ) {
				continue;
			}
			if ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}
			$parts[] = $key . '=' . (string) $value;
		}
		$string_to_sign = implode( '&', $parts );
		$signature      = hash_hmac( 'sha512', $string_to_sign, $secret, true );
		return base64_encode( $signature );
	}

	/**
	 * Verify HMAC-SHA512 signature.
	 *
	 * @param array  $params    Original params.
	 * @param string $signature Provided signature (Base64).
	 * @param string $secret    API key / secret.
	 * @return bool
	 */
	public static function verify_signature( $params, $signature, $secret ) {
		$expected = self::generate_signature( $params, $secret );
		return hash_equals( $expected, $signature );
	}

	/**
	 * Base64URL encode.
	 *
	 * @param string $data Raw binary data.
	 * @return string
	 */
	public static function base64url_encode( $data ) {
		return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
	}

	/**
	 * Base64URL decode.
	 *
	 * @param string $data Base64URL string.
	 * @return string|false
	 */
	public static function base64url_decode( $data ) {
		$pad  = 4 - ( strlen( $data ) % 4 );
		$pad  = ( $pad !== 4 ) ? $pad : 0;
		$data = str_pad( $data, strlen( $data ) + $pad, '=', STR_PAD_RIGHT );
		$data = strtr( $data, '-_', '+/' );
		return base64_decode( $data );
	}

	/**
	 * Normalize PEM key.
	 *
	 * @param string $key  Raw key string.
	 * @param string $type 'PUBLIC' or 'PRIVATE'.
	 * @return string PEM-formatted key.
	 */
	private static function normalize_key( $key, $type ) {
		$key = trim( $key );
		if ( strpos( $key, "BEGIN {$type} KEY" ) !== false ) {
			return $key;
		}
		return "-----BEGIN {$type} KEY-----\n" . chunk_split( $key, 64, "\n" ) . "-----END {$type} KEY-----";
	}
}
