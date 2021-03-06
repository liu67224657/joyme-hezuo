<?php
require_once ("jssdk.php");
require_once("config.php");
$appid = APP_ID;
$appsecret = APP_SECRET;
$jssdk = new JSSDK("wx28d67b05018d0f19", "dad223d60f6d9d6ee3ca7d4ce2326f5a");
$signPackage = $jssdk->GetSignPackage();

echo $appid;
echo $appsecret; 
echo '<pre>';
print_r($signPackage);
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
</head>
<body>
  
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
wx.onMenuShareAppMessage({
      title: '最强请假条',
      desc: '终于找到不上班的完美理由，不妨借你用用啊！',
      link: 'http://hezuo.joyme.com/xjhx/index.php',
      imgUrl: 'http://hezuo.joyme.com/xjhx/gamename.jpg',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
        alert('用户点击发送给朋友');
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

  wx.onMenuShareTimeline({
      title: '最强请假条',
      link: 'http://hezuo.joyme.com/xjhx/index.php',
      imgUrl: 'http://hezuo.joyme.com/xjhx/gamename.jpg',
      trigger: function (res) {
        // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
        alert('用户点击分享到朋友圈');
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

      wx.onMenuShareQQ({
      title: '最强请假条',
      desc: '终于找到不上班的完美理由，不妨借你用用啊！',
      link: 'http://hezuo.joyme.com/xjhx/index.php',
      imgUrl: 'http://hezuo.joyme.com/xjhx/gamename.jpg',
      trigger: function (res) {
        alert('用户点击分享到QQ');
      },
      complete: function (res) {
        alert(JSON.stringify(res));
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

    wx.onMenuShareWeibo({
      title: '最强请假条',
      desc: '终于找到不上班的完美理由，不妨借你用用啊！',
      link: 'http://hezuo.joyme.com/xjhx/index.php',
      imgUrl: 'http://hezuo.joyme.com/xjhx/gamename.jpg',
      trigger: function (res) {
        alert('用户点击分享到微博');
      },
      complete: function (res) {
        alert(JSON.stringify(res));
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });
       wx.onMenuShareQZone({
      title: '最强请假条',
      desc: '终于找到不上班的完美理由，不妨借你用用啊！',
      link: 'http://hezuo.joyme.com/xjhx/index.php',
      imgUrl: 'http://hezuo.joyme.com/xjhx/gamename.jpg',

      trigger: function (res) {
        alert('用户点击分享到QZone');
      },
      complete: function (res) {
        alert(JSON.stringify(res));
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

  });
</script>
Test Share
</html>
