

<html>
  <head>
    <title>毕业照片展示</title>
    <meta charset = "utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <style>

      .entry{
        margin:30px;
        padding:20px;
        background-color: pink;
      }
      .entry time{
        color:grey;
      }
      .entry p{

      }
      .pic{
        text-align: center;
      }

    </style>

  </head>

  <body>
    <div style="text-align: center">
      <div style="display:inline-block; border-bottom: 5px solid #C34644; padding-left:10px; padding-right: 10px; padding-bottom: 5px; margin-top: 30px; font-size: 20px; font-weight: bold">
        寻找最美毕业照
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-2"></div>
      <div class = "col-md-6 col-sm-8" id = "mainContent"></div>
      <div class="col-md-3 col-sm-2"></div>
    </div>
    <div style="text-align: center; margin-bottom:40px">
      <button class="btn btn-warning" id = "more_btn" style="width:50%;" onclick = "getSource(stamp)">点击加载更多</button>
    </div>



  <script>
    var stamp = Math.floor(new Date().getTime()/1000)
    //getTime返回的是毫秒，php数据库里存的是秒数，所以差10^3
    // stamp = 149650000 + 500;
    var insertTarget = document.getElementById("mainContent");

    var getSource = function (stamp) {
      var request = new XMLHttpRequest();
      request.onreadystatechange = function (){
        if (request.readyState == 4 && request.status == 200){
          if(request.responseText != "[false]"){
            var resp = JSON.parse(request.responseText);
            renderSource(resp);
          }
          else{
            //response表示已经没有图片了
            console.log("no more");
            renderFaliure();
            //提示没有更多图片了
          }
        }
      }
      request.open('GET','ajaxRetrive.php?lastStamp='+stamp);//=最后一个stamp
      request.send();
    }

    var renderSource = function (resp){
      console.log("rendering source:");
      // console.log(resp);
      var i = 0;
      for(i = 0; resp[i];i++){
        //console.log(resp[i]);
        insertTarget.innerHTML += constructView(resp[i]);
      }
      stamp = resp[i-1].TIME_COMMIT;
    }

    var renderFaliure = function () {
      //pass for now
      var more = document.getElementById("more_btn");
      more.innerText = "没有更多了";
      more.classList.add("disabled");

    }

    var constructView = function (entry){

      var commitDate = new Date(entry.TIME_COMMIT * 1000).toLocaleString();
      var piclist = entry.PIC_LOC.split(',');
      var view = "<div class='entry'><time>"+commitDate+"</time><br /><p>"+entry.COMMENT+"</p><div class = 'pic'>";
      for(var i = 0;piclist[i];i++){
        // view += "<p>" +piclist[i]+"</p><br />";
        //这里测试用的替换图片
        var replacement = "https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1229547004,1199991462&fm=117&gp=0.jpg";
        view += "<img src = '" + replacement + "'/>"
      }
      view += "</div></div>";

      return view;
    }
    //刚进入的时候第一次载入
    // getSource(stamp);

    var getpage = function (page) {
      var request = new XMLHttpRequest();
      request.onreadystatechange = function (){
        if (request.readyState == 4 && request.status == 200){
          if(request.responseText != "[false]"){
            var resp = JSON.parse(request.responseText);
            console.log(resp);
            // renderSource(resp);
          }
          else{
            //response表示已经没有图片了
            console.log("no more");
            // renderFaliure();
            //提示没有更多图片了
          }
        }
      }
      request.open('GET','ajaxRetrive.php?lastStamp='+page);//=最后一个stamp
      request.send();
    }




  </script>
  </body>


</html>
