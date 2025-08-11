<?php

namespace App\Shared\UI\Response;

class HttpResponseCode
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_CONFLICT = 409;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_FORBIDDEN = 403;
    const HTTP_GONE = 410;
    const HTTP_UNAUTHORIZED = 401;
}