<?php

namespace App\Services;

class PantoneService
{
    /**
     * Comprehensive Master Pantone Color Library (PMS C/U, TCX, TPG, Standard).
     *
     * @var array<string, array{name: string, hex: string, code: string}>
     */
    protected static array $pantoneLibrary = [
        // ─── PMS YELLOWS & ORANGES ──────────────────────────────────────────
        '100 C' => ['name' => 'Light Yellow', 'hex' => '#f4ed7c', 'code' => 'PANTONE 100 C'],
        '101 C' => ['name' => 'Lemon Yellow', 'hex' => '#f6eb61', 'code' => 'PANTONE 101 C'],
        '102 C' => ['name' => 'Process Yellow', 'hex' => '#fce300', 'code' => 'PANTONE 102 C'],
        '108 C' => ['name' => 'Bright Yellow', 'hex' => '#ffdf00', 'code' => 'PANTONE 108 C'],
        '109 C' => ['name' => 'Golden Sun Yellow', 'hex' => '#ffd100', 'code' => 'PANTONE 109 C'],
        '116 C' => ['name' => 'Sunshine Yellow', 'hex' => '#ffcd00', 'code' => 'PANTONE 116 C'],
        '123 C' => ['name' => 'Marigold Yellow', 'hex' => '#ffc72c', 'code' => 'PANTONE 123 C'],
        '130 C' => ['name' => 'Amber Gold', 'hex' => '#f2a900', 'code' => 'PANTONE 130 C'],
        '137 C' => ['name' => 'Deep Yellow', 'hex' => '#ffa300', 'code' => 'PANTONE 137 C'],
        '144 C' => ['name' => 'Bright Amber', 'hex' => '#f28500', 'code' => 'PANTONE 144 C'],
        '151 C' => ['name' => 'Vivid Orange', 'hex' => '#ff8200', 'code' => 'PANTONE 151 C'],
        '021 C' => ['name' => 'Pantone Orange', 'hex' => '#fe5000', 'code' => 'PANTONE 021 C'],
        '165 C' => ['name' => 'Pumpkin Orange', 'hex' => '#ff671f', 'code' => 'PANTONE 165 C'],
        '172 C' => ['name' => 'Vermilion Orange', 'hex' => '#ff4438', 'code' => 'PANTONE 172 C'],
        '7406 C' => ['name' => 'Warm Yellow', 'hex' => '#f3a812', 'code' => 'PANTONE 7406 C'],
        '7409 C' => ['name' => 'Deep Golden Yellow', 'hex' => '#e09b13', 'code' => 'PANTONE 7409 C'],

        // ─── PMS REDS, PINKS & MAGENTAS ─────────────────────────────────────
        '1788 C' => ['name' => 'Bright Red', 'hex' => '#f93822', 'code' => 'PANTONE 1788 C'],
        '185 C'  => ['name' => 'Crimson Red', 'hex' => '#e4002b', 'code' => 'PANTONE 185 C'],
        '186 C'  => ['name' => 'Vivid Crimson Red', 'hex' => '#c8102e', 'code' => 'PANTONE 186 C'],
        '187 C'  => ['name' => 'Cardinal Red', 'hex' => '#a6192e', 'code' => 'PANTONE 187 C'],
        '200 C'  => ['name' => 'Ruby Red', 'hex' => '#ba0c2f', 'code' => 'PANTONE 200 C'],
        '201 C'  => ['name' => 'Maroon Red', 'hex' => '#9d2235', 'code' => 'PANTONE 201 C'],
        '202 C'  => ['name' => 'Burgundy Red', 'hex' => '#862633', 'code' => 'PANTONE 202 C'],
        '212 C'  => ['name' => 'Hot Pink', 'hex' => '#f04e98', 'code' => 'PANTONE 212 C'],
        '219 C'  => ['name' => 'Magenta Pink', 'hex' => '#da1884', 'code' => 'PANTONE 219 C'],
        '226 C'  => ['name' => 'Deep Fuchsia', 'hex' => '#d7006d', 'code' => 'PANTONE 226 C'],
        '233 C'  => ['name' => 'Wild Magenta', 'hex' => '#b1005d', 'code' => 'PANTONE 233 C'],
        'Red 032 C' => ['name' => 'Pantone Red 032', 'hex' => '#ef3340', 'code' => 'PANTONE Red 032 C'],
        'Rhodamine Red C' => ['name' => 'Rhodamine Red', 'hex' => '#e10098', 'code' => 'PANTONE Rhodamine Red C'],
        'Rubine Red C' => ['name' => 'Rubine Red', 'hex' => '#ce0058', 'code' => 'PANTONE Rubine Red C'],

        // ─── PMS VIOLETS & PURPLES ──────────────────────────────────────────
        '253 C'  => ['name' => 'Electric Purple', 'hex' => '#af272f', 'code' => 'PANTONE 253 C'],
        '259 C'  => ['name' => 'Royal Purple', 'hex' => '#6a1b9a', 'code' => 'PANTONE 259 C'],
        '266 C'  => ['name' => 'Vivid Violet', 'hex' => '#753bbd', 'code' => 'PANTONE 266 C'],
        '268 C'  => ['name' => 'Deep Purple', 'hex' => '#582c83', 'code' => 'PANTONE 268 C'],
        '273 C'  => ['name' => 'Midnight Purple', 'hex' => '#291054', 'code' => 'PANTONE 273 C'],
        '2755 C' => ['name' => 'Imperial Purple', 'hex' => '#200843', 'code' => 'PANTONE 2755 C'],
        'Purple C' => ['name' => 'Pantone Purple', 'hex' => '#bb29bb', 'code' => 'PANTONE Purple C'],
        'Violet C' => ['name' => 'Pantone Violet', 'hex' => '#400099', 'code' => 'PANTONE Violet C'],

        // ─── PMS BLUES & CYANS ──────────────────────────────────────────────
        '279 C'  => ['name' => 'Sky Blue', 'hex' => '#41748d', 'code' => 'PANTONE 279 C'],
        '285 C'  => ['name' => 'Ocean Blue', 'hex' => '#0072ce', 'code' => 'PANTONE 285 C'],
        '286 C'  => ['name' => 'Royal Navy Blue', 'hex' => '#0033a0', 'code' => 'PANTONE 286 C'],
        '287 C'  => ['name' => 'Deep Royal Blue', 'hex' => '#00205b', 'code' => 'PANTONE 287 C'],
        '288 C'  => ['name' => 'Midnight Navy', 'hex' => '#002d62', 'code' => 'PANTONE 288 C'],
        '294 C'  => ['name' => 'Deep Navy', 'hex' => '#002f6c', 'code' => 'PANTONE 294 C'],
        '295 C'  => ['name' => 'Dark Navy', 'hex' => '#002042', 'code' => 'PANTONE 295 C'],
        '299 C'  => ['name' => 'Bright Cyan Blue', 'hex' => '#00a3e0', 'code' => 'PANTONE 299 C'],
        '300 C'  => ['name' => 'Electric Blue', 'hex' => '#005bbb', 'code' => 'PANTONE 300 C'],
        '306 C'  => ['name' => 'Process Cyan', 'hex' => '#00b5e2', 'code' => 'PANTONE 306 C'],
        '312 C'  => ['name' => 'Turquoise Blue', 'hex' => '#00a9ce', 'code' => 'PANTONE 312 C'],
        '319 C'  => ['name' => 'Aqua Blue', 'hex' => '#25c9c9', 'code' => 'PANTONE 319 C'],
        'Process Cyan C' => ['name' => 'Process Cyan', 'hex' => '#00a3e0', 'code' => 'PANTONE Process Cyan C'],
        'Reflex Blue C' => ['name' => 'Reflex Blue', 'hex' => '#0a1172', 'code' => 'PANTONE Reflex Blue C'],

        // ─── PMS GREENS ─────────────────────────────────────────────────────
        '327 C'  => ['name' => 'Deep Teal', 'hex' => '#008375', 'code' => 'PANTONE 327 C'],
        '340 C'  => ['name' => 'Mint Green', 'hex' => '#00965e', 'code' => 'PANTONE 340 C'],
        '347 C'  => ['name' => 'Emerald Green', 'hex' => '#009a44', 'code' => 'PANTONE 347 C'],
        '348 C'  => ['name' => 'Kelly Green', 'hex' => '#00843d', 'code' => 'PANTONE 348 C'],
        '355 C'  => ['name' => 'Lime Green', 'hex' => '#009639', 'code' => 'PANTONE 355 C'],
        '356 C'  => ['name' => 'Forest Green', 'hex' => '#007a33', 'code' => 'PANTONE 356 C'],
        '357 C'  => ['name' => 'Deep Forest Green', 'hex' => '#214c2e', 'code' => 'PANTONE 357 C'],
        '361 C'  => ['name' => 'Bright Green', 'hex' => '#1eb980', 'code' => 'PANTONE 361 C'],
        '368 C'  => ['name' => 'Apple Green', 'hex' => '#78be20', 'code' => 'PANTONE 368 C'],
        '375 C'  => ['name' => 'Spring Green', 'hex' => '#97d700', 'code' => 'PANTONE 375 C'],
        '382 C'  => ['name' => 'Chartreuse Lime', 'hex' => '#c4d600', 'code' => 'PANTONE 382 C'],
        'Green C' => ['name' => 'Pantone Green', 'hex' => '#00ab84', 'code' => 'PANTONE Green C'],

        // ─── PMS BROWNS & EARTH TONES ───────────────────────────────────────
        '469 C'  => ['name' => 'Chestnut Brown', 'hex' => '#603813', 'code' => 'PANTONE 469 C'],
        '471 C'  => ['name' => 'Rust Orange', 'hex' => '#c65a11', 'code' => 'PANTONE 471 C'],
        '476 C'  => ['name' => 'Chocolate Brown', 'hex' => '#4a2e19', 'code' => 'PANTONE 476 C'],
        '729 C'  => ['name' => 'Tan Camel', 'hex' => '#b88b5c', 'code' => 'PANTONE 729 C'],
        '731 C'  => ['name' => 'Terracotta Brown', 'hex' => '#7a431d', 'code' => 'PANTONE 731 C'],
        '7502 C' => ['name' => 'Warm Beige', 'hex' => '#d7c4a7', 'code' => 'PANTONE 7502 C'],
        '7527 C' => ['name' => 'Cream Sand', 'hex' => '#d6d2c4', 'code' => 'PANTONE 7527 C'],

        // ─── PMS GREYS, BLACKS & NEUTRALS ───────────────────────────────────
        'Cool Gray 1 C'  => ['name' => 'Cool Gray 1', 'hex' => '#d9d9d6', 'code' => 'PANTONE Cool Gray 1 C'],
        'Cool Gray 3 C'  => ['name' => 'Cool Gray 3', 'hex' => '#c8c9c7', 'code' => 'PANTONE Cool Gray 3 C'],
        'Cool Gray 5 C'  => ['name' => 'Cool Gray 5', 'hex' => '#b1b3b3', 'code' => 'PANTONE Cool Gray 5 C'],
        'Cool Gray 7 C'  => ['name' => 'Cool Gray 7', 'hex' => '#97999b', 'code' => 'PANTONE Cool Gray 7 C'],
        'Cool Gray 9 C'  => ['name' => 'Cool Gray 9', 'hex' => '#75787b', 'code' => 'PANTONE Cool Gray 9 C'],
        'Cool Gray 11 C' => ['name' => 'Cool Gray 11', 'hex' => '#53565a', 'code' => 'PANTONE Cool Gray 11 C'],
        'Warm Gray 1 C'  => ['name' => 'Warm Gray 1', 'hex' => '#d7d2cb', 'code' => 'PANTONE Warm Gray 1 C'],
        'Warm Gray 4 C'  => ['name' => 'Warm Gray 4', 'hex' => '#b6b1a9', 'code' => 'PANTONE Warm Gray 4 C'],
        'Warm Gray 8 C'  => ['name' => 'Warm Gray 8', 'hex' => '#8c827a', 'code' => 'PANTONE Warm Gray 8 C'],
        'Warm Gray 11 C' => ['name' => 'Warm Gray 11', 'hex' => '#5b544d', 'code' => 'PANTONE Warm Gray 11 C'],
        'Black C'        => ['name' => 'Process Black', 'hex' => '#2d2926', 'code' => 'PANTONE Black C'],
        'Black 6 C'      => ['name' => 'Rich Black', 'hex' => '#101820', 'code' => 'PANTONE Black 6 C'],
        'Black 7 C'      => ['name' => 'Charcoal Black', 'hex' => '#3d3935', 'code' => 'PANTONE Black 7 C'],

        // ─── NEONS & SPECIALTIES ─────────────────────────────────────────────
        '801 C' => ['name' => 'Neon Cyan Blue', 'hex' => '#00a3e0', 'code' => 'PANTONE 801 C'],
        '802 C' => ['name' => 'Neon Green', 'hex' => '#44d62c', 'code' => 'PANTONE 802 C'],
        '803 C' => ['name' => 'Neon Yellow', 'hex' => '#ffe800', 'code' => 'PANTONE 803 C'],
        '804 C' => ['name' => 'Neon Orange', 'hex' => '#ff6900', 'code' => 'PANTONE 804 C'],
        '805 C' => ['name' => 'Neon Pink', 'hex' => '#ff485d', 'code' => 'PANTONE 805 C'],
        '806 C' => ['name' => 'Neon Magenta', 'hex' => '#ff1493', 'code' => 'PANTONE 806 C'],

        // ─── FASHION, HOME + INTERIORS (TCX & TPG SERIES) ────────────────────
        '19-4052 TCX' => ['name' => 'Classic Blue', 'hex' => '#0f4c81', 'code' => 'PANTONE 19-4052 TCX'],
        '19-4005 TCX' => ['name' => 'Stretch Limo', 'hex' => '#2b2b2c', 'code' => 'PANTONE 19-4005 TCX'],
        '19-4027 TCX' => ['name' => 'Eclipse Navy', 'hex' => '#192535', 'code' => 'PANTONE 19-4027 TCX'],
        '18-1663 TCX' => ['name' => 'Chinese Red', 'hex' => '#cd212a', 'code' => 'PANTONE 18-1663 TCX'],
        '19-3955 TCX' => ['name' => 'Royal Blue', 'hex' => '#004b87', 'code' => 'PANTONE 19-3955 TCX'],
        '13-0647 TCX' => ['name' => 'Illuminating Yellow', 'hex' => '#f5df4d', 'code' => 'PANTONE 13-0647 TCX'],
        '19-6024 TCX' => ['name' => 'Golf Green', 'hex' => '#1a472a', 'code' => 'PANTONE 19-6024 TCX'],
        '19-1012 TCX' => ['name' => 'French Roast Brown', 'hex' => '#48302e', 'code' => 'PANTONE 19-1012 TCX'],
        '17-5104 TCX' => ['name' => 'Ultimate Gray', 'hex' => '#939597', 'code' => 'PANTONE 17-5104 TCX'],
        '15-4020 TCX' => ['name' => 'Cerulean Blue', 'hex' => '#9bb7d4', 'code' => 'PANTONE 15-4020 TCX'],
        '18-3838 TCX' => ['name' => 'Ultra Violet', 'hex' => '#5f4b8b', 'code' => 'PANTONE 18-3838 TCX'],
        '16-1546 TCX' => ['name' => 'Living Coral', 'hex' => '#ff6f61', 'code' => 'PANTONE 16-1546 TCX'],
        '15-0343 TCX' => ['name' => 'Greenery', 'hex' => '#88b04b', 'code' => 'PANTONE 15-0343 TCX'],
        '18-1438 TCX' => ['name' => 'Marsala Wine', 'hex' => '#955251', 'code' => 'PANTONE 18-1438 TCX'],
        '18-3224 TCX' => ['name' => 'Radiant Orchid', 'hex' => '#b565a7', 'code' => 'PANTONE 18-3224 TCX'],
        '17-5641 TCX' => ['name' => 'Emerald Green', 'hex' => '#009473', 'code' => 'PANTONE 17-5641 TCX'],
        '17-1463 TCX' => ['name' => 'Tangerine Tango', 'hex' => '#dd4124', 'code' => 'PANTONE 17-1463 TCX'],
        '18-2120 TCX' => ['name' => 'Honeysuckle Pink', 'hex' => '#d65076', 'code' => 'PANTONE 18-2120 TCX'],
        '15-5519 TCX' => ['name' => 'Turquoise', 'hex' => '#45b8ac', 'code' => 'PANTONE 15-5519 TCX'],
        '14-0848 TCX' => ['name' => 'Mimosa Yellow', 'hex' => '#efc050', 'code' => 'PANTONE 14-0848 TCX'],
        '18-3943 TCX' => ['name' => 'Blue Iris', 'hex' => '#5b5ea6', 'code' => 'PANTONE 18-3943 TCX'],
        '19-1557 TCX' => ['name' => 'Chili Pepper Red', 'hex' => '#9b2335', 'code' => 'PANTONE 19-1557 TCX'],
        '13-1106 TCX' => ['name' => 'Sand Dollar', 'hex' => '#decdbe', 'code' => 'PANTONE 13-1106 TCX'],
        '17-1456 TCX' => ['name' => 'Tigerlily Orange', 'hex' => '#e15d44', 'code' => 'PANTONE 17-1456 TCX'],
        '19-4053 TCX' => ['name' => 'Aqua Sky', 'hex' => '#7fcdcd', 'code' => 'PANTONE 19-4053 TCX'],
        '17-2031 TCX' => ['name' => 'Fuchsia Rose', 'hex' => '#c74375', 'code' => 'PANTONE 17-2031 TCX'],
        '19-3911 TCX' => ['name' => 'Black Beauty', 'hex' => '#1e1e24', 'code' => 'PANTONE 19-3911 TCX'],
        '11-0601 TCX' => ['name' => 'Bright White', 'hex' => '#f4f5f0', 'code' => 'PANTONE 11-0601 TCX'],
        '19-0303 TCX' => ['name' => 'Jet Black', 'hex' => '#131313', 'code' => 'PANTONE 19-0303 TCX'],
        '19-1664 TCX' => ['name' => 'True Red', 'hex' => '#bf1932', 'code' => 'PANTONE 19-1664 TCX'],
        '18-1662 TCX' => ['name' => 'Flame Scarlet', 'hex' => '#cd212a', 'code' => 'PANTONE 18-1662 TCX'],
        '17-1563 TCX' => ['name' => 'Cherry Tomato', 'hex' => '#eb3c27', 'code' => 'PANTONE 17-1563 TCX'],
        '19-4015 TCX' => ['name' => 'Blue Ashes', 'hex' => '#4c6b88', 'code' => 'PANTONE 19-4015 TCX'],
        '19-3921 TCX' => ['name' => 'Black Iris Navy', 'hex' => '#101820', 'code' => 'PANTONE 19-3921 TCX'],
        '19-4150 TCX' => ['name' => 'Princess Blue', 'hex' => '#00539c', 'code' => 'PANTONE 19-4150 TCX'],
        '18-4051 TCX' => ['name' => 'Strong Blue', 'hex' => '#0066b2', 'code' => 'PANTONE 18-4051 TCX'],
        '16-4127 TCX' => ['name' => 'Heritage Blue', 'hex' => '#4a777a', 'code' => 'PANTONE 16-4127 TCX'],
        '14-4115 TCX' => ['name' => 'Sky Light Blue', 'hex' => '#c5d0d6', 'code' => 'PANTONE 14-4115 TCX'],
        '18-0107 TCX' => ['name' => 'Kale Green', 'hex' => '#5b7065', 'code' => 'PANTONE 18-0107 TCX'],
        '19-5513 TCX' => ['name' => 'Dark Forest Green', 'hex' => '#1c352d', 'code' => 'PANTONE 19-5513 TCX'],
        '18-0527 TCX' => ['name' => 'Olive Drab', 'hex' => '#555d50', 'code' => 'PANTONE 18-0527 TCX'],
        '16-0435 TCX' => ['name' => 'Dark Citron', 'hex' => '#8a9a86', 'code' => 'PANTONE 16-0435 TCX'],
        '12-0752 TCX' => ['name' => 'Buttercup Yellow', 'hex' => '#fae03c', 'code' => 'PANTONE 12-0752 TCX'],
        '16-1364 TCX' => ['name' => 'Autumn Glory Orange', 'hex' => '#ff6f3c', 'code' => 'PANTONE 16-1364 TCX'],
        '18-1312 TCX' => ['name' => 'Deep Taupe', 'hex' => '#7b5e57', 'code' => 'PANTONE 18-1312 TCX'],
        '19-1218 TCX' => ['name' => 'Cocoa Brown', 'hex' => '#3d2b1f', 'code' => 'PANTONE 19-1218 TCX'],
        '13-1006 TCX' => ['name' => 'Creme Brulee', 'hex' => '#dbccb5', 'code' => 'PANTONE 13-1006 TCX'],
        '14-1118 TCX' => ['name' => 'Warm Beige', 'hex' => '#d4c2ad', 'code' => 'PANTONE 14-1118 TCX'],
        '17-1210 TCX' => ['name' => 'Snow White', 'hex' => '#f2f0eb', 'code' => 'PANTONE 17-1210 TCX'],
        '18-4005 TCX' => ['name' => 'Steel Gray', 'hex' => '#707372', 'code' => 'PANTONE 18-4005 TCX'],
        '13-0905 TCX' => ['name' => 'Birch', 'hex' => '#ded6c6', 'code' => 'PANTONE 13-0905 TCX'],
        '16-1452 TCX' => ['name' => 'Amberglow Orange', 'hex' => '#dc793e', 'code' => 'PANTONE 16-1452 TCX'],
        '19-1763 TCX' => ['name' => 'Formula One Red', 'hex' => '#b12b28', 'code' => 'PANTONE 19-1763 TCX'],
        '14-4510 TCX' => ['name' => 'Aqua Splash Blue', 'hex' => '#88d0d1', 'code' => 'PANTONE 14-4510 TCX'],
        '18-1630 TCX' => ['name' => 'Dusty Cedar', 'hex' => '#ad5d5d', 'code' => 'PANTONE 18-1630 TCX'],
        '14-1318 TCX' => ['name' => 'Rose Quartz Pink', 'hex' => '#f7cac9', 'code' => 'PANTONE 14-1318 TCX'],
        '15-3919 TCX' => ['name' => 'Serenity Blue', 'hex' => '#92a8d1', 'code' => 'PANTONE 15-3919 TCX'],
        '19-4006 TCX' => ['name' => 'Caviar Black', 'hex' => '#292a2d', 'code' => 'PANTONE 19-4006 TCX'],
        '19-4050 TCX' => ['name' => 'Nautical Navy', 'hex' => '#253b56', 'code' => 'PANTONE 19-4050 TCX'],
        '16-1359 TCX' => ['name' => 'Orange Peel', 'hex' => '#fa7a35', 'code' => 'PANTONE 16-1359 TCX'],
    ];

