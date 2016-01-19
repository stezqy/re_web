<?php
/**
 * Created by Steven
 * Date: 2016/1/17
 * Time: 10:20
 */
defined('BASEPATH') or exit('No direct script access allowed');
?>
<?php $this->load->view('header/header_login.php', array("username" => $this->user_model->get_username())); ?>
<div class="jumbotron masthead"  style="color:#b7b7b7;">
    <h2 style="text-align:center">Search What You Want</h2>
    <form  action="<?php echo site_url('search/search/1') ?>" method ="GET" role="form" style=" position: relative;top:50%;left:25%;" id="search_form">
         <div class="form-group" >
            <input type="text" name="title" class="form-control" id="title" placeholder="Title" style="width:700px;float:left;margin-right:10px;">
            <button id="submit" type="button" class="btn btn-default" >搜索</button>
            <button class="btn btn-large btn-primary" type="button" id="advance_search">高级搜索</button>
        </div>
        <input type="text" class="form-control" id="vendor" name="vendor" placeholder="路由器品牌" style="width:200px;display:none;float:left;margin-right:10px;">
        <input type="text" class="form-control" id="module"  name="module" placeholder="路由器型号" style="width:200px;display:none;float:left;margin-right:10px;";>
    </form>
</div>
<div style="width:680px;margin:auto;">
 <?php if(!empty($list)){ ?>
<table class="table">
   <thead>
      <tr>
         <th>品牌</th>
         <th>型号</th>
         <th>title</th>
      </tr>
   </thead>
   <tbody>
   <?php foreach($list as $v): ?>
        <tr>
         <td><?php echo $v['vendor'] ?></td>
         <td><?php echo $v['module'] ?></td>
         <td><a href="#"><?php echo $v['title'] ?></a></td>
        </tr>
    <?php endforeach?>
    <?php } ?>
    </tbody>
</table>
 </div>
<div>
<div style="width:300px;margin-left:580px;position:absolute;bottom:50px">
<?php if(!empty($current_page)){?>
<ul class="pagination">
    <?php $pre=$current_page-1;$next=$current_page+1;?>
   <li  <?php echo ($current_page>1) ?'class="active"':'class="disabled"';?>><a href= <?php echo ($current_page>1)? "/search/index/".$pre."?".http_build_query($input):"#";?>>&laquo;</a></li>
   <?php 
     for($p=$min_page;$p<=$max_page;$p++):
         $get_array = $input;
        $url="/search/index/".$p."?".http_build_query($get_array);?>
        <li <?php echo ($p==$current_page) ?'class="active"':'class="disabled"';?>> <a href="<?php echo $url;?>"><?php echo $p?></a></li>
    <?php endfor;?>
     <li  <?php echo ($current_page<$totalpage) ?'class="active"':'class="disabled"';?>><a href= <?php echo ($current_page<$totalpage)? "/search/index/".$next."?".http_build_query($input):"#";?>>&raquo;</a></li>
    </ul>
</div>
<?php }?>
<?$this->load->view('footer/footer.php');?>
<script type="text/javascript">

$(function(){
    $("#advance_search").click(function(){
    $("#vendor").show();
    $("#module").show();
    $("#advance_search").hide();
    });
});
$(function(){
    $("#submit").click(function(){
    var pw1=document.getElementById("title").value;
    var pw2=document.getElementById("vendor").value;
    var pw3=document.getElementById("module").value;
    if(pw1==""&&pw2==""&&pw3==""){
        return false;
    }
    else{
        window.location.href="http://localhost/search/index/1?title="+pw1+"&vendor="+pw2+"&module="+pw3; 
    }
    });
});
</script>

<!--<script type="text/javascript">
$(function(){
        //载入时隐藏下拉li
        $("#suggest_ul").hide(0);
        $("#advance_search").click(function(){
            $("#search_brand").show();
            $("#search_version").show();
            $("#advance_search").hide();
           
        });
});

//Ajax 动态获取关键字
$(function(){
                     
    //监听文本框输入变化
    $("#search_input").keyup(function(){
        //创建ajax对象函数
        function createLink(){
            if(window.ActiveXObject){
                var newRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }else{
                var newRequest = new XMLHttpRequest();
            }
            return newRequest;
        }
       
        //如果文本框为空，不发送请求
        if($("#search_input").val().length==0){
            $("#suggest_ul").hide(0);
            return;
        }
        //发送请求
        http_request = createLink();//创建一个ajax对象
        if(http_request){
            var sid = $("#search_input").val();
            var url = "search/search_hint";
            var data = "keywords="+sid;
            http_request.open("post",url,true);
            http_request.setRequestHeader("content-type","application/x-www-form-urlencoded");
           
            //指定一个函数来处理从服务器返回的结果
            http_request.onreadystatechange = dealresult; //此函数不要括号
            //发送请求
            http_request.send(data);
        }
       
        //处理返回结果
        function dealresult(){
        if(http_request.readyState==4){
            //等于200表示成功
            if(http_request.status==200){
                if(http_request.responseText=="no"){

                    $("#suggest_ul").hide(0);
                    return;
                   
                }
                $("#suggest_ul").show(0);
                var res = eval("("+http_request.responseText+")");
                //alert(http_request.responseText);
                var contents="";
                for(var i=0;i<res.length;i++){
                    var keywords = res[i].keywords;
                    contents=contents+"<li class='suggest_li"+(i+1)+"'>"+keywords+"</li>";
                       
                }
                
                $("#suggest_ul").html(contents);
                //$("#suggest_ul").empty();
            }
        }
    }
       
       
    });
   
   
    //鼠标
$(function(){
       
    //按下按键后300毫秒显示下拉提示
    $("#search_input").keyup(function(){
        setInterval(changehover,300);
        function changehover(){
            $("#suggest_ul li").hover(function(){ $(this).css("background","#eee");},function(){ $(this).css("background","#fff");});
            $("#suggest_ul li").click(function(){ $("#search_input").val($(this).html());});
            //$("#suggest_ul li").click(function(){ $("#search_form").submit();});
        }
    });
   
});

});
</script>-->
