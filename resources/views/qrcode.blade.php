<!DOCTYPE html>
<html>
<head>
    <title>QRコード</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .qr-code-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div>
        {{ $qr_text }}
    </div>
    <div class="qr-code-container">
        {{ $qrCode }}
    </div>
    <div>
        物件名: {{ $apatment_names }}
    </div>
</body>
</html>
