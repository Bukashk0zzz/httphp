<?php declare(strict_types=1);

namespace HTTPHP\RFC;

class RFC723x
{
    /**
     * @see https://tools.ietf.org/html/rfc7231#section-6.1
     */
    public const STATUS_CONTINUE = 100;
    public const STATUS_SWITCHING_PROTOCOLS = 101;
    public const STATUS_OK = 200;
    public const STATUS_CREATED = 201;
    public const STATUS_ACCEPTED = 202;
    public const STATUS_NON_AUTHORITATIVE_INFORMATION = 203;
    public const STATUS_NO_CONTENT = 204;
    public const STATUS_RESET_CONTENT = 205;
    public const STATUS_PARTIAL_CONTENT = 206;
    public const STATUS_MULTIPLE_CHOICES = 300;
    public const STATUS_MOVED_PERMANENTLY = 301;
    public const STATUS_FOUND = 302;
    public const STATUS_SEE_OTHER = 303;
    public const STATUS_NOT_MODIFIED = 304;
    public const STATUS_USE_PROXY = 305;
    public const STATUS_TEMPORARY_REDIRECT = 307;
    public const STATUS_BAD_REQUEST = 400;
    public const STATUS_UNAUTHORIZED = 401;
    public const STATUS_PAYMENT_REQUIRED = 402;
    public const STATUS_FORBIDDEN = 403;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_METHOD_NOT_ALLOWED = 405;
    public const STATUS_NOT_ACCEPTABLE = 406;
    public const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const STATUS_REQUEST_TIMEOUT = 408;
    public const STATUS_CONFLICT = 409;
    public const STATUS_GONE = 410;
    public const STATUS_LENGTH_REQUIRED = 411;
    public const STATUS_PRECONDITION_FAILED = 412;
    public const STATUS_PAYLOAD_TOO_LARGE = 413;
    public const STATUS_URI_TOO_LONG = 414;
    public const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    public const STATUS_RANGE_NOT_SATISFIABLE = 416;
    public const STATUS_EXPECTATION_FAILED = 417;
    public const STATUS_UPGRADE_REQUIRED = 426;
    public const STATUS_INTERNAL_SERVER_ERROR = 500;
    public const STATUS_NOT_IMPLEMENTED = 501;
    public const STATUS_BAD_GATEWAY = 502;
    public const STATUS_SERVICE_UNAVAILABLE = 503;
    public const STATUS_GATEWAY_TIMEOUT = 504;
    public const STATUS_HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * @see https://tools.ietf.org/html/rfc7238#section-3
     */
    public const STATUS_PERMANENT_REDIRECT = 308;

    public const REASONS = [
        self::STATUS_CONTINUE => 'Continue',
        self::STATUS_SWITCHING_PROTOCOLS => 'Switching protocols',
        self::STATUS_OK => 'Ok',
        self::STATUS_CREATED => 'Created',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_NON_AUTHORITATIVE_INFORMATION => 'Non authoritative information',
        self::STATUS_NO_CONTENT => 'No content',
        self::STATUS_RESET_CONTENT => 'Reset content',
        self::STATUS_PARTIAL_CONTENT => 'Partial content',
        self::STATUS_MULTIPLE_CHOICES => 'Multiple choices',
        self::STATUS_MOVED_PERMANENTLY => 'Moved permanently',
        self::STATUS_FOUND => 'Found',
        self::STATUS_SEE_OTHER => 'See other',
        self::STATUS_NOT_MODIFIED => 'Not modified',
        self::STATUS_USE_PROXY => 'Use proxy',
        self::STATUS_TEMPORARY_REDIRECT => 'Temporary redirect',
        self::STATUS_BAD_REQUEST => 'Bad request',
        self::STATUS_UNAUTHORIZED => 'Unauthorized',
        self::STATUS_PAYMENT_REQUIRED => 'Payment required',
        self::STATUS_FORBIDDEN => 'Forbidden',
        self::STATUS_NOT_FOUND => 'Not found',
        self::STATUS_METHOD_NOT_ALLOWED => 'Method not allowed',
        self::STATUS_NOT_ACCEPTABLE => 'Not acceptable',
        self::STATUS_PROXY_AUTHENTICATION_REQUIRED => 'Proxy authentication required',
        self::STATUS_REQUEST_TIMEOUT => 'Request timeout',
        self::STATUS_CONFLICT => 'Conflict',
        self::STATUS_GONE => 'Gone',
        self::STATUS_LENGTH_REQUIRED => 'Length required',
        self::STATUS_PRECONDITION_FAILED => 'Precondition failed',
        self::STATUS_PAYLOAD_TOO_LARGE => 'Payload too large',
        self::STATUS_URI_TOO_LONG => 'Uri too long',
        self::STATUS_UNSUPPORTED_MEDIA_TYPE => 'Unsupported media type',
        self::STATUS_RANGE_NOT_SATISFIABLE => 'Range not satisfiable',
        self::STATUS_EXPECTATION_FAILED => 'Expectation failed',
        self::STATUS_UPGRADE_REQUIRED => 'Upgrade required',
        self::STATUS_INTERNAL_SERVER_ERROR => 'Internal server error',
        self::STATUS_NOT_IMPLEMENTED => 'Not implemented',
        self::STATUS_BAD_GATEWAY => 'Bad gateway',
        self::STATUS_SERVICE_UNAVAILABLE => 'Service unavailable',
        self::STATUS_GATEWAY_TIMEOUT => 'Gateway timeout',
        self::STATUS_HTTP_VERSION_NOT_SUPPORTED => 'HTTP version not supported',
        self::STATUS_PERMANENT_REDIRECT => 'Permanent redirect',
    ];
}
