<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            text-align: center;
            padding-top: 3mm;
            color: #666;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 2mm;
            width: 100%;
        }
        .page-number:after {
            content: counter(page);
        }
        .page-count:after {
            content: counter(pages);
        }
    </style>
</head>
<body>
    <div class="footer">
        PixelPerfect - {{ __('Avenue de la gare 1, 1880 Bex - Tél.: +41 (0)24 444 44 44') }} |
        {{ __('Page') }} <span class="page-number"></span>
    </div>
</body>
</html>