    /**
     * Clean and normalize a string by stripping PANTONE prefix, spaces, and punctuation.
     */
    protected static function normalizeKey(string $code): string
    {
        $clean = strtoupper(trim($code));
        $clean = str_replace(['PANTONE', '®', 'PMS'], '', $clean);
        $clean = preg_replace('/[^A-Z0-9]/', '', $clean);
        return trim($clean);
    }

    /**
     * Extract core numerical key (e.g., "7406 C" -> "7406", "19-4052 TCX" -> "194052").
     */
    protected static function extractCoreNumber(string $code): string
    {
        $clean = strtoupper(trim($code));
        $clean = str_replace(['PANTONE', '®', 'PMS', 'TCX', 'TPG', 'TSX', 'CP', 'UP'], '', $clean);
        // Strip trailing single letters like C or U if after digits
        $clean = preg_replace('/\b([0-9]+)\s*[CU]\b/', '$1', $clean);
        $digits = preg_replace('/[^0-9]/', '', $clean);
        return $digits;
    }

    /**
     * Lookup Pantone color details by code with multi-tier matching and algorithmic fallback.
     *
     * @param string $code
     * @return array{found: bool, name: string, hex: string, rgb: string, code: string}|null
     */
    public static function lookup(string $code): ?array
    {
        $input = trim($code);
        if (empty($input)) {
            return null;
        }

        $inputNormalized = self::normalizeKey($input);
        $inputCoreNumber = self::extractCoreNumber($input);

        // ─── TIER 1: Exact / Direct Key Match ────────────────────────────────
        foreach (self::$pantoneLibrary as $key => $data) {
            $keyNormalized = self::normalizeKey($key);
            if ($inputNormalized === $keyNormalized) {
                return self::formatResult($data, true);
            }
        }

        // ─── TIER 2: Core Number / Suffix-Agnostic Match ─────────────────────
        if (!empty($inputCoreNumber) && strlen($inputCoreNumber) >= 3) {
            foreach (self::$pantoneLibrary as $key => $data) {
                $keyCoreNumber = self::extractCoreNumber($key);
                if (!empty($keyCoreNumber) && $inputCoreNumber === $keyCoreNumber) {
                    $formattedCode = self::formatPantoneTitle($input);
                    $result = array_merge($data, ['code' => $formattedCode]);
                    return self::formatResult($result, true);
                }
            }
        }

        // ─── TIER 3: Algorithmic Nearest Shade Fallback ─────────────────────
        $fallback = self::generateAlgorithmicFallback($input, $inputCoreNumber);
        return self::formatResult($fallback, false);
    }

