<?php
/**
 *文章链接函数
 *$row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
  $row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']
 */
function GetFileUrl($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$typedir='',
    $money=0, $filename='',$moresite=0,$siteurl='',$sitepath='')
    {
        $articleUrl = GetFileName($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$typedir,$money,$filename);
        $sitepath = MfTypedir($sitepath);

            if($siteurl=='')
            {
                //$siteurl = $GLOBALS['cfg_basehost'];
				$siteurl = 'www.joyme.com';
            }
            if($moresite==1)
            {
                $articleUrl = preg_replace("#^".$sitepath.'#', '', $articleUrl);
            }
            if(!preg_match("/http:/", $articleUrl))
            {
                $articleUrl = $siteurl.$articleUrl;
            }

        return $articleUrl;
    }

function MfTypedir($typedir)
{
    if(preg_match("/^http:|^ftp:/i", $typedir)) return $typedir;
    $typedir = str_replace("{cmspath}",$GLOBALS['cfg_cmspath'],$typedir);
    $typedir = preg_replace("/\/{1,}/", "/", $typedir);
    return $typedir;
}
	
function GetFileName($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$typedir='',$money=0,$filename='')
    {
        global $cfg_rewrite, $cfg_cmspath, $cfg_arcdir, $cfg_special, $cfg_arc_dirname;
        //没指定栏目时用固定规则（专题）
        if(empty($namerule)) {
            $namerule = $cfg_special.'/arc-{aid}.html';
            $typeid = -1;
        }
        if($rank!=0 || $ismake==-1 || $typeid==0 || $money>0)
        {
            //动态文章
            if($cfg_rewrite == 'Y')
            {
                return $GLOBALS["cfg_plus_dir"]."/view-".$aid.'-1.html';
            }
            else
            {
                return $GLOBALS['cfg_phpurl']."/view.php?aid=$aid";
            }
        }
        else
        {
            $articleDir = MfTypedir($typedir);
            $articleRule = strtolower($namerule);
            if($articleRule=='')
            {
                $articleRule = strtolower($GLOBALS['cfg_df_namerule']);
            }
            if($typedir=='')
            {
                $articleDir  = $GLOBALS['cfg_cmspath'].$GLOBALS['cfg_arcdir'];
            }
            $dtime = GetDateMk($timetag);
            list($y, $m, $d) = explode('-', $dtime);
            $arr_rpsource = array('{typedir}','{y}','{m}','{d}','{timestamp}','{aid}','{cc}');
            $arr_rpvalues = array($articleDir,$y, $m, $d, $timetag, $aid, dd2char($m.$d.$aid.$y));
            if($filename != '')
            {
                $articleRule = dirname($articleRule).'/'.$filename.$GLOBALS['cfg_df_ext'];
            }
            $articleRule = str_replace($arr_rpsource,$arr_rpvalues,$articleRule);
            if(preg_match("/\{p/", $articleRule))
            {
                $articleRule = str_replace('{pinyin}',GetPinyin($title).'_'.$aid,$articleRule);
                $articleRule = str_replace('{py}',GetPinyin($title,1).'_'.$aid,$articleRule);
            }
            $articleUrl = '/'.preg_replace("/^\//", '', $articleRule);
            if(preg_match("/index\.html/", $articleUrl) && $cfg_arc_dirname=='Y')
            {
                $articleUrl = str_replace('index.html', '', $articleUrl);
            }
            return $articleUrl;
        }
    }

function GetDateMk($mktime)
    {
        if($mktime=="0") return "暂无";
        else return MyDate("Y-m-d", $mktime);
    }

function MyDate($format='Y-m-d H:i:s', $timest=0)
    {
        global $cfg_cli_time;
        $addtime = $cfg_cli_time * 3600;
        if(empty($format))
        {
            $format = 'Y-m-d H:i:s';
        }
        return gmdate ($format, $timest+$addtime);
    }
	
function dd2char($ddnum)
    {
        $ddnum = strval($ddnum);
        $slen = strlen($ddnum);
        $okdd = '';
        $nn = '';
        for($i=0;$i<$slen;$i++)
        {
            if(isset($ddnum[$i+1]))
            {
                $n = $ddnum[$i].$ddnum[$i+1];
                if( ($n>96 && $n<123) || ($n>64 && $n<91) )
                {
                    $okdd .= chr($n);
                    $i++;
                }
                else
                {
                    $okdd .= $ddnum[$i];
                }
            }
            else
            {
                $okdd .= $ddnum[$i];
            }
        }
        return $okdd;
    }
	
