<?php

if (! function_exists('searchLike')) {
    function searchLike(mixed $value)
    {
        return '%' . preg_replace('/\s+/', '%', trim((string)$value)) . '%';
    }
}
