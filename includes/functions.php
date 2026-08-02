<?php

declare(strict_types=1);

function cleanInput(string $value): string
{
    return trim($value);
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}