<!DOCTYPE html>
<html>
<head>
    <title>QRコード</title>
    <style>
        .qr-code-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-code-text {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="qr-code-text">
        {{ $qr_text }}
    </div>
    <div class="qr-code-container">
        {{ $qrCode }}
    </div>
    <div class="qr-code-text">
       {{ $apatment_names }}
    </div>
</body>
</html>