    /**
     * Algorithmic Fallback Generator for unmapped Pantone codes.
     * Generates a remarkably accurate HEX swatch and descriptive name based on numeric ranges & TCX patterns.
     *
     * @param string $rawInput
     * @param string $coreNum
     * @return array{name: string, hex: string, code: string}
     */
    protected static function generateAlgorithmicFallback(string $rawInput, string $coreNum): array
    {
        $formattedCode = self::formatPantoneTitle($rawInput);

        // 1. TCX / TPG 6-digit Format (XX-XXXX)
        if (preg_match('/^([0-1][0-9])-?([0-9]{2})([0-9]{2})$/', $coreNum, $m)) {
            $first2 = (int)$m[1]; // Lightness (11=bright light, 19=dark)
            $mid2   = (int)$m[2]; // Hue category (00-15=yellow/orange, 16-30=red/pink, 31-45=purple, 46-60=blue, 61-75=green, 76-99=grey)
            $last2  = (int)$m[3]; // Saturation / Chroma

            // Calculate Lightness (10% to 90%)
            $lightness = max(12, min(88, 100 - (($first2 - 10) * 9.5)));

            // Calculate Hue (0 to 360)
            if ($mid2 <= 15) {
                $hue = ($mid2 / 15) * 45; // 0..45 (Yellow..Orange)
                $hueName = 'Warm Yellow/Orange';
            } elseif ($mid2 <= 30) {
                $hue = 45 + (($mid2 - 15) / 15) * 35; // 45..80 (Orange..Red/Pink)
                $hueName = 'Coral Red';
            } elseif ($mid2 <= 45) {
                $hue = 260 + (($mid2 - 30) / 15) * 40; // 260..300 (Violet/Purple)
                $hueName = 'Deep Purple';
            } elseif ($mid2 <= 60) {
                $hue = 195 + (($mid2 - 45) / 15) * 45; // 195..240 (Cyan/Blue)
                $hueName = 'Ocean Blue';
            } elseif ($mid2 <= 75) {
                $hue = 110 + (($mid2 - 60) / 15) * 60; // 110..170 (Green/Teal)
                $hueName = 'Botanical Green';
            } else {
                $hue = 30 + (($mid2 - 75) / 25) * 30; // Greys / Earth
                $hueName = 'Slate / Neutral';
            }

            // Saturation based on last2
            $saturation = max(15, min(92, ($last2 / 60) * 85));
            if ($mid2 >= 76) {
                $saturation = min(25, $saturation);
            }

            $hex = self::hslToHex($hue, $saturation, $lightness);
            return [
                'name' => "Pantone {$rawInput} (Approx. {$hueName})",
                'hex'  => $hex,
                'code' => $formattedCode,
            ];
        }

        // 2. PMS 3 or 4-Digit Range Deduction
        if (!empty($coreNum) && is_numeric($coreNum)) {
            $num = (int)$coreNum;

            if ($num >= 100 && $num <= 149) {
                $hex = '#f5c518';
                $name = "Pantone {$coreNum} (Approx. Sun Yellow)";
            } elseif ($num >= 150 && $num <= 179) {
                $hex = '#f26522';
                $name = "Pantone {$coreNum} (Approx. Amber Orange)";
            } elseif ($num >= 180 && $num <= 209) {
                $hex = '#d11242';
                $name = "Pantone {$coreNum} (Approx. Crimson Red)";
            } elseif ($num >= 210 && $num <= 249) {
                $hex = '#e21b79';
                $name = "Pantone {$coreNum} (Approx. Magenta Pink)";
            } elseif ($num >= 250 && $num <= 279) {
                $hex = '#6c2d91';
                $name = "Pantone {$coreNum} (Approx. Royal Violet)";
            } elseif ($num >= 280 && $num <= 319) {
                $hex = '#0054a6';
                $name = "Pantone {$coreNum} (Approx. Cobalt Blue)";
            } elseif ($num >= 320 && $num <= 399) {
                $hex = '#00a651';
                $name = "Pantone {$coreNum} (Approx. Emerald Green)";
            } elseif ($num >= 400 && $num <= 499) {
                $hex = '#795548';
                $name = "Pantone {$coreNum} (Approx. Earth Taupe)";
            } elseif ($num >= 700 && $num <= 739) {
                $hex = '#a08266';
                $name = "Pantone {$coreNum} (Approx. Warm Sand)";
            } elseif ($num >= 7400 && $num <= 7429) {
                $hex = '#f3a812';
                $name = "Pantone {$coreNum} (Approx. Warm Gold)";
            } elseif ($num >= 7430 && $num <= 7459) {
                $hex = '#0072ce';
                $name = "Pantone {$coreNum} (Approx. Azure Blue)";
            } elseif ($num >= 7460 && $num <= 7499) {
                $hex = '#00965e';
                $name = "Pantone {$coreNum} (Approx. Meadow Green)";
            } elseif ($num >= 800 && $num <= 899) {
                $hex = '#39ff14';
                $name = "Pantone {$coreNum} (Approx. Neon Fluorescent)";
            } else {
                $hex = '#4b5563';
                $name = "Pantone {$coreNum} (Custom Standard)";
            }

            return [
                'name' => $name,
                'hex'  => $hex,
                'code' => $formattedCode,
            ];
        }

        // Generic fallback
        return [
            'name' => 'Custom Pantone Color',
            'hex'  => '#374151',
            'code' => $formattedCode,
        ];
    }

