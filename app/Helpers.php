<?php

/**
 * Generate time options in 30-minute intervals for use in Blade views.
 * Returns array of ['value' => 'HH:MM', 'label' => 'h:i A']
 *
 * Usage in blade: @foreach(generateTimeOptions() as $time)
 *
 * Register in composer.json autoload > files:
 *   "files": ["app/helpers.php"]
 */
if (! function_exists('generateTimeOptions')) {
    function generateTimeOptions(string $start = '07:00', string $end = '22:00', int $intervalMinutes = 30): array
    {
        $options   = [];
        $current   = \Carbon\Carbon::createFromFormat('H:i', $start);
        $endTime   = \Carbon\Carbon::createFromFormat('H:i', $end);

        while ($current->lte($endTime)) {
            $options[] = [
                'value' => $current->format('H:i'),
                'label' => $current->format('g:i A'),
            ];
            $current->addMinutes($intervalMinutes);
        }

        return $options;
    }
}
