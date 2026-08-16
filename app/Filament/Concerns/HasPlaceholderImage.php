<?php

namespace App\Filament\Concerns;

trait HasPlaceholderImage
{
    private static function placeholderImage(): string
    {
        return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHJlY3Qgd2lkdGg9IjQ4IiBoZWlnaHQ9IjQ4IiByeD0iMTAiIGZpbGw9IiNlZWYzZTkiLz48Y2lyY2xlIGN4PSIxOCIgY3k9IjE4IiByPSI0IiBmaWxsPSIjYjljOWFlIi8+PHBhdGggZD0iTTEwIDM0bDktMTEgNiA2IDgtMTAgNSAxNXoiIGZpbGw9IiNiOWM5YWUiLz48L3N2Zz4K';
    }
}