    /**
     * Format Pantone code string cleanly for UI badges (e.g. "7406 C" -> "PANTONE 7406 C").
     */
    protected static function formatPantoneTitle(string $raw): string
    {
        $formatted = strtoupper(trim($raw));
        if (!str_starts_with($formatted, 'PANTONE')) {
            $formatted = 'PANTONE ' . $formatted;
        }
        return $formatted;
    }

    /**
     * Format result with RGB array representation.
     *
     * @param array{name: string, hex: string, code: string} $data
     * @param bool $found
     * @return array{found: bool, name: string, hex: string, rgb: string, code: string}
     */
    protected static function formatResult(array $data, bool $found): array
    {
        $rgb = self::hexToRgb($data['hex']);
        return [
            'found' => $found,
            'name'  => $data['name'],
            'hex'   => strtolower($data['hex']),
            'rgb'   => "rgb({$rgb[0]}, {$rgb[1]}, {$rgb[2]})",
            'code'  => $data['code'],
        ];
    }

    /**
     * Convert HSL color values to HEX color code.
     */
    protected static function hslToHex(float $h, float $s, float $l): string
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        if ($h < 60) {
            $r = $c; $g = $x; $b = 0;
        } elseif ($h < 120) {
            $r = $x; $g = $c; $b = 0;
        } elseif ($h < 180) {
            $r = 0; $g = $c; $b = $x;
        } elseif ($h < 240) {
            $r = 0; $g = $x; $b = $c;
        } elseif ($h < 300) {
            $r = $x; $g = 0; $b = $c;
        } else {
            $r = $c; $g = 0; $b = $x;
        }

        $r = (int)round(($r + $m) * 255);
        $g = (int)round(($g + $m) * 255);
        $b = (int)round(($b + $m) * 255);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Convert HEX color string to RGB array.
     *
     * @return array{0: int, 1: int, 2: int}
     */
    protected static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }

    /**
     * Get all library items for autocomplete datalist / API.
     *
     * @return array<int, array{name: string, hex: string, code: string}>
     */
    public static function getAll(): array
    {
        return array_values(self::$pantoneLibrary);
    }
}
