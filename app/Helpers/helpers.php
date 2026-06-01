<?php

if (! function_exists('searchLike')) {
    function searchLike(mixed $value)
    {
        return '%' . preg_replace('/\s+/', '%', trim((string)$value)) . '%';
    }
}

if (! function_exists('isAjax')) {
    function isAjax(): bool
    {
        return request()->ajax() || request()->wantsJson();
    }
}
