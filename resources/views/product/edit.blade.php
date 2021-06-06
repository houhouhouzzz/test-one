<link rel="stylesheet" href="{{URL::asset('static/js/upload/jquery.fileupload.css')}}" >
<link rel="stylesheet" href="{{URL::asset('static/css/upload.css')}}" >

<form action="/admin/products" id="form" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if(empty($product->id))
                        <h3 class="box-title">商品创建</h3>
                    @else
                        <h3 class="box-title">商品编辑</h3>
                    @endif
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="/admin/products" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
                        </div>
                        <a class="btn btn-sm btn-info" target="_blank" href="/admin/categories/create">创建分类</a>
                        <a class="btn btn-sm btn-primary" target="_blank" href="/admin/options/create">创建属性</a>
                        <a class="btn btn-sm btn-danger" target="_blank" href="https://www.hsbianma.com">海关编码查询</a>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class=" ">
                                        <label for="product_no" class="asterisk control-label">货号</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" id="product_no" v-model="product_no" name="product_no" value="" class="form-control title" placeholder="输入 标题">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class=" ">
                                        <label for="title" class="asterisk control-label">标题</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" id="title" v-model="title" name="title" value="" class="form-control title" placeholder="输入 标题">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class=" ">

                                        <label for="category_id" class="asterisk control-label">分类</label>
                                        <div class="">
                                            <select id="category_id" class="form-control" v-model="category_id">
                                                <option value="" disabled selected>请选择分类</option>
                                                @foreach(\App\Model\Category::all()->toArray() as $category)
                                                    <option value="{{$category['id']}}" >{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class=" ">
                                        <label for="is_ele" class=" control-label">是否带电</label>
                                        <div class="">
                                            <span class="icheck">
                                                <label class="radio-inline">
                                                    <div class="iradio_minimal-blue" v-bind:class="[is_ele==0?'checked':'']"  aria-checked="false" aria-disabled="false" style="position: relative;">
                                                        <input type="radio" name="is_ele" @click="eleChange" value="0" v-model="is_ele" class="minimal" style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                                        </ins>
                                                    </div>&nbsp;不带电&nbsp;&nbsp;
                                                </label>
                                            </span>
                                            <span class="icheck">
                                                <label class="radio-inline">
                                                    <div class="iradio_minimal-blue" v-bind:class="[is_ele==1?'checked':'']" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                        <input type="radio" name="is_ele" @click="eleChange" value="1" v-model="is_ele" class="minimal" style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                                                        </ins>
                                                    </div>&nbsp;带电&nbsp;&nbsp;
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class=" ">
                                        <label for="ocean_number" class=" control-label">海关编码</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" id="ocean_number" v-model="ocean_number" name="ocean_number" value="" class="form-control ocean_number" placeholder="输入 海关编码">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class=" ">
                                        <label for="description" class=" control-label">产品英文描述</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" id="description" v-model="description" name="description" value="" class="form-control description" placeholder="输入 产品英文描述">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="sa_price" class=" control-label">产品价格 SA</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="sa_price" name="sa_price" value="" class="form-control sa_price" placeholder="请输入 SA 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="ae_price" class=" control-label">产品价格 AE</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="ae_price" name="ae_price" value="" class="form-control ae_price" placeholder="请输入 AE 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="qa_price" class=" control-label">产品价格 QA</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="qa_price" name="qa_price" value="" class="form-control qa_price" placeholder="请输入 QA 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="kw_price" class=" control-label">产品价格 KW</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="kw_price" name="kw_price" value="" class="form-control kw_price" placeholder="请输入 KW 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="bh_price" class=" control-label">产品价格 BH</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="bh_price" name="bh_price" value="" class="form-control bh_price" placeholder="请输入 BH 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="om_price" class=" control-label">产品价格 OM</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="om_price" name="om_price" value="" class="form-control om_price" placeholder="请输入 OM 价格">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class=" ">
                                        <label for="cost" class="asterisk control-label">进货底价</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="cost" name="cost" value="" class="form-control cost" placeholder="请输入 进货底价">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class=" ">
                                        <label for="weight" class="asterisk control-label">重量(单位kg)</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input style="width: 130px; text-align: right;" type="text" v-model="weight" name="weight" value="" class="form-control weight" placeholder="输入 重量(单位kg)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class=" ">
                                        <label for="supplier_link" class=" control-label">供应商链接</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" v-model="supplier_link" name="supplier[link]" value="" class="form-control supplier_link_" placeholder="输入 供应商链接">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class=" ">
                                        <label for="supplier_note" class=" control-label">采购备注</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" v-model="supplier_note" name="supplier[note]" value="" class="form-control supplier_note_" placeholder="输入 采购备注">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" ">
                                        <label for="supplier_note" class=" control-label">视频位置(第几张图片后)</label>
                                        <select id="video_position" class="form-control" v-model="video_position">
                                            @foreach(\App\Model\Product::video_position_map() as $position)
                                                <option value="{{$position}}" >{{$position}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class=" ">
                                        <label for="supplier_note" class=" control-label">视频链接</label>
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                                <input type="text" v-model="video_link" value="" class="form-control supplier_note_" placeholder="输入 视频链接">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class=" ">
                                        <label for="options" class="asterisk control-label">主属性选择</label>
                                        <div class="">
                                            <select id="main_option" data-placeholder="请选择属性" class="form-control">
                                                <option value="0" >请选择</option>
                                                @foreach(\App\Model\Option::all()->toArray() as $option)
                                                <option value="{{$option['id']}}" >{{$option['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class=" ">
                                        <label for="options" class="asterisk control-label">副属性选择</label>
                                        <div class="">
                                            <select id="options" multiple data-placeholder="请选择属性" class="form-control">
                                                @foreach(\App\Model\Option::all()->toArray() as $option)
                                                    <option value="{{$option['id']}}" >{{$option['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label for="supplier_note" class=" control-label">主图</label>
                                        <div class="">
                                            <div class="input-group">
                                                <ul style="padding: 0px" id="referencesUl">
                                                    <li class="fileinput-button fileupload-thumb">
                                                        <i class="glyphicon glyphicon-camera" aria-hidden="true"></i> 添加图片
                                                        <input type="file" name="file" accept="image/*" multiple="">
                                                    </li>
                                                    <li v-show="pictures.length>0" class="fileupload-img-list" v-for="(pic_child, pic_index) in pictures" >
                                                        <div class="box-overlay">
                                                        <span>
                                                            <a href="javascript:void(0);" @click="removeImage(pic_index)" v-bind:index="pic_index">移除</a>
                                                        </span>
                                                        </div>
                                                        <input type="hidden" :name="'pictures['+pic_child+']'" :value="pic_child">
                                                        <img width="100" :src="'/'+pic_child" onerror="this.style.display='none'">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label for="supplier_note" class=" control-label">sku详情</label>
                                        <button type="button" style="float:right" class="btn btn-info"  @click="addSku"> 添加sku </button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-condensed">
                                        <thead>
                                        <tr>
                                            <th> 操作 </th>
                                            <th v-for="(val, index) in child_header" v-text="val"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(child, index) in skus">
                                            <td >
                                                <button type="button" class="btn btn-sm btn-info" @click="delSku(index)" >删除该行</button>
                                                <br>
                                                <button type="button" class="btn btn-sm btn-warning" @click="delSkuImage(index)" >删除图片</button>
                                            </td>
                                            <td>
                                                <ul style="padding: 0px" id="sku-image" v-if="!child.image">
                                                    <li class="fileinput-button fileupload-thumb">
                                                        <i class="glyphicon glyphicon-camera" aria-hidden="true"></i> 添加图片
                                                        <input type="file" accept="image/*" :index="index" name="file" @change="pictureUpload">
                                                    </li>
                                                </ul>
                                                <img width="100" :src="child.image" v-if="child.image">
                                            </td>
                                            <td v-for="(main_option_child, main_option_index) in child.main_option" v-if="main_option_child">
                                                <input class="form-control" @input="changeIn()" type="text" v-model="main_option_child.option_value" >
                                            </td>
                                            <td v-for="(option_child, option_index) in child.options" v-if="option_index && option_child">
                                                <input class="form-control" @input="changeIn()" type="text" v-model="option_child.option_value" >
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
                            {{--<label class="pull-right" style="margin: 5px 10px 0 0;">--}}
                                {{--<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" class="after-submit" name="after-save" value="1" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> 继续编辑--}}
                            {{--</label>--}}
                            {{--<label class="pull-right" style="margin: 5px 10px 0 0;">--}}
                                {{--<div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" class="after-submit" name="after-save" value="2" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> 继续创建--}}
                            {{--</label>--}}
                        </div>
                    </div>
                    <input type="hidden" name="_previous_" value="http://one-page-dev.com/admin/products" class="_previous_">
                    <!-- /.box-footer -->
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ URL::asset('static/js/upload/jquery.ui.widget.js') }}"></script>
<script src="{{ URL::asset('static/js/upload/jquery.fileupload.js') }}"></script>
<script src="{{ URL::asset('static/js/vue.min.js') }}"></script>
<script src="{{ URL::asset('static/js/axios.min.js') }}"></script>
<script type="application/javascript">
    jQuery(document).ready(function($) {
                @if(empty($product->id))
        var data = {
                title: '',
                product_no: '',
                category_id: '',
                is_ele: 0,
                ocean_number: '',
                description: '',
                sa_price: '',
                ae_price: '',
                qa_price: '',
                kw_price: '',
                bh_price: '',
                om_price: '',
            video_link: '',
            video_position: '5',
            supplier_note: '',
            supplier_link: '',
            main_option : '',
            main_option_map:[],
                options: [],
                option_map:[],
                cost: '',
                pictures: [],
                weight: '',
                post: '{{url('admin/products')}}',
                child_header : ['图片'],
                skus : [],
                sku : {
                    'image' : '',
                    'main_option' : [],
                    'options' : []
                }
            }
                @else
        var data = {
                title: '{{$product->title}}',
                product_no: '{{$product->product_no}}',
                category_id: '{{$product->category_id}}',
                is_ele: '1',
                ocean_number: '{{$product->ocean_number}}',
                description: '{{$product->description}}',
                sa_price: '{{$product->sa_price}}',
                ae_price: '{{$product->ae_price}}',
                qa_price: '{{$product->qa_price}}',
                kw_price: '{{$product->kw_price}}',
                bh_price: '{{$product->bh_price}}',
                om_price: '{{$product->om_price}}',
            video_link: '{{$product->video_link}}',
            video_position: '{{$product->video_position}}',
                supplier_link: '{{$product->supplier?$product->supplier->link:""}}',
                supplier_note: '{{$product->supplier?$product->supplier->note:""}}',
            main_option: {{$product->main_option}},
            main_option_map:[],
                options: {{json_encode( $product->options->map->id->toArray())}},
                option_map:[],
                cost: '{{$product->cost}}',
                pictures: {!! json_encode( $product->pictures)!!},
                weight: '{{$product->weight}}',
                post: '{{url('admin/products/edit') . '/' . $product->id}}',
                child_header : ['图片'],
                skus : {!! json_encode($product->children) !!},
                sku : {
                    'image' : '',
                    'main_option' : [],
                    'options' : [],
                    'sku' : ''
                }
            }
                @endif
        var vm = new Vue({
                el:'#form',
                data:data,
                methods:{
                    removeImage : function(index){
                        vm.pictures.splice(index,1);
                    },
                    delSku : function(index){
                        vm.skus.splice(index, 1);
                    },
                    delSkuImage : function(index){
                        vm.skus[index]['image']='';
                    },
                    pictureUpload : function(e){
                        // let formData = formdata=new FormData( );
                        // formdata.append("file" , $(e.target())[0].files[0]);

                        var forms = new FormData()
                        forms.append('file', $(e.target)[0].files[0]);
                        axios.post('{{url('/admin/api/upload')}}', forms,{
                            headers:{
                                'X-Requested-With' : 'XMLHttpRequest',
                                'Content-Type':'multipart/form-data'
                            }
                        })
                            .then(function (response) {
                                let index = $(e.target).attr('index');
                                vm.skus[index]['image'] = '/' + response.data.path;
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
                    },
                    changeIn : function(){
                        console.log(this.skus);
                    },
                    addSku : function(){
                        this.skus.push(this.sku);
                        dealSkus();
                        return false;
                    },
                    eleChange : function(event){
                        this.is_ele  = event.target.value;
                    },
                    depChange : function(){
                        if( ! this.department_id )return [];
                        let _this = this;
                        axios.get('{{url('/site/getGroupLeaderMap')}}/' + this.department_id,{
                            headers:{'X-Requested-With' : 'XMLHttpRequest'}
                        })
                            .then(function(response){
                                _this.lead_map =response.data;
                                if(_this.leader_id){
                                    $("#leader_id").val([_this.leader_id]).trigger('change');
                                }
                            }).catch(function (error) {
                            let error_message = '';
                            for(let key in error.response.data.errors){
                                error_message += error.response.data.errors[key] + '\n';
                            }
                            swal(error_message, '', 'error', {
                                dangerMode: true,
                                confirmButton: true,
                            });
                        });
                    },
                    sendBefore : function (){
                        axios.post(data.post, data,{
                            headers:{'X-Requested-With' : 'XMLHttpRequest'}
                        })
                            .then(function (response) {
                                @if(empty($product->id))
                                        var title = '商品创建成功';
                                @else
                                        var title = '商品保存成功';
                                @endif
                                swal("成功", title, 'success', {
                                    dangerMode: true,
                                    confirmButton: true,
                                    timer:3000
                                }).then(function(){
                                    {{--window.location.href = "{{url('admin/products')}}";--}}
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
        $('#category_id').select2();
        $('#options').select2();
        $('#main_option').select2();
        $('#video_position').select2();
        $('#category_id').on('change', function(){
            vm.category_id = $(this).select2('data')[0].id;
        })
        $('#main_option').on('change', function(){
            vm.main_option = dealSelectMethod($('#main_option').select2('data'))[0];
            vm.main_option_map = dealSelectMap($('#main_option').select2('data'));
            dealSkus();
        })
        $('#options').on('change', function(){
            {{--$("#main_option").val([{{ $product->main_option }}]).trigger('change');--}}
            if($('#options').select2('data').length > 3){
                swal('最多选择3个副属性', '', 'error', {
                    dangerMode: true,
                    confirmButton: true,
                }).then(function(){
                    $("#options").val(vm.options).trigger('change');
                });
                return false;
            }
            vm.options = dealSelectMethod($('#options').select2('data'));
            vm.option_map = dealSelectMap($('#options').select2('data'));
            dealSkus();
        })
        $('#video_position').on('change', function(){
            vm.video_position = $(this).select2('data')[0].id;
        })

        function dealSkus(){
            vm.child_header = ['图片'];
            vm.sku = {image:'', 'main_option':[], 'options':[]};
            let sku_options = [];
            vm.main_option_map.forEach((value,key)=>{
                vm.child_header.push(value + '(主属性)');
                vm.sku.main_option[0] = {
                    option_id : key,
                    option_name : value,
                    option_value : vm.sku.main_option[0]?vm.sku.main_option[0]['option_value']:''
                };
                vm.skus.forEach((sku, sku_index)=>{
                    let main = [{
                        option_id: key,
                        option_name: value,
                        option_value: sku.main_option?sku.main_option[0]['option_value']:'',
                    }];
                    vm.skus[sku_index].main_option = main;
                })
            });
            vm.option_map.forEach((value, key)=>{
                if(value instanceof Array){
                    return true
                }
                if(vm.child_header.indexOf(value) == -1){
                    vm.child_header.push(value+'(多选项用,隔开)');
                }
                if(sku_options[value] == undefined){
                    sku_options[key] = {
                        option_id : key,
                        option_name : value,
                        option_value : ''
                    };
                }
                vm.skus.forEach((sku, sku_index)=>{
                    if(sku['options'][key] == undefined){
                        vm.skus[sku_index]['options'][key] = {
                            option_id : key,
                            option_name : value,
                            option_value : ''
                        };
                    }
                })
            });
            vm.skus.forEach((sku, sku_index)=>{
                for(let option_key  in vm.skus[sku_index]['options']){
                    if(option_key>0){
                        if(vm.options.indexOf(option_key) == -1){
                            delete vm.skus[sku_index]['options'][option_key];
                        }
                    }
                }

            })
            vm.sku['options'] = sku_options;
        }

        function dealSelectMethod(data){
            let ids = [];
            data.forEach(function (item ,index) {
                ids.push(item.id);
            });
            return ids;
        }
        function dealSelectMap(data){
            let options = [];
            data.forEach(function (item ,index) {
                options[item.id] = item.text;
            });
            return options;
        }
        @if( ! empty($product->id))
        $("#options").val({{json_encode($product->options->map->id->toArray())}}).trigger('change');
        $("#main_option").val([{{ $product->main_option }}]).trigger('change');
        $("#category_id").val([{{ $product->category_id }}]).trigger('change');
        @endif

        var image_lists = {
                'loading': undefined,
        };

        // 上传图片
        $('#referencesUl .fileinput-button').fileupload({
            dataType : 'json'
            ,autoUpload : true //自动上传
            ,maxNumberOfFiles : 2
            ,acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            ,url : '/admin/api/upload'
            ,previewMaxWidth: 600
            ,dropZone : false
            ,previewMaxHeight : 0
            ,imageCrop : true
            ,formData : {}
            ,done: function (e,data) {
                if(vm.pictures.length > 15){
                    swal('图片最多上传15张', '', 'error', {
                        dangerMode: true,
                        confirmButton: true,
                    });
                    return false;
                }
                let url = data.result.path;
                vm.pictures.push(url);
            }
        });

        // 上传图片
        $('#sku-image .fileinput-button').fileupload({
            dataType : 'json'
            ,autoUpload : true //自动上传
            ,maxNumberOfFiles : 2
            ,acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            ,url : '/admin/api/upload'
            ,previewMaxWidth: 600
            ,dropZone : false
            ,previewMaxHeight : 0
            ,imageCrop : true
            ,formData : {}
            ,done: function (e,data) {
                let thumb = window.location.origin + '/' + data.result.path;
                let url = data.result.path;
                $('#referencesUl').append($('#reference-gallery-template').html().replaceMulti({
                    '{thumb}': thumb,
                    '{url}': url,
                    '{key}': image_lists.gallery_len
                }));
                image_lists.gallery_len++;
                vm.gallery_len ++;
                vm.pictures.push(url);
            }
        });
    });
</script>
{{--<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>--}}
