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
            <img src="{{\App\Extensions\Util::to($product->top_picture)}}" alt="" style="width: 100%;" />
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

        <div class="imgContainer visible-img-detail" data-gtm-vis-first-on-screen-13511651_20="2044" data-gtm-vis-total-visible-time-13511651_20="100" data-gtm-vis-has-fired-13511651_20="1">
            <?php $i = 1; ?>
            @foreach($product->pictures as $picture)
            <div>
                <img src="{{\App\Extensions\Util::to($picture)}}" alt="" />
                <div class="imgText" style="display: none;"></div>
                <div class="imgText" style="display: none;"></div>
            </div>
                @if($i == $product->video_position && $product->video_link)
                        <div class="video" style="font-family: Helvetica;">
{{--                            <video src="{{$product->video_link}}" controls="controls">--}}
                                    {{--                   poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}

{{--                            </video>--}}
                        </div>
                @endif
                <?php $i++ ?>
            @endforeach
            @foreach($product->children as $child)
                <div>
                    <img src="{{\App\Extensions\Util::to($child['image'])}}" alt="" />
                    <div class="imgText" style="display: none;"></div>
                    <div class="imgText" style="display: none;"></div>
                </div>
                @if($i == $product->video_position && $product->video_link)
                    <div class="video" style="font-family: Helvetica;">
                        <video src="{{$product->video_link}}" controls="controls"
                                {{--                   poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
                        >
                        </video>
                    </div>
                @endif
                <?php $i++ ?>
            @endforeach
            @if($i<=$product->video_position && $product->video_link)
                <div class="video" style="font-family: Helvetica;">
                    <video src="{{$product->video_link}}" controls="controls"
                            {{--                   poster="{{$product->pictures?\App\Extensions\Util::to($product->pictures[0]) : ''}}"--}}
                    >
                    </video>
                </div>
            @endif
        </div>
        <div class="cover-bg" style="display: none;"></div>
        <div class="service-pachic">
            <div>
                <img src="{{\App\Extensions\Util::to('/static/image/car.png')}}" alt="" style="width: 0.58rem; height: 0.44rem; margin-bottom: 0.1rem; margin-top: 0.1rem;" />
                <div class="serviceblock" style="">
                    Cash on Delivery
                </div>
            </div>
            <div>
                <img src="{{App\Extensions\Util::to('/static/image/airplain.png')}}" alt="" style="width: 0.54rem; height: 0.54rem; margin-bottom: 0.05rem; margin-top: 0.05rem;" />
                <div class="serviceblock" style="">
                    Free Shipping
                </div>
            </div>
            <div>
                <img src="{{\App\Extensions\Util::to('/static/image/good.png')}}" alt="" style="width: 0.54rem; height: 0.54rem; margin-bottom: 0.1rem;" />
                <div class="serviceblock" style="">
                    Genuine Guarantee
                </div>
            </div>
        </div>
        <div class="Consumer-Services english visible-info-contactus" style="display: block;" data-gtm-vis-first-on-screen-13511651_21="202" data-gtm-vis-total-visible-time-13511651_21="100" data-gtm-vis-has-fired-13511651_21="1">
            <div id="accordion2" role="tablist" aria-multiselectable="true" class="panel-group">
                <div class="panel panel-default">
                    <div role="tab" class="panel-heading">
                        <div role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="panel-title collapsed" style="overflow: hidden;">
                            <div style="float: left; color: rgb(249, 62, 62);">
                                How to Order?
                            </div>
{{--                            <img src="https://sp.gcc-friday.com/static/singlepage/img/xiangxia.jpg" alt="" class="fold " />--}}
                        </div>
                    </div>
                    <div id="collapseTwo" role="tabpanel" aria-expanded="true" class="panel-collapse collapse in">
                        <div class="panel-body service-text">
                            <p><span>1.</span>Click the Shop Now button and Choose your color and size (if so)</p>
                            <p><span>2.</span>Fill in your name, phone number, delivery address</p>
                            <p><span>3.</span>Click the Confirm button</p>
                            <p><span>4.</span>Wait for us to contact you</p>
                            <p> Any inquiry please contact WhatsApp
                                <a href="https://api.whatsapp.com/send?phone={{\App\Model\WhatApp::getCurrentWhatApp()}}&amp;text=hi%2CI+want+to+ask+something+about+{{$product->product_no}}+{{$price_info['currency_code'] . ' ' . $product->price}}+&amp;source=&amp;data=" target="_blank">
                                    <span style="color: rgb(249, 62, 62); border-bottom: 0.01rem solid rgb(249, 62, 62);">{{\App\Model\WhatApp::getCurrentWhatApp()}}</span>
                                </a>
                                <br /> Expected delivery time: about 10-20 days. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="TermsContainer">
            <div>
                Shipment
            </div>
            <div>
                Returns
            </div>
            <div>
                About Us
            </div>
            <div>
                Privacy
            </div>
            <div>
                Contact
            </div>
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
                <div class="select-option" style="max-height: 540px; overflow-y: scroll;">
                    <div class="allOption">
                        Product details &nbsp;&nbsp; (Please select)
                    </div>
                    <div class="option-btn-area" style="">
                        <div class="option-btn" v-for="(sku, index) in skus" v-bind:class="{optionActive : sku_index == index}" @click="changeSku(index)" v-text="sku.options" style="float: left; margin-right: 0.3rem; position: relative;">
                        </div>
                    </div>
                    <div class="quantity" style="overflow: hidden;">
                        <div class="quantity-title english" style="display: block;">
                            Quantity
                        </div>
                        <div class="ar_quantity" style="overflow: hidden; float: left;">
                            <div class="quantity-sub" @click="decQuantity">
                                -
                            </div>
                            <div class="quantity-number" v-model="quantity" v-text="quantity">

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
                        </div>State
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
                                <div class="tellabel" v-text="pre_phone">
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
                                          maxlength="350"
                                          placeholder="Detail Street, building , etc  الرجاء كتابة العنوان كاملاً (اسم المدينة + اسم الحي  + اسم الشارع + رقم المنزل + أقرب علامة مميزة للمنزل اذا وجد ) اذا لم يتم كتابة العنوان كامل سيتم تجاهل الطلبية شكرا لتعاونكم">
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
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script src="/static/js/vue.min.js" type="text/javascript"></script>
<script src="/static/js/select2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function ($) {
        var vm = new Vue({
            el: '#option',
            data: {
                skus : {!! json_encode($product->front_children) !!},
                total : {{$product->price}},
                currency_code : '{{$price_info['currency_code']}}',
                total_show : '{{$price_info['currency_code'] . ' ' . $product->price}}',
                price : {{$product->price}},
                quantity : 1,
                sku_index : 0,
                sku_id : {{data_get( $product->front_children, '0.id', 0)}},
                sku : '{{data_get( $product->front_children, '0.sku', '')}}',
                sku_image : '{{data_get( $product->front_children, '0.image', '')}}',
                country_code : '{{$country}}',
                customer_name : '',
                customer_phone : '',
                whats_app : '',
                pre_phone : '{{$price_info['pre_phone']}}',
                address : '',
                phone_length : {{$price_info['phone_length']}},
                price_infos : {!! json_encode(\App\Model\Product::PRICE_COLUMNS) !!},
                address_note : '',
            },
            methods:{
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
                changeSku : function(index){
                    this.sku_index = index;
                    this.sku_id = this.skus[index]['id'];
                    this.sku_image = this.skus[index]['image'];
                },
                beforeSend : function (){
                    if(!this.$options.methods.checkSend()) return;
                    axios.post('{{url('/order')}}', vm.$data)
                        .then(function (response) {
                            swal("Thank you for your purchase!", 'Your order has been created successfully.', 'success', {
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
                }
            }
        });
        window.vm = vm;
        $('#country_select').css("width","4.63rem").select2();
        $('#country_select').on('change', function(){
            vm.country_code = $(this).select2('data')[0].id;
        })
        $("#country_select").val(['{{ $country }}']).trigger('change');

    });

</script>
</html>