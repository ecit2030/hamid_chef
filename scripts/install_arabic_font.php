<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$fontDir = __DIR__ . '/../storage/fonts';

$options = new Options();
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontDir);

$dompdf = new Dompdf($options);

// Get the font metrics
$fontMetrics = $dompdf->getFontMetrics();

// Register Amiri font
$fontMetrics->registerFont(
    ['family' => 'Amiri', 'style' => 'normal', 'weight' => 'normal'],
    $fontDir . '/Amiri-Regular.ttf'
);

$fontMetrics->registerFont(
    ['family' => 'Amiri', 'style' => 'normal', 'weight' => 'bold'],
    $fontDir . '/Amiri-Bold.ttf'
);

echo "Amiri font registered successfully!\n";
