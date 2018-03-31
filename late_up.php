<?php
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

session_start();
require_once('interface.php');
define('SID',getAccount());
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=8">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Cache-control" content="no-cache">
  <meta http-equiv="Cache" content="no-cache">
  <title>寻找最美毕业照</title>
  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>

<body>
<div style="text-align: center"><div style="display:inline-block; border-bottom: 5px solid #C34644; padding-left:10px; padding-right: 10px; padding-bottom: 5px; margin-top: 30px; font-size: 20px; font-weight: bold">寻找最美毕业照</div></div>
<?php
$requireZcode = 1 - isGraduateThisYear();
if(getAccount() == null){
  echo
  "<script>
    alert('请先登录!');
    window.location.href = 'http://system.lib.whu.edu.cn/dsjjs/byj/oauth.php';
  </script>";
}
?>

<div style="padding: 20px; margin: 30px 20px; border: 1px dashed #AAA; border-radius: 8px; font-size: 14px; color: #555;">
<p style="font-weight: bold">活动规则</p>
1. 凡是通过“情书”提交毕业照的同学/校友，都将收到一份图书馆赠予的定制礼物！数量有限，送完为止；<br/>
2. 优秀毕业照将通过图书馆官方微博、微信和馆内电子屏进行展示，并打印出来贴在毕业季大型喷绘上展示；<br/>
3. 参赛作品不得打logo水印或其它作者信息；<br/>
4. 每位参与者提交照片最多不超过5幅。
</div>

<form name="myForm" role="form" enctype="multipart/form-data" onsubmit="submission();return false;" style="margin: 0px 20px; margin-bottom: 30px">
    <div class="form-group">
      <label for="comment">感言</label><textarea class="form-control" id="comment" name="comment" rows=6 placeholder="请在这里输入你的感言。"></textarea>
    </div>
    <div class="form-group">
      <input type="file" name="img" accept="image/*" onchange="upload()" style="display:none;">
      <button type="button" class="btn btn-primary" onclick="document.myForm.img.click();">上传照片</button>
      <div id="preview"></div>
    </div>
    <div class="form-group">
      <label for="telephone">电话号码</label><input class="form-control" type="text" id="telephone" name="telephone" />
    </div>
<?php if($requireZcode): ?>
    <div class="form-group">
      <label for="zcode">邮政编码</label><input class="form-control" type='text' name='zcode' id="zcode">
    </div>
    <div class="form-group">
      <label for='addr'>邮寄地址</label><input class="form-control" type='text' name='addr' id='addr'/>
    </div>
<?php endif; ?>
    <div style="text-align: center;">
      <button type="submit" class="btn btn-success" id="btn" style="width:50%;">保 存</button>
    </div>
</form>



<script>
var imgCount = 0;
//限制最大数量
var imgRef = 0;
//跟踪显示的标签

var debug = 1;

var allowedFormat = 'image';
var maximumSize = 8000000;

function upload() {
    var img = document.myForm.img.files[0];
    document.myForm.img.value = "";

    if ((img.type.split('/')[0] != allowedFormat)||
        (img.size > maximumSize)){
          //超出
          alert('图片过大或图片格式不正确，请重新选择。');
          document.myForm.img.value = "";
      }
    else {
      var fm = new FormData();
      fm.append('img', img);
      var request = new XMLHttpRequest();
      request.onreadystatechange = function (){
        if (request.readyState == 4 && request.status == 200){
          console.log(request.responseText);
          addPreView(request.responseText);
        }
      }
      request.open('POST', 'ajaxImg.php?sid='+<?= "'".SID."'" ?>);
      request.send(fm);
    }

}

function addPreView(src){
  var img =
  '<div style="display:inline-block; margin-right: 80px; margin-bottom: 8px; margin-top: 7px"><img style="width: 200px; padding-right: 5px; padding-bottom: 5px" src="' + src + '" class="prepImg" id="' + imgRef + '"/><button type="button" class="btn btn-danger" onclick="deleteImg(\'' + src + '\',\'' + imgRef + '\')" style="font-size:12px">删除</button></div>';

  document.getElementById("preview").innerHTML += img;

  imgCount += 1;
  imgRef += 1;
}

function deleteImg(src,id){

  var cur = document.getElementById(id).parentNode;
  var par = cur.parentNode;
  par.removeChild(cur);
  imgCount -= 1;

  var request = new XMLHttpRequest();
  request.onreadystatechange = function (){
    if (request.readyState == 4 && request.status == 200){
      console.log(request.responseText);
      //addPreView(request.responseText);
    }
  }
  request.open('GET', 'ajaxDEL.php?sid='+<?= "'".SID."'" ?>+'&src='+src);
  request.send();
}

function submission(){
  //文字提交
  if (imgCount == 0){
    alert('请至少上传一张图片。');
  }
  else if(document.myForm.comment.value.trim() == ""){
    //没有留言
    alert('请输入留言');
  }
  else if(document.myForm.telephone.value.trim() == ""){
    //没有电话
    alert('请输入联系方式');
  }

  <?php if($requireZcode): ?>
    else if(document.myForm.zcode.value.trim() == ''){
      //没有邮编
      alert('请输人您的邮编');
    }
    else if(document.myForm.addr.value.trim() == ''){
      //没有地址
      alert('请输入您的地址');
    }
  <?php endif; ?>

  else{
    //格式正确,开始上传了
    var comment = document.myForm.comment.value;
    var telephone = document.myForm.telephone.value;
    var fm = new FormData();
    fm.append('comment', comment);
    fm.append('telephone',telephone);

    <?php
      if($requireZcode){
        echo
        "var zcode = document.myForm.zcode.value;
        var addr = document.myForm.addr.value;
        fm.append('zcode',zcode);
        fm.append('addr',addr);";
      }
     ?>

     console.log(fm);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function (){
      if (request.readyState == 4 && request.status == 200){
        console.log(request.responseText);
        //addPreView(request.responseText);
        alert('提交成功！');
      }
    }
    request.open('POST', 'ajaxText.php?requirezcode=<?php echo $requireZcode;?>&sid='+<?= "'".SID."'" ?>);
    request.send(fm);
  }

}

function initLoad(){
  var request = new XMLHttpRequest();
  request.onreadystatechange = function (){
    if (request.readyState == 4 && request.status == 200){
      //addPreView(request.responseText);
      //initRender(request.responseText);
      if(request.responseText){
        var resp = JSON.parse(request.responseText);
        initRender(resp);
      }
      else{
        //新用户
        //不操作
      }

    }
  }
  request.open('GET','ajaxInit.php?requirezcode=<?php echo $requireZcode;?>&sid='+<?= "'".SID."'" ?>);
  request.send();

}

function initRender(resp){
  var requirez = <?php echo $requireZcode;?>;

  document.myForm.comment.value = resp.COMMENT;
  document.myForm.telephone.value = resp.TELE;
  if(requirez){
    document.myForm.zcode.value = resp.ZCODE;
    document.myForm.addr.value = resp.ADDR;
  }

  var piclist = resp.PIC_LOC.split(',');
  console.log(piclist);
  for (var i = 0;i < piclist.length;i++){
    if(piclist[i]){
      addPreView(piclist[i]);
    }
  }
}

initLoad();
//载入网页时执行

</script>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
