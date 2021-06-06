<form action="/admin/products" id="form" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if(empty($product->id))
                        <h3 class="box-title">赠品创建</h3>
                    @else
                        <h3 class="box-title">赠品编辑</h3>
                    @endif
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="/admin/gifts" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                        </div>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="title" class="asterisk control-label">小标题</label>
                                    <div class="">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="title" v-model="title" name="title" value="" class="form-control title" placeholder="输入 主商品(货号)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="main_product_no" class="asterisk control-label">主商品(货号)</label>
                                    <div class="">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="main_product_no" v-model="main_product_no" name="main_product_no" value="" class="form-control title" placeholder="输入 主商品(货号)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="gift_product_no" class="asterisk control-label">赠品(货号)</label>
                                    <div class="">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="gift_product_no" v-model="gift_product_no" name="gift_product_no" value="" class="form-control title" placeholder="输入 赠品(货号)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class=" ">

                                        <label for="status" class="asterisk control-label">状态</label>
                                        <div class="">
                                            <select id="status" class="form-control" v-model="status">
                                                @foreach(\App\Model\Gift::$status_maps as $key => $value)
                                                    <option value="{{$key}}" >{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="hidden" name="_token" value="c7ayfHhCKk42yiULdV3CfBEXkXaYqsyiZAthDOLj">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <button type="button" @click="sendBefore" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ URL::asset('static/js/vue.min.js') }}"></script>
<script src="{{ URL::asset('static/js/axios.min.js') }}"></script>
<script type="application/javascript">
    jQuery(document).ready(function($) {
                @if(empty($gift->id))
        var data = {
            title: '',
            gift_product_no: '',
            main_product_no: '',
            status : 0,
            post: '{{url('admin/gifts')}}',
        }
                @else
        var data = {
            title: '{{$gift->title}}',
            gift_product_no: '{{$gift->main_product->product_no}}',
            main_product_no: '{{$gift->gift_product->product_no}}',
            status : {{$gift->status}},
            post: '{{url('admin/gifts/edit') . '/' . $gift->id}}',
        }
                @endif
        var vm = new Vue({
                el:'#form',
                data:data,
                methods:{
                    sendBefore : function (){
                        axios.post(data.post, data,{
                            headers:{'X-Requested-With' : 'XMLHttpRequest'}
                        })
                        .then(function (response) {
                            @if(empty($gift->id))
                                    var title = '赠品创建成功';
                            @else
                                    var title = '赠品保存成功';
                            @endif
                            swal("成功", title, 'success', {
                                dangerMode: true,
                                confirmButton: true,
                                timer:3000
                            }).then(function(){
                                window.location.href = "{{url('admin/gifts')}}";
                                {{--window.location.href = "{{url()->previous()}}";--}}
                            });
                        })
                        .catch(function (error) {
                            let error_message = '';
                            if(error.response.status == 406){
                                error_message += error.response.data.message;
                            }
                            for(let key in error.response.data.errors){
                                error_message += error.response.data.errors[key] + '\n';
                            }
                            swal(error_message, '', 'error', {
                                dangerMode: true,
                                confirmButton: true,
                            });
                        });
                    }
                }
            });
        window.vm = vm;
        $('#status').select2();
        $('#status').on('change', function(){
            vm.status = $(this).select2('data')[0].id;
        });
        @if( ! empty($gift->id))
            $("#status").val([{{ $gift->status }}]).trigger('change');
        @endif
    });

</script>
