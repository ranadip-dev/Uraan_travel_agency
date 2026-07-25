<?php

declare(strict_types=1);

function cleanInput(string $value): string
{
    return trim($value);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}