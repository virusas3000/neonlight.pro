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
	 * Build candidate "string to sign" values for a set of params.
	 *
	 * The HKTPL v1.0.62 spec is internally inconsistent: §2.2/§2.4 describe
	 * `ksort` + `key1=value1&key2=value2`, but the §7 callback example uses
	 * `implode('&', $params)` (values only, fixed order). Outgoing requests
	 * are accepted with the key=value format, yet callbacks fail to verify
	 * with it — so HKTPL appears to sign callbacks differently. To be robust
	 * (and to discover which format HKTPL actually uses), we try several
	 * candidate strings. Each is still a real HMAC-SHA512 with the API key,
	 * so accepting any match is secure.
	 *
	 * @param array $params Params (sign/empty excluded inside).
	 * @return array mode => string_to_sign
	 */
	public static function candidate_strings( array $params ) {
		$params = array_filter( $params, function ( $v ) { return $v !== null && $v !== ''; } );
		unset( $params['sign'] );

		$sorted = $params;
		ksort( $sorted );

		// Fixed parameter order per spec §7 example.
		$fixed = array();
		foreach ( array( 'merTradeNo', 'tradeNo', 'tradeStatus', 'msg', 'resultCode' ) as $k ) {
			if ( isset( $params[ $k ] ) ) {
				$fixed[ $k ] = $params[ $k ];
			}
		}

		return array(
			'ksort_keyvalue'         => self::pairs( $sorted, false ),
			'ksort_keyvalue_trail'   => self::pairs( $sorted, true ),
			'ksort_values'           => self::values( $sorted, false ),
			'ksort_values_trail'     => self::values( $sorted, true ),
			'fixed_values'           => self::values( $fixed, false ),
			'fixed_values_trail'     => self::values( $fixed, true ),
			'fixed_keyvalue'         => self::pairs( $fixed, false ),
			'ksort_keyvalue_encoded' => self::pairs( $sorted, false, true ),
			'sent_keyvalue'          => self::pairs( $params, false ),
		);
	}

	/**
	 * Verify a signature against all candidate string formats.
	 *
	 * @param array  $params    Original params.
	 * @param string $signature Provided signature (Base64).
	 * @param string $secret    API key.
	 * @return string Matched mode name, or '' if none matched.
	 */
	public static function verify_signature_mode( $params, $signature, $secret ) {
		$signature = (string) $signature;
		foreach ( self::candidate_strings( (array) $params ) as $mode => $str ) {
			$expected = base64_encode( hash_hmac( 'sha512', $str, $secret, true ) );
			if ( hash_equals( $expected, $signature ) ) {
				return $mode;
			}
		}
		return '';
	}

	/**
	 * Verify HMAC-SHA512 signature (accept any valid string format).
	 *
	 * @param array  $params    Original params.
	 * @param string $signature Provided signature (Base64).
	 * @param string $secret    API key / secret.
	 * @return bool
	 */
	public static function verify_signature( $params, $signature, $secret ) {
		return '' !== self::verify_signature_mode( $params, $signature, $secret );
	}

	/**
	 * Join params as key=value pairs (order preserved).
	 *
	 * @param array $params   Params.
	 * @param bool  $trailing Append a trailing '&'.
	 * @param bool  $encode   rawurlencode values.
	 * @return string
	 */
	private static function pairs( array $params, $trailing, $encode = false ) {
		$parts = array();
		foreach ( $params as $k => $v ) {
			if ( is_array( $v ) ) {
				$v = wp_json_encode( $v );
			}
			$v = (string) $v;
			if ( $encode ) {
				$v = rawurlencode( $v );
			}
			$parts[] = $k . '=' . $v;
		}
		$s = implode( '&', $parts );
		return $trailing ? $s . '&' : $s;
	}

	/**
	 * Join param values only, keys discarded (order preserved).
	 *
	 * @param array $params   Params.
	 * @param bool  $trailing Append a trailing '&'.
	 * @return string
	 */
	private static function values( array $params, $trailing ) {
		$vals = array();
		foreach ( $params as $v ) {
			if ( is_array( $v ) ) {
				$v = wp_json_encode( $v );
			}
			$vals[] = (string) $v;
		}
		$s = implode( '&', $vals );
		return $trailing ? $s . '&' : $s;
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
