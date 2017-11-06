/**
 * Created by xinshi on 2015/7/31.
 */
function FreshTime()
{
    var endtime=new Date("2015/8/10,00:00:00");//结束时间
    var nowtime = new Date();//当前时间
    var lefttime= parseInt((endtime.getTime()-nowtime.getTime())/1000)  ;
    d= parseInt(lefttime/(60*60*24)) ;
    h= parseInt(lefttime/(60*60)%24);
    m=parseInt(lefttime/60%60);
    s=parseInt(lefttime%60);
    if (d >= 10 ){
        d = d;
    }
    else
    {
        d = "0" + d;
    }
    if (h >= 10 ){
        h = h;
    }
    else
    {
        h = "0" + h;
    }
    if (m >= 10 ){
        m = m;
    }
    else
    {
        m = "0" + m;
    }
    if (s >= 10 ){
        s = s;
    }
    else
    {
        s = "0" + s;
    }
    document.getElementById("LeftTime").innerHTML="&nbsp;"+d+"　"+h+"　"+m+"　"+s;
    if(lefttime<=0){
        document.getElementById("LeftTime").innerHTML="&nbsp;"+'00'+"　"+'00'+"　"+'00'+"　"+'00';
        clearInterval(sh);
    }
}

function showarea(id){

    var tipSuccess = '恭喜您投票成功!';
    var tipIpError = '您已投过票了!';
    var tipDelError = '投票失败!';
    var tipParamError = '参数错误!';
    var num = $('#'+id).text();
    var flag = "joymevote";

    jQuery.ajax({
        "url": "recordVote.php",
        "type": "post",
        "data": {"id":id,"flag":flag},
        "success": function(msg) {
            var data = eval('(' + msg + ')');
            if(data['rs'] == 0){
                $('#'+id).text(parseInt(num)+1)
                $('#t_area').text(tipSuccess);
            }else if(data['rs'] == 1){
                $('#t_area').text(tipDelError);
            }else if(data['rs'] == 2){
                $('#t_area').text(tipIpError);
            }else if(data['rs'] == 3){
                $('#t_area').text(tipParamError);
            }
        }
    })
    var con = document.getElementById("t_area");
    con.style.display = "block";
}

function offarea(){
    var con = document.getElementById("t_area");
    con.style.display = "none";
}

var timer;
FreshTime()
var sh;
sh=setInterval('FreshTime()',500)  ;


