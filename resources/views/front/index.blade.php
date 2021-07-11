<html style="font-size: 72px;">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no, email=no, date=no, address=no" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="yes" name="apple-touch-fullscreen" />
    <meta name="robots" content="noindex" />
    <meta name="googlebot" content="noindex" />
    <meta name="facebook-domain-verification" content="4m6ffvq9fvv41noqc3kfwbs26g1113" />
    <title>Welcome To {{env('SITE_NAME', '')}}</title>
    <link rel="shortcut icon" href="{{\App\Extensions\Util::to('/static/image/min_logo.jpeg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{\App\Extensions\Util::to('/static/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{\App\Extensions\Util::to('/static/css/productindex.css')}}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-C59FXDPNF3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-C59FXDPNF3');
    </script>
    <script>
        //控制响应式
        var fun = function(doc, win) {
            var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function() {
                    var clientWidth = docEl.clientWidth;
                    if (!clientWidth) return;
                    if (clientWidth >= 540) {
                        docEl.style.fontSize = 72 + 'px';
                        // console.log(clientWidth)
                    } else {
                        docEl.style.fontSize = (clientWidth / 750) * 100 + 'px';
                    }
                };
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            doc.addEventListener('DOMContentLoaded', recalc, false);
        };
        fun(document, window);
    </script>
</head>
<body style="max-width: 7.5rem; margin: auto; font-family: Helvetica;">
<div id="english" style="position: relative; display: block;">
    <div class="home" style="position: relative;">
        <div class="header" style="">
            <img src="{{\App\Extensions\Util::to('/static/image/back.png')}}" onclick="window.location.href='javascript:history.go(-1)';" alt="" class="backen click-btn-indextohome" />
{{--            <div class="logol click-btn-indextohome" style="height:1rem;line-height: 1rem;text-align: center">--}}
{{--                one-page--}}
{{--            </div>--}}

            <img src="{{\App\Extensions\Util::to('/static/image/logo.jpeg')}}" alt="" class="logol click-btn-indextohome" />
{{--            <img src="{{\App\Extensions\Util::to('/static/image/homeicon.png')}}" alt="" class="homeicon click-btn-indextohome" />--}}
        </div>
        <div style="height: 0.98rem;">
        </div>
        <div style="position: fixed; right: 10px; float: right; bottom: 3rem; z-index: 16000160;">
            <a href="https://api.whatsapp.com/send?phone={{\App\Model\WhatApp::getCurrentWhatApp()}}&text=hi%2CI+want+to+ask+something+about+stayshab&amp;source=&amp;data=">
                <img src="/static/image/whatsapp.gif" style="width: 55px; height: 55px;">
            </a>
        </div>
        <div class="productName">
            <div class="productName-big">
                Welcome, Pls contact us by
                <a class="display:inline-black" href="https://api.whatsapp.com/send?phone={{\App\Model\WhatApp::getCurrentWhatApp()}}&text=hi%2CI+want+to+ask+something+about+stayshab&amp;source=&amp;data=">
                    whatsapp
                </a>
                <br>
                مرحبا. يرجى الاتصال بالواتساب
            </div>
{{--            <div class="productName-small">--}}
{{----}}
{{--            </div>--}}
            <div class="clear" style="float: none;"></div>
        </div>
    </div>
</div>
</body>
</html>