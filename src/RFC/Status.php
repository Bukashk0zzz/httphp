<?php declare(strict_types=1);

namespace HTTPHP\RFC;

class Status
{
    /** @see https://tools.ietf.org/html/rfc7231#section-6.1 */
    public const CONTINUE = 100;
    public const SWITCHING_PROTOCOLS = 101;
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NON_AUTHORITATIVE_INFORMATION = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    public const TEMPORARY_REDIRECT = 307;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTHENTICATION_REQUIRED = 407;
    public const REQUEST_TIMEOUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const PAYLOAD_TOO_LARGE = 413;
    public const URI_TOO_LONG = 414;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const RANGE_NOT_SATISFIABLE = 416;
    public const EXPECTATION_FAILED = 417;
    public const UPGRADE_REQUIRED = 426;

    public const INTERNAL_SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;

    /** @see https://tools.ietf.org/html/rfc6585 */
    public const PRECONDITION_REQUIRED = 428;
    public const TOO_MANY_REQUESTS = 429;
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;

    /** @see https://tools.ietf.org/html/rfc7238#section-3 */
    public const PERMANENT_REDIRECT = 308;

    /** @see https://tools.ietf.org/html/rfc7725#section-3 */
    public const UNAVAILABLE_FOR_LEGAL_REASONS   = 451;

    public const REASONS = [
        self::CONTINUE => 'Continue',
        self::SWITCHING_PROTOCOLS => 'Switching protocols',
        self::OK => 'Ok',
        self::CREATED => 'Created',
        self::ACCEPTED => 'Accepted',
        self::NON_AUTHORITATIVE_INFORMATION => 'Non authoritative information',
        self::NO_CONTENT => 'No content',
        self::RESET_CONTENT => 'Reset content',
        self::PARTIAL_CONTENT => 'Partial content',
        self::MULTIPLE_CHOICES => 'Multiple choices',
        self::MOVED_PERMANENTLY => 'Moved permanently',
        self::FOUND => 'Found',
        self::SEE_OTHER => 'See other',
        self::NOT_MODIFIED => 'Not modified',
        self::USE_PROXY => 'Use proxy',
        self::TEMPORARY_REDIRECT => 'Temporary redirect',
        self::BAD_REQUEST => 'Bad request',
        self::UNAUTHORIZED => 'Unauthorized',
        self::PAYMENT_REQUIRED => 'Payment required',
        self::FORBIDDEN => 'Forbidden',
        self::NOT_FOUND => 'Not found',
        self::METHOD_NOT_ALLOWED => 'Method not allowed',
        self::NOT_ACCEPTABLE => 'Not acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED => 'Proxy authentication required',
        self::REQUEST_TIMEOUT => 'Request timeout',
        self::CONFLICT => 'Conflict',
        self::GONE => 'Gone',
        self::LENGTH_REQUIRED => 'Length required',
        self::PRECONDITION_FAILED => 'Precondition failed',
        self::PAYLOAD_TOO_LARGE => 'Payload too large',
        self::URI_TOO_LONG => 'Uri too long',
        self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported media type',
        self::RANGE_NOT_SATISFIABLE => 'Range not satisfiable',
        self::EXPECTATION_FAILED => 'Expectation failed',
        self::UPGRADE_REQUIRED => 'Upgrade required',
        self::INTERNAL_SERVER_ERROR => 'Internal server error',
        self::NOT_IMPLEMENTED => 'Not implemented',
        self::BAD_GATEWAY => 'Bad gateway',
        self::SERVICE_UNAVAILABLE => 'Service unavailable',
        self::GATEWAY_TIMEOUT => 'Gateway timeout',
        self::HTTP_VERSION_NOT_SUPPORTED => 'HTTP version not supported',
        self::PERMANENT_REDIRECT => 'Permanent redirect',
        self::PRECONDITION_REQUIRED => 'Precondition required',
        self::TOO_MANY_REQUESTS => 'Too many requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request header fields too large',
        self::NETWORK_AUTHENTICATION_REQUIRED => 'Network authentication required',
        self::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable for legal reasons',
    ];
}
