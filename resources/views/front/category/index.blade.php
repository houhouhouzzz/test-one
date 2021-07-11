<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{URL::asset('static/css/bootstrap.min.css')}}" rel="stylesheet">
        <style type="text/css">.product-grid {    font-family: 'Open Sans', sans-serif;            text-align: center;        }            .product-grid .product-image {                position: relative;                overflow: hidden;            }                .product-grid .product-image a.image {                    display: block;                }                .product-grid .product-image img {                    width: 100%;                    height: auto;                    transition: all 0.3s;                }                .product-grid .product-image:hover img {                    transform: scale(1.05);                }            .product-grid .product-new-label {                color: #fff;                background: #cd1b29;                font-size: 12px;                font-weight: 600;                text-transform: uppercase;                padding: 3px 10px 10px;                position: absolute;                top: 0px;                left: 0;                clip-path: polygon(0 0, 100% 0, 100% 75%, 15% 75%, 0 100%, 0% 25%);            }            .product-grid .social {                padding: 0;                margin: 0;                list-style: none;                opacity: 0;                transform: translateY(-50%);                position: absolute;                top: 50%;                left: -50px;                transition: all 0.3s ease;            }            .product-grid .product-image:hover .social {                opacity: 1;                left: 0;            }            .product-grid .social li {                margin: 5px 0;            }                .product-grid .social li a {                    color: #fff;                    background: #033772;                    font-size: 16px;                    line-height: 40px;                    width: 40px;                    height: 40px;                    display: block;                    position: relative;                    transition: all .3s ease;                }                    .product-grid .social li a:hover {                        background: #1f72ce;                    }                    .product-grid .social li a:before {                        content: attr(data-tip);                        color: #fff;                        background-color: #1f72ce;                        font-size: 13px;                        font-weight: 600;                        line-height: 22px;                        padding: 9px 12px;                        white-space: nowrap;                        visibility: hidden;                        position: absolute;                        left: 100%;                        top: 0;                        transition: all 0.3s ease;                    }                    .product-grid .social li a:hover:before {                        visibility: visible;                    }            .product-grid .product-content {                width: 100%;                padding: 12px 0;                display: inline-block;            }            .product-grid .title {                margin: 0 0 7px;                font-size: 16px;                font-weight: 600;                text-transform: capitalize;            }                .product-grid .title a {                    color: #000;                    transition: all 0.4s ease-out;                }                    .product-grid .title a:hover {                        color: #033772;                    }            .product-grid .price {                color: #000;                font-size: 16px;                font-weight: 600;                width: calc(100% - 100px);                margin: 0 0 10px;                display: inline-block;            }                .product-grid .price span {                    color: #7a7a7a;                    font-size: 15px;                    text-decoration: line-through;                    margin-right: 5px;                    display: inline-block;                }            .product-grid .rating {                padding: 0;                margin: 0;                list-style: none;                display: inline-block;                float: right;            }                .product-grid .rating li {                    color: #ffc500;                    font-size: 13px;                }                    .product-grid .rating li.far {                        color: #bababa;                    }            .product-grid .add-to-cart {                color: #000;                background: #fff;                font-size: 13px;                font-weight: 600;                text-align: left;                width: 75%;                margin: 0 auto;                border: 1px solid #033772;                display: block;                transition: all .3s ease;            }                .product-grid .add-to-cart:hover {                    color: #fff;                    background: #033772;                }                .product-grid .add-to-cart i {                    color: #fff;                    background-color: #033772;                    text-align: center;                    line-height: 35px;                    height: 35px;                    width: 35px;                    border-right: 1px solid #fff;                    display: inline-block;                }
            .product-grid .add-to-cart span {
                text-align: center;
                line-height: 35px;
                height: 35px;
                width:100%;
                padding: 0 6px;
                vertical-align: top;
                display: inline-block;
            }        @media only screen and (max-width:990px) {            .product-grid {                margin: 0 0 30px;            }        }</style>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-C59FXDPNF3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-C59FXDPNF3');
        </script>
    </head>
    <body><div class="demo">
        <div class="container">
            <div class="row">
                @foreach($products as $product)
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="product-grid">
                        <div class="product-image">
                            <a href="{{\App\Extensions\Util::jumpToProduct('/product/' . $product['id'])}}" class="image">
                                <img class="pic-1" src="{{$product->pictures?\App\Extensions\Util::to('/static/uploads/' . $product->pictures[0]) : ''}}">
                            </a>
                            <span class="product-new-label">new</span>
                        </div>
                        <div class="product-content">
                            <h3 class="title">
                                <a href="{{\App\Extensions\Util::jumpToProduct('/product/' . $product['id'])}}">{{ str_limit($product->title, 20,'...')  }}</a>
                            </h3>
                            <div class="price">{{\App\Extensions\Util::currencyFormat($product['price'], $price_info['currency_code'])['price']}}</div>
                            <ul class="rating">
                                <li class="fas fa-star"></li>
                                <li class="fas fa-star"></li>
                                <li class="fas fa-star"></li>
                                <li class="fas fa-star"></li>
                                <li class="far fa-star"></li>
                            </ul>
                            <a class="add-to-cart" href="{{\App\Extensions\Util::jumpToProduct('/product/' . $product['id'])}}">
{{--                                <i class="fas fa-shopping-cart"></i>--}}
                                <span>Order now</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </body>
</html>
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script language="javascript">
    window.onload = function(){
        var arr = new Array();
        $('.product-image img').each(function(i){
            arr[i] = $(this).outerHeight();
        });
        $('.product-image img').height(Math.max.apply(null,arr));
        console.log(Math.max.apply(null,arr));

        var arr = new Array();
        $('.product-content h3').each(function(i){
            arr[i] = $(this).outerHeight();
        });
        $('.product-content h3').height(Math.max.apply(null,arr));
    }
</script>
