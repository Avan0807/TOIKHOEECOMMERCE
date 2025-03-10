<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang mở ứng dụng...</title>
    <meta name="robots" content="noindex, nofollow">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var slug = "{{ $slug }}";
            var refCode = "{{ $refCode }}";
            var appLink = "toikhoeapp://product-detail/" + slug + "?ref=" + refCode;
            var webLink = "https://toikhoe.vn/product-detail/" + slug + "?ref=" + refCode;
            var storeLink = ""; // Đổi link tải app nếu có link trực tiếp trên App Store/Google Play

            var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            var isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            var isAndroid = /Android/i.test(navigator.userAgent);

            if (isMobile) {
                var startTime = Date.now();
                var hiddenIframe = document.createElement("iframe");
                hiddenIframe.style.display = "none";
                hiddenIframe.src = appLink;
                document.body.appendChild(hiddenIframe);

                setTimeout(function() {
                    var elapsedTime = Date.now() - startTime;
                    if (elapsedTime < 2000) {
                        document.getElementById("fallback-message").style.display = "block";
                    }
                }, 1500);
            } else {
                document.getElementById("fallback-message").style.display = "block";
            }

            document.getElementById("open-web").addEventListener("click", function() {
                window.location.href = webLink;
            });

            document.getElementById("download-app").addEventListener("click", function() {
                window.location.href = storeLink;
            });
        });
    </script>
</head>
<body>
    <p>Nếu ứng dụng không tự động mở, hãy <a href="toikhoeapp://product-detail/{{ $slug }}?ref={{ $refCode }}">bấm vào đây</a>.</p>
    <div id="fallback-message" style="display: none;">
        <p>Ứng dụng không mở được? Bạn có thể:</p>
        <button id="open-web">Mở trang web</button>
        <button id="download-app">Tải ứng dụng</button>
    </div>
</body>
</html>
