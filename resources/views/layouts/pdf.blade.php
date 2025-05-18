<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Document' }}</title>

    <style>
        .header {
            position: fixed;
            top: -30px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
        }
        .header img {
            height: 60px;
        }
        body {
            margin-top: 60px;
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
        table td, table th {
            padding: 8px;
            font-size: 12px;
        }
        table .border {
            border: 1px solid #00000034;
        }
        table tr {
            vertical-align: text-top;
        }
        .m-0 {
            margin: 0;
        }
        ul {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('media/logo.jpeg') }}" alt="Logo">
    </div>
    <div class="">
        @yield('content')
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>