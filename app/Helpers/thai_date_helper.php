<?php

if (!function_exists('thai_date_short')) {
    /**
     * แปลงวันที่เป็นรูปแบบสั้นภาษาไทย พ.ศ. (เช่น 23/08/2569)
     */
    function thai_date_short($dateStr): string
    {
        if (empty($dateStr) || $dateStr === '0000-00-00' || $dateStr === '0000-00-00 00:00:00') {
            return '-';
        }
        $time = strtotime($dateStr);
        if (!$time) {
            return $dateStr;
        }
        $day = date('d', $time);
        $month = date('m', $time);
        $year = (int)date('Y', $time) + 543;
        return "{$day}/{$month}/{$year}";
    }
}

if (!function_exists('thai_date_full')) {
    /**
     * แปลงวันที่เป็นรูปแบบเต็มภาษาไทย พ.ศ. (เช่น 23 สิงหาคม 2569)
     */
    function thai_date_full($dateStr, $showTime = false): string
    {
        if (empty($dateStr) || $dateStr === '0000-00-00' || $dateStr === '0000-00-00 00:00:00') {
            return '-';
        }
        $time = strtotime($dateStr);
        if (!$time) {
            return $dateStr;
        }
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
            4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
            10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $day = (int)date('j', $time);
        $month = $thaiMonths[(int)date('n', $time)] ?? '';
        $year = (int)date('Y', $time) + 543;

        $formatted = "{$day} {$month} {$year}";
        if ($showTime) {
            $formatted .= ' ' . date('H:i น.', $time);
        }
        return $formatted;
    }
}

if (!function_exists('thai_date_medium')) {
    /**
     * แปลงวันที่เป็นรูปแบบย่อภาษาไทย พ.ศ. (เช่น 23 ส.ค. 2569)
     */
    function thai_date_medium($dateStr): string
    {
        if (empty($dateStr) || $dateStr === '0000-00-00' || $dateStr === '0000-00-00 00:00:00') {
            return '-';
        }
        $time = strtotime($dateStr);
        if (!$time) {
            return $dateStr;
        }
        $thaiShortMonths = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.',
            4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
            7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.',
            10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];
        $day = (int)date('j', $time);
        $month = $thaiShortMonths[(int)date('n', $time)] ?? '';
        $year = (int)date('Y', $time) + 543;

        return "{$day} {$month} {$year}";
    }
}

if (!function_exists('thai_year')) {
    /**
     * แปลงปี ค.ศ. เป็น พ.ศ. (เช่น 2026 -> 2569)
     */
    function thai_year($year = null): int
    {
        $y = $year ? (int)$year : (int)date('Y');
        return $y < 2400 ? $y + 543 : $y;
    }
}
