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
    <title>Welcome To {{env('SITE_NAME', '')}}</title>
    <link rel="shortcut icon" href="{{\App\Extensions\Util::to('/static/image/min_logo.jpeg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{\App\Extensions\Util::to('/static/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{\App\Extensions\Util::to('/static/css/productindex.css')}}" />
    <link rel="stylesheet" href="{{\App\Extensions\Util::to('/static/css/select2.css')}}" />
    <style type="text/css">
        .select2-container .select2-selection--single {
            height: .80rem;
            line-height: .80rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered
        {
            line-height: .80rem;
        }
        .good-services {
            font-size: 12px;
            width: 100%;
            padding: 10px 5px;
        }
        .clear:after, .clear:before {
            display: table;
            clear: both;
            content: "";
        }
        .good-services .item {
            float: left;
            width: 33.3%;
            box-sizing: border-box;
        }
        .good-services .item:first-child {
            text-align: left;
        }
        .good-services .item:nth-child(2) {
            text-align: center;
        }
        .good-services .item:nth-child(3) {
            text-align: right;
        }
        .good-services .item span {
            display: inline-block;
            margin-right: 3px;
            font-size: 12px;
            line-height: 16px;
            height: 16px;
            width: 16px;
            border-radius: 9px;
            text-align: center;
            border: 1px solid #666;
        }
        .good-services.good-services-style-1 span {
            vertical-align: baseline;
        }
        .good-services .item>div {
            display: inline-block;
        }
        .select2-container--default .select2-selection--single{
            border: none;
        }
        .select2-container .select2-selection--single .select2-selection__rendered{
            padding: 0;
        }
        .tellabel .select2-container .select2-selection--single{
            display: inline-block;
        }
        .select2-selection__arrow{
            display: none;
        }
    </style>
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
{{--            <img src="{{\App\Extensions\Util::to('/static/image/back.png')}}" alt="" class="backen click-btn-indextohome" />--}}
{{--            <div class="logol click-btn-indextohome" style="height:1rem;line-height: 1rem;text-align: center">--}}
{{--                one-page--}}
{{--            </div>--}}

            <img src="{{\App\Extensions\Util::to('/static/image/logo.jpeg')}}" alt="" class="logol click-btn-indextohome" />
{{--            <img src="{{\App\Extensions\Util::to('/static/image/homeicon.png')}}" alt="" class="homeicon click-btn-indextohome" />--}}
        </div>
        <div style="height: 0.98rem;">
        </div>
        <div class="ban" style="width: 100%;">
            <div id="divs2" style="overflow: hidden; display: flex; position: relative;">
            </div>
        </div>
        <div class="productName">
            <div class="pricecontainer">
                <span class="dispricetext" style="display: inline-block;">{{\App\Extensions\Util::currencyFormat($product->price, $price_info['currency_code'])['price']}}</span>
                <span class="pricetext" style="display: inline-block;">{{\App\Extensions\Util::currencyFormat((int)($product->price / 10 * (11+($product->id%4))) , $price_info['currency_code'])['price']}}</span>
            </div>
            <div class="productName-big">
                {{$product->title}}
            </div>
            <div class="productName-small">
                {{$product->description}}
            </div>
            <div class="clear" style="float: none;"></div>
        </div>
        <div class="good-services good-services-style-1 clear">
            <div class="item">
                <span style="color: #b10c0c; border-color: #b10c0c;">$</span>
                <div>COD</div>
            </div>
            <div class="item">
						<span style="color: #b10c0c; border-color: #b10c0c; vertical-align: bottom; padding-top: 1px;">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1024 1024" version="1.1" height="12px">
<path fill="#b10c0c" d="M837.174 673.388c-25.022 0-48.792 9.8-66.724 27.523-17.932 17.932-27.94 41.286-27.94 66.307s9.8 48.375 27.94 66.307c18.14 17.723 41.702 27.523 66.724 27.523 51.294 0 92.996-42.12 92.996-93.83s-41.702-93.83-92.996-93.83z m0 145.958c-28.775 0-52.962-23.77-52.962-52.128s24.187-52.128 52.962-52.128c28.357 0 51.294 23.353 51.294 52.128s-22.937 52.128-51.294 52.128z m24.187-458.517c-3.753-3.545-8.757-5.421-13.97-5.421H740.424c-11.468 0-20.85 9.383-20.85 20.851v170.98c0 11.468 9.382 20.85 20.85 20.85h169.729c11.468 0 20.85-9.382 20.85-20.85V432.765c0-5.839-2.501-11.469-6.88-15.43l-62.762-56.507z m27.94 165.558H761.277V396.902h77.983l50.043 45.038v84.447z m-561.312 147c-25.022 0-48.792 9.8-66.724 27.524-17.932 17.932-27.94 41.286-27.94 66.307s9.8 48.375 27.94 66.307c18.14 17.723 41.702 27.523 66.724 27.523 51.293 0 92.996-42.12 92.996-93.83s-41.703-93.83-92.996-93.83z m0 145.959c-28.775 0-52.962-23.77-52.962-52.128s24.187-52.128 52.962-52.128c28.357 0 51.293 23.353 51.293 52.128s-22.936 52.128-51.293 52.128zM188.91 699.035h-41.91V643.57c0-11.468-9.383-20.851-20.852-20.851s-20.85 9.383-20.85 20.85v76.316c0 11.468 9.382 20.851 20.85 20.851h62.762c11.469 0 20.852-9.383 20.852-20.851s-9.383-20.851-20.852-20.851z m105.716-113.639c0-11.468-9.383-20.851-20.851-20.851H20.85C9.383 564.545 0 573.928 0 585.396s9.383 20.851 20.851 20.851h252.925c11.468 0 20.85-9.174 20.85-20.85zM63.179 510.123l252.924 1.46c11.469 0 20.852-9.175 21.06-20.643 0.209-11.676-9.174-21.06-20.643-21.06l-252.924-1.459h-0.209c-11.468 0-20.85 9.175-20.85 20.643-0.21 11.676 9.174 21.06 20.642 21.06z m42.536-94.664H358.64c11.468 0 20.85-9.383 20.85-20.851s-9.382-20.851-20.85-20.851H105.715c-11.468 0-20.85 9.383-20.85 20.851s9.382 20.851 20.85 20.851z m910.57-28.149L866.991 263.663c-3.753-3.128-8.34-4.796-13.345-4.796H678.08v-75.064c0-11.468-9.383-20.851-20.85-20.851h-531.08c-11.468 0-20.85 9.383-20.85 20.85v152.631c0 11.468 9.382 20.851 20.85 20.851s20.852-9.383 20.852-20.85v-131.78h489.585v494.38h-170.98c-11.468 0-20.851 9.384-20.851 20.852s9.383 20.851 20.851 20.851H720.2c11.468 0 20.85-9.383 20.85-20.851s-9.382-20.851-20.85-20.851h-41.911V300.569h168.06l135.95 112.597-1.46 285.452h-21.685c-11.468 0-20.851 9.383-20.851 20.85s9.383 20.852 20.85 20.852h42.329c11.468 0 20.85-9.174 20.85-20.643L1024 403.574c-0.209-6.255-2.92-12.302-7.715-16.264z"></path></svg></span>
                <div>Free postage</div>
            </div>
            <div class="item">
                <span style="color: #b10c0c; border-color: #b10c0c;">7</span>
                <div>7-day return</div>
            </div>
        </div>

        <div class="imgContainer visible-img-detail" data-gtm-vis-first-on-screen-13511651_20="2044" data-gtm-vis-total-visible-time-13511651_20="100" data-gtm-vis-has-fired-13511651_20="1">
            <?php $i = 5; ?>
{{--                @if($i == $product->video_position && $product->video_link)--}}
{{--                    <div class="video" style="font-family: Helvetica;">--}}
{{--                        <video src="{{$product->video_link}}" controls="controls">--}}
{{--                            --}}{{--                                                       poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
{{--                        </video>--}}
{{--                    </div>--}}
{{--                @endif--}}
            @foreach($product->detail_pictures as $k => $picture)
            <div>
                <img src="{{\App\Extensions\Util::to($picture)}}" alt="" />
                <div class="imgText" style="display: none;"></div>
                <div class="imgText" style="display: none;"></div>
            </div>

                <?php $i++ ?>
{{--                    @if($i == $product->video_position && $product->video_link)--}}
{{--                        <div class="video" style="font-family: Helvetica;">--}}
{{--                            <video src="{{$product->video_link}}" controls="controls">--}}
{{--                                --}}{{--                                                       poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
{{--                            </video>--}}
{{--                        </div>--}}
{{--                    @endif--}}
            @endforeach
            @foreach($product->sku_pictures as $child)
                <div>
                    <img src="{{\App\Extensions\Util::to($child)}}" alt="" />
                    <div class="imgText" style="display: none;"></div>
                    <div class="imgText" style="display: none;"></div>
                </div>
{{--                @if($i == $product->video_position && $product->video_link)--}}
{{--                    <div class="video" style="font-family: Helvetica;">--}}
{{--                        <video src="{{$product->video_link}}" controls="controls">--}}
{{--                                --}}{{--                   poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
{{--                        </video>--}}
{{--                    </div>--}}
{{--                @endif--}}
                <?php $i++ ?>
            @endforeach
                @if($gift_product)
                    @foreach($gift_product->pictures as $picture)
                        <div>
                            <img src="{{\App\Extensions\Util::to($picture)}}" alt="" />
                            <div class="imgText" style="display: none;"></div>
                            <div class="imgText" style="display: none;"></div>
                        </div>
                    @endforeach
                @endif
{{--            @if($i<=$product->video_position && $product->video_link)--}}
{{--                <div class="video" style="font-family: Helvetica;">--}}
{{--                    <video src="{{$product->video_link}}" controls="controls"--}}
{{--                            --}}{{--                   poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
{{--                    >--}}
{{--                    </video>--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
        <div class="cover-bg" style="display: none;"></div>
{{--        <div class="service-pachic">--}}
{{--            <div>--}}
{{--                <img src="{{\App\Extensions\Util::to('/static/image/car.png')}}" alt="" style="width: 0.58rem; height: 0.44rem; margin-bottom: 0.1rem; margin-top: 0.1rem;" />--}}
{{--                <div class="serviceblock" style="">--}}
{{--                    Cash on Delivery--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                <img src="{{App\Extensions\Util::to('/static/image/airplain.png')}}" alt="" style="width: 0.54rem; height: 0.54rem; margin-bottom: 0.05rem; margin-top: 0.05rem;" />--}}
{{--                <div class="serviceblock" style="">--}}
{{--                    Free Shipping--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                <img src="{{\App\Extensions\Util::to('/static/image/good.png')}}" alt="" style="width: 0.54rem; height: 0.54rem; margin-bottom: 0.1rem;" />--}}
{{--                <div class="serviceblock" style="">--}}
{{--                    Genuine Guarantee--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="Consumer-Services english visible-info-contactus" style="display: block;" data-gtm-vis-first-on-screen-13511651_21="202" data-gtm-vis-total-visible-time-13511651_21="100" data-gtm-vis-has-fired-13511651_21="1">--}}
{{--            <div id="accordion2" role="tablist" aria-multiselectable="true" class="panel-group">--}}
{{--                <div class="panel panel-default">--}}
{{--                    <div role="tab" class="panel-heading">--}}
{{--                        <div role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="panel-title collapsed" style="overflow: hidden;">--}}
{{--                            <div style="float: left; color: rgb(249, 62, 62);">--}}
{{--                                How to Order?--}}
{{--                            </div>--}}
{{--                            <img src="https://sp.gcc-friday.com/static/singlepage/img/xiangxia.jpg" alt="" class="fold " />--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div id="collapseTwo" role="tabpanel" aria-expanded="true" class="panel-collapse collapse in">--}}
{{--                        <div class="panel-body service-text">--}}
{{--                            <p><span>1.</span>Click the Shop Now button and Choose your color and size (if so)</p>--}}
{{--                            <p><span>2.</span>Fill in your name, phone number, delivery address</p>--}}
{{--                            <p><span>3.</span>Click the Confirm button</p>--}}
{{--                            <p><span>4.</span>Wait for us to contact you</p>--}}
{{--                            <p> Any inquiry please contact WhatsApp--}}
{{--                                <a href="https://api.whatsapp.com/send?phone={{\App\Model\WhatApp::getCurrentWhatApp()}}&amp;text=hi%2CI+want+to+ask+something+about+{{$product->product_no}}+{{$price_info['currency_code'] . ' ' . $product->price}}+&amp;source=&amp;data=" target="_blank">--}}
{{--                                    <span style="color: rgb(249, 62, 62); border-bottom: 0.01rem solid rgb(249, 62, 62);">{{\App\Model\WhatApp::getCurrentWhatApp()}}</span>--}}
{{--                                </a>--}}
{{--                                <br /> Expected delivery time: about 10-20 days. </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="TermsContainer">
            @foreach($term_services as $term_service)
            <div>
                <a href="/{{$term_service['name']}}">{{$term_service['name']}}</a>
            </div>
            @endforeach
        </div>
        <div class="cover-content" style="display: block;">
            <div id="option">
                <div style="position:fixed;right:10px;float:right;bottom:3rem;z-index: 16000160">
                    <a href="https://api.whatsapp.com/send?phone={{\App\Model\WhatApp::getCurrentWhatApp()}}&amp;text=hi%2CI+want+to+ask+something+about+{{$product->product_no}}+{{$price_info['currency_code'] . ' ' . $product->price}}&amp;source=&amp;data=">
                        <img src="{{\App\Extensions\Util::to('/static/image/whatsapp.gif')}}" style="width:55px;height:55px;">
                    </a>
                </div>
                <div class="addressfirst" style="display: block">
                    <div>
                        Product Information:
                    </div>
                </div>
                <div class="option-title english" style="display: block;">
                    <img v-bind:src="sku_image" alt="" class="click-skuimg">
                    <div class="option-title-right" style="text-align: left;">
                        <div class="option-title-first">{{$product->title}}</div>
                        <div class="option-title-second" style="overflow: hidden;">
                            <div class="option-price">
                                <span>{{\App\Extensions\Util::currencyFormat($product->price, $price_info['currency_code'])['price']}}</span>
                                <span class="allpricenodis">{{\App\Extensions\Util::currencyFormat((int)($product->price / 10 * (11+($product->id%4))) , $price_info['currency_code'])['price']}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="select-option" style="overflow-y: scroll;">
                    <div class="allOption">
                        Product details &nbsp;&nbsp; (Please select)
                    </div>
                    <div class="option-btn-area" style="">
                        <div style="width:100%;min-height:0.7rem;max-height: 2.8rem" v-for="(main_button, main_select_title) in skus.main_option">
                            <div v-text="main_select_title" style="float: left; margin-right: 0.3rem; position: relative;color: #9fa0a0;font-size: .21rem;padding: .05rem .2rem;width: 1rem;"></div>
                            <div class="option-btn" v-for="(value, index) in main_button"  v-bind:class="{optionActive : main_option_value == index}" @click="changeMainOption(index)" v-text="index" style="float: left; margin-right: 0.3rem; position: relative;">
                            </div>
                        </div>
                        <div style="width:100%;min-height:0.7rem;max-height: 2.8rem" class="clearfix" v-for="(option_value, option_key) in options">
                            <div v-text="option_value.option_name" style="float: left; margin-right: 0.3rem; position: relative;color: #9fa0a0;font-size: .21rem;padding: .05rem .2rem;width: 1rem;"></div>
                            <div class="option-btn" v-for="(value, index) in option_value.option_value"  v-bind:class="{optionActive : value == sku_options[option_key]}" @click="changeSku(option_key,value)" v-text="value" style="float: left; margin-right: 0.3rem; position: relative;">
                            </div>
                        </div>
                    </div>
                    @if($gift_product)
                    <div class="giftAllOption">
                        {{$gift_product->gift_title}}
                    </div>
                        <div class="gift-option-title english" style="display: block;">
                            <img v-bind:src="gift_sku_image" alt="" class="click-skuimg">
                        </div>
                    <div class="option-btn-area" style="">
                        <div style="width:100%;min-height:0.7rem;max-height: 2.8rem" v-for="(main_button, main_select_title) in gift_skus.main_option">
                            <div v-text="main_select_title" style="float: left; margin-right: 0.3rem; position: relative;color: #9fa0a0;font-size: .21rem;padding: .05rem .2rem;width: 1rem;"></div>
                            <div class="option-btn" v-for="(value, index) in main_button"  v-bind:class="{optionActive : gift_main_option_value == index}" @click="changeGiftMainOption(index)" v-text="index" style="float: left; margin-right: 0.3rem; position: relative;">
                            </div>
                        </div>
                        <div style="width:100%;min-height:0.7rem;max-height: 2.8rem" class="clearfix" v-for="(option_value, option_key) in gift_options">
                            <div v-text="option_value.option_name" style="float: left; margin-right: 0.3rem; position: relative;color: #9fa0a0;font-size: .21rem;padding: .05rem .2rem;width: 1rem;"></div>
                            <div class="option-btn" v-for="(value, index) in option_value.option_value"  v-bind:class="{optionActive : value == gift_sku_options[option_key]}" @click="changeGiftSku(option_key,value)" v-text="value" style="float: left; margin-right: 0.3rem; position: relative;">
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="quantity" style="overflow: hidden;">
                        <div class="quantity-title english" style="display: block;">
                            Quantity
                        </div>
                        <div class="ar_quantity" style="overflow: hidden; float: left;">
                            <div class="quantity-sub" @click="decQuantity">
                                -
                            </div>
                            <div class="quantity-number" v-text="quantity">

                            </div>
                            <div class="quantity-add" @click="incQuantity">
                                +
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 0.1rem 0.4rem;">
                    <div class="input-div english" style="display: block;">
                        Total:
                        <div class="input-total" style="float: right;" v-text="total_show">
                        </div>
                    </div>
                    <div class="input-div english" style="display: block;">
                        Shipping Fee:
                        <div class="input-total" style="float: right;">
                            Free Shipping
                        </div>
                    </div>
                    <div class="input-div english" style="display: block;">
                        Payment Method:
                        <div class="input-total" style="float: right;">
                            Cash on Delivery
                        </div>
                    </div>
                </div>
                <div class="address" style="position: relative; display: block; font-family: Helvetica;">
                    <div class="addressfirst" style="display: block">
                        <div>
                            Shipping Information:
                        </div>
                    </div>
                    <div class="addressbody" style="display: block">
                        <div style="position: relative;">
                            <img src="{{\App\Extensions\Util::to('/static/image/usernameicon.png')}}" alt="" class="usernameicon" />
                            <input id="userName" v-model="customer_name" placeholder="Full Name" class="userName inputnew" />
                            <span class="musticon">*</span>
                            <div class="musticontipen" style="display: none;">
                                !
                            </div>
                        </div>
                        <div style="position: relative;">
                            <div class="phoneInfo en_phoneInfo">
                                Phone Number should 9-digit which start with 5
                            </div>
                            <div class="teldiv phonemust">
                                <div class="tellabel">
                                    <select id="phone_select" v-model="pre_phone">
                                        @foreach(array_column(\App\Model\Product::PRICE_COLUMNS, 'pre_phone') as $pre_phone_value)
                                            <option value="{{$pre_phone_value}}">{{$pre_phone_value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                            <img src="{{\App\Extensions\Util::to('/static/image/phoneicon.png')}}" alt="" class="phoneicon" />
                                <input id="phoneNumber" v-model="customer_phone" type="number" @input="numChange"  name="phone" placeholder="*Phone Number" class="phone" />
                            </div>
                            <span class="musticon">*</span>
                            <div class="musticontipen" style="display: none;">
                                !
                            </div>
                        </div>
                        <div style="position: relative;">
                            <img src="{{\App\Extensions\Util::to('/static/image/telicon.png')}}" alt="" class="usernameicon" />
                            <input v-model="whats_app" placeholder="Whatsapp" class="userName inputnew" />
                        </div>
                        <div style="position: relative;">
                            <div class="teldiv phonemust">
                                <div class="countrylabel">
                                    Country
                                </div>
                                <img src="{{\App\Extensions\Util::to('/static/image/stateicon.png')}}" alt="" class="usernameicon" style="top: 0.18rem;" />
                                <select id="country_select" v-model="country_code" disabled="disabled">
                                    @foreach(\App\Model\Order::ORDER_COUNTRIES as $country_code => $country_value)
                                        <option value="{{$country_code}}">{{$country_value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="musticon">*</span>
                            <div class="musticontipen" style="display: none;">
                                !
                            </div>
                        </div>
                        <div style="position: relative;">
                            <div class="addressdiv">
                                <div class="addresslabel">
                                    Address
                                </div>

                                <img src="{{\App\Extensions\Util::to('/static/image/landmarkicon.png')}}" alt="" class="phoneicon" />
                                <textarea id="addressdetail"
                                          class="addressinput"
                                          v-model="address"
                                          maxlength="350">
                                </textarea>
                            </div>
                            <span class="musticon">*</span>
                        </div>
                        <div style="position: relative;">
                            <img src="{{\App\Extensions\Util::to('/static/image/noteicon.png')}}" alt="" class="phoneicon" />
                            <input placeholder="Write a note if you have" v-model="address_note" class="note inputnew" />
                        </div>
                    </div>
{{--                    <div style="height: 1.5rem;"></div>--}}
                    <div class="address-footer" style="">
                        <div @click="beforeSend" class="click-btn-confirmaddress">
                            Order Now
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/static/js/sweetAlert.min.js" type="text/javascript"></script>
<script src="/static/js/axios.min.js"></script>
<script src="/static/js/lbt.js"></script>
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script src="/static/js/vue.min.js" type="text/javascript"></script>
<script src="/static/js/select2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function ($) {
        var data = {
            product_id : {{$product->id}},
            skus : {!! json_encode($product->front_children) !!},
            main_option_value : '{{$product->skus->first()->main_option_value}}',
            option : {},
            options : {},
            sku_options : {},
            gift_skus : {},
            gift_main_option_value : '',
            gift_options : {},
            gift_sku_options : {},
            gift_product_id : 0,
            total : {{$product->price}},
            currency_code : '{{$price_info['currency_code']}}',
            total_show : '{{$price_info['currency_code'] . ' ' . $product->price}}',
            price : {{$product->price}},
            quantity : 1,
            sku : '',
            sku_image : '',
            gift_sku_image : '',
            country_code : '{{$country}}',
            customer_name : '',
            customer_phone : '',
            whats_app : '',
            pre_phone : '{{$price_info['pre_phone']}}',
            address : '',
            phone_length : {{$price_info['phone_length']}},
            phone_length_map : {!! json_encode(array_column(\App\Model\Product::PRICE_COLUMNS, 'phone_length', 'pre_phone' )) !!},
            price_infos : {!! json_encode(\App\Model\Product::PRICE_COLUMNS) !!},
            address_note : '',
        };
        @if($gift_product)
            data.gift_skus = {!! json_encode($gift_product->front_children) !!};
            data.gift_main_option_value = '{{$gift_product->skus->first()->main_option_value}}';
            data.gift_options = {};
            data.gift_sku_options = {};
            data.gift_product_id = {{$gift_product->id}};
        @endif

        var vm = new Vue({
            el: '#option',
            data: data,
            mounted:function(){
                this.sku_image = this.getSkuImage();
                this.options = this.getOptions();
                this.genSkuOptions();
                @if($gift_product)
                    this.gift_sku_image = this.getGiftSkuImage();
                    this.gift_options = this.getGiftOptions();
                    this.genGiftSkuOptions();
                @endif
            },
            methods:{
                genGiftSkuOptions : function(){
                    for (let key in this.gift_options) {
                        this.gift_sku_options[key] = this.gift_options[key]['option_value'][0];
                    }
                },
                genSkuOptions : function(){
                    for (let key in this.options) {
                        this.sku_options[key] = this.options[key]['option_value'][0];
                    }
                },
                getSkuImage : function(){
                    for (let key in this.skus.main_option) {
                        return this.skus.main_option[key][this.main_option_value]['image'];
                    }
                },
                getGiftSkuImage : function(){
                    for (let key in this.gift_skus.main_option) {
                        return this.gift_skus.main_option[key][this.gift_main_option_value]['image'];
                    }
                },
                getGiftOptions : function(){
                    return this.gift_skus.options[this.gift_main_option_value];
                },
                getOptions : function(){
                    return this.skus.options[this.main_option_value];
                },
                numChange : function(){
                    if(this.customer_phone.length>this.phone_length)
                    {
                        this.customer_phone = this.customer_phone.slice(0, this.phone_length);
                    }
                },
                decQuantity : function(){
                    if(this.quantity > 1){
                        this.quantity--;
                    }else{
                        this.quantity = 1;
                    }
                    this.$options.methods.calcTotal();
                },
                incQuantity : function(){
                    if(this.quantity < 1){
                        this.quantity = 1;
                    }
                    this.quantity++;
                    this.$options.methods.calcTotal();
                },
                calcTotal : function (){
                    vm.total = vm.price * vm.quantity;
                    vm.total_show = vm.currency_code + ' ' + vm.total;
                },
                changeSku : function(key,value){
                    this.sku_options[key] = value;
                    this.$forceUpdate()
                },
                changeGiftSku : function(key,value){
                    this.gift_sku_options[key] = value;
                    this.$forceUpdate()
                },
                changeMainOption : function(key){
                    this.main_option_value = key;
                    this.sku_image = vm.getSkuImage();
                    this.options = vm.getOptions();
                    vm.genSkuOptions();
                },
                changeGiftMainOption : function(key){
                    this.gift_main_option_value = key;
                    this.gift_options = vm.getGiftOptions();
                    this.gift_sku_image = vm.getGiftSkuImage();
                    vm.genGiftSkuOptions();
                },
                beforeSend : function (){
                    if(!this.$options.methods.checkSend()) return;
                    axios.post('{{url('/order')}}', vm.$data)
                        .then(function (response) {
                            swal("Thank you for your purchase!", 'Your order has been created successfully.We’ll contact you to confirm it within 24H شكرا لكم. سنتصل بكم لتأكيد الطلبية خلال 24 ساعة.', 'success', {
                                confirmButton: true,
                            }).then(function(){
                                window.location.href = '{{\App\Extensions\Util::to('/category/' . $product->category_id)}}';
                            });
                        })
                        .catch(function (error) {
                            console.log(error);
                            let error_message = '';
                            for(let key in error.response.data.errors){
                                error_message += error.response.data.errors[key] + '\n';
                            }
                            swal("SomeThing Error", error_message, 'error', {
                                dangerMode: true,
                                confirmButton: true,
                            });
                        });
                },
                checkSend : function (){
                    let error_title = '';
                    let error_content = '';
                    let error_focus = '';
                    if(!vm.customer_name){
                        error_title = 'Full Name';
                        error_content = 'Full Name Required';
                        error_focus = '#userName';
                        vm.$options.methods.alertError(error_focus, error_title, error_content);
                        return false;
                    }
                    if(!vm.customer_phone){
                        error_title = 'Phone Number';
                        error_content = 'Phone Number Required';
                        error_focus = '#phoneNumber';
                        vm.$options.methods.alertError(error_focus, error_title, error_content);
                        return false;
                    }else if(vm.customer_phone.length != vm.phone_length){
                        error_title = 'Phone Length Error';
                        error_content = 'Phone Number should '+vm.phone_length+'-digit';
                        error_focus = '#phoneNumber';
                        vm.$options.methods.alertError(error_focus, error_title, error_content);
                        return false;
                    }
                    if(!vm.address){
                        error_title = 'Address';
                        error_content = 'Address Required';
                        error_focus = '#addressdetail';
                        vm.$options.methods.alertError(error_focus, error_title, error_content);
                        return false;
                    }
                    return true;
                },
                alertError : function (focus, error_title, error_content=''){
                    swal(error_title, error_content, 'error', {
                        dangerMode: true,
                        confirmButton: true,
                    }).then(function(){
                        $(focus).focus();
                    });
                },
                dealPrePhoneChange : function (){
                    this.phone_length = this.phone_length_map[this.pre_phone];
                }
            }
        });
        window.vm = vm;
        $('#phone_select').css("width","1.65rem").select2({
            minimumResultsForSearch: -1
        });
        $('#phone_select').on('change', function(){
            vm.pre_phone = $(this).select2('data')[0].id;
            vm.dealPrePhoneChange();
        })
        $('#country_select').css("width","4.63rem").select2();
        $('#country_select').on('change', function(){
            vm.country_code = $(this).select2('data')[0].id;
        })
        $("#country_select").val(['{{ $country }}']).trigger('change');
    });
    //调用
    var imgs = {!! json_encode($product->main_pictures) !!}
    var hrefs = ["#", "#2", "#3", "#4", '#5']
    var lbt = lbimg("divs2", imgs,hrefs,2000).start()
</script>
</html>