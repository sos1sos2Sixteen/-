

<html>
  <head>
    <title>毕业照片墙</title>
    <meta charset = "utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.css" rel="stylesheet">
    <!-- 用了cdn的4.0版本里的的卡片 -->

    <style>

      .card-img-bottom{
        height: auto;
        width: 100%;
      }
      .card{
        margin: 20px 20px;
        background: #f5f5f5;
      }

    </style>

  </head>

  <body>
    <div style="text-align: center">
      <div style="display:inline-block; border-bottom: 5px solid #C34644; padding-left:10px; padding-right: 10px; padding-bottom: 5px; margin-top: 30px; margin-bottom:30px; font-size: 20px; font-weight: bold">
        毕业照片墙
      </div>
      <div style="text-align: center;">
        <button
        onclick = "window.location = 'http://system.lib.whu.edu.cn/dsjjs/byj/oauth.php'"
        class="btn btn-warning  "
        style="width:50%;">
          打开情书上传毕业照
      </button>
      </div>
    </div>
    <div class="row" style="margin-top:20px">
      <div class="col-md-3 col-sm-2"></div>
      <div class = "col-md-6 col-sm-8" id = "mainContent"></div>
      <div class="col-md-3 col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-2"></div>
      <div class = "col-md-6 col-sm-8">

        <div class="container" style="text-align:center;margin-bottom:100px">
          <nav>
            <ul class="pagination justify-content-center">
              <li class="page-item disabled" onclick = "handlePrev(this)" id = "prevBtn">
                <a class="page-link" href="#">上一页</a>
              </li>
              <li class="page-item page_numeral active" onclick = "handleNum(this)"><a class="page-link" href="#">1</a></li>
              <li class="page-item page_numeral" onclick = "handleNum(this)"><a class="page-link" href="#">2</a></li>
              <li class="page-item page_numeral" onclick = "handleNum(this)"><a class="page-link" href="#">3</a></li>
              <li class="page-item" onclick = "handleNext(this)" id = "nextBtn">
                <a class="page-link" href="#">下一页</a>
              </li>
            </ul>
          </nav>
          <br />
          <small class='text-muted'>技术支持：珞珈技术俱乐部</small>
        </div>

      </div>
      <div class="col-md-3 col-sm-2"></div>
    </div>




  <script>
    var stamp = Math.floor(new Date().getTime()/1000)
    //getTime返回的是毫秒，php数据库里存的是秒数，所以差10^3

    var insertTarget = document.getElementById("mainContent");
    var maxPage = 42;  //现在数据库里确实只有42页，网页刚开始会重新载入这个数据（getMaxPage)
    var currentPage = 1;

    var page_nums = document.getElementsByClassName("page_numeral");
    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");

    var renderSource = function (resp){
      console.log("rendering source:");
      console.log(resp);
      insertTarget.innerHTML = "";
      var i = 0;
      for(i = 0; resp[i];i++){

        insertTarget.innerHTML += constructView(resp[i]);
      }
      stamp = resp[i-1].TIME_COMMIT;
    }

    var renderFaliure = function () {
      alert("没有更多了！")

    }

    var constructView = function (entry){

      var commitDate = new Date(entry.TIME_COMMIT * 1000).toLocaleString();
      var piclist = entry.PIC_LOC.split(',');


      var view = "<div class='card'><div class='card-block'><small class='text-muted'>"+entry.UUID+"</small><p class='card-text'>"+entry.COMMENT+"</p><p class='card-text'><small class='text-muted'>"+commitDate+"</small></p></div>"


      for(var i = 0;piclist[i];i++){
        //这里拿到正式的图片
        view += "<img class = 'card-img-bottom' src = '" + piclist[i] + "' onerror='handleBadImg(this)'/>";
      }
      view += "</div>";

      return view;
    }

    var handleBadImg = function (imgTag) {
      if (imgTag.parentElement.children.length == 2 //有一个comment和一个图片
      && imgTag.parentElement.children[0].children[1].innerText == ""){  //comment里空的
        var parent = imgTag.parentElement;
        parent.parentElement.removeChild(parent);

      }
      else{
        imgTag.parentElement.removeChild(imgTag);
      }
    }


    var ajaxRequest = function (target,succeed) {
      var request = new XMLHttpRequest();
      request.onreadystatechange = function (){
        if (request.readyState == 4 && request.status == 200){
          succeed(request);
        }
      }
      request.open('GET',target);//=最后一个stamp
      request.send();
    }
    var getpage = function (page) {
      ajaxRequest('ajaxRetrive.php?lastStamp='+page,
        function(request){
          if(request.responseText != "[false]"){
            var resp = JSON.parse(request.responseText);
            // console.log(resp);
            renderSource(resp);
          }
          else{
            //response表示已经没有图片了
            console.log("no more");
            renderFaliure();
            //提示没有更多图片了
          }
      })
    }
    var getMaxPage = function () {
      ajaxRequest('ajaxMaxPage.php',
        function (request) {
          if(request.responseText){
            maxPage = Number(request.responseText);
          }
          else{
            //失败就按着43走
            console.log("cannot fetch maximum page count, assuming 43.");
          }
      })
    }


    var handlePrev = function (btn) {
      console.log("priv!");
      if (!btn.classList.contains("disabled")){
        //没有被禁用
        nextBtn.classList.remove("disabled");
        for (var i = 0;page_nums[i];i++){
        page_nums[i].children[0].innerText = Number(page_nums[i].innerText) - 1;
        }
        if (page_nums[0].innerText == 1){
          btn.classList.add("disabled");
        }

        //切换页面
        currentPage -= 1;
        getpage(currentPage);
      }
      else{
        //
      }
    }
    var handleNext = function (btn) {
      console.log("next!");
      if (!btn.classList.contains("disabled")){
        //没有被禁用
        prevBtn.classList.remove("disabled");
        for (var i = 0;page_nums[i];i++){
        page_nums[i].children[0].innerText = Number(page_nums[i].innerText) + 1;
        }

        if (Number(page_nums[2].innerText) >= maxPage){
          btn.classList.add("disabled");
        }

        //切换页面
        currentPage += 1;
        getpage(currentPage);
      }
      else{
        //
      }
    }
    var handleNum = function (btn) {
      console.log("num!");
      //更新页面
      currentPage = Number(btn.innerText);
      getpage(currentPage);

      //更新高亮
      for (var i = 0;page_nums[i];i++){
        page_nums[i].classList.remove("active");
      }
      btn.classList.add("active");
    }

    //网页载入完成后先取得总页数再加载第一页
    getMaxPage();
    getpage(currentPage);
  </script>
  </body>


</html>
