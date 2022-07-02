<?php


namespace AliSuliman\P2PRpc\Classes\Helpers;


class StatusCode
{
    public const INTERNAL_ERROR = 500;
    public const NOT_FOUND = 404;
    public const UNAUTHENTICATED = 401;
    public const UNAUTHORIZED = 403;
    public const UNPROCESSABLE_ENTITY = 422;
    public const ErrorWithMessage = 100;
}
