<!DOCTYPE html>
<html lang="ja">
<body>
    <form action="" method="post">
        直近m回：<input type="text"name="ave">
        過負荷状態時間tミリ秒：<input type="text"name="time">
        <input type="submit"name="submit">
    </form>
</body>
<?php
$i=0;
if(isset($_POST["submit"])){
    $m = $_POST["ave"];
    $t = $_POST["time"];
    $lines = file("data.txt");
    foreach($lines as $line){//サーバーアドレス毎に分ける
        $content = explode(",",$line);
        $add[$i]=$content[1];
        $i++;
        $address = array_unique($add);
    }
    for($j=0;$j<=count($add);$j++){//一つ一つに数字をつける
        $k=0;
        foreach($lines as $line){
            $content = explode(",",$line);
            if($content[1]===$address[$j]){
                $list[$k] = $k.",".$line;
                $k++;   
            }
        }
        for($o=$m-1;$o<$k;$o++){
            $totaltime =0;
            for($p=$o;$p>$o-$m;$p--){
                $listcontent = explode(",",$list[$p]);
                if(preg_match('/-/',$list[$p])){
                    $listcontent[3]=0;
                }
                $totaltime += $listcontent[3];  
            }
            $averagetime[$o] = $totaltime/$m;
        }
        for($o=$m-1;$o<=$k;$o++){
            if($averagetime[$o]>=$t && $averagetime[$o-1]<$t){
                $listcontent = explode(",",$list[$o]);
                $date = $listcontent[1];
                for($p=0;$p+$o<$k;$p++){
                    if($averagetime[$o+$p]<$t){
                        $funlistcontent = explode(",",$list[$o+$p]);
                        echo "過負荷状態のサーバーアドレス".$listcontent[2]."<br>";
                        echo "過負荷状態の期間".$date."から".$funlistcontent[1]."<br>";
                        break;
                    }   
                    if($o+$p==$k-1 && $averagetime[$o+$p]>=$t){
                        echo "過負荷状態のサーバーアドレス".$listcontent[2]."<br>";
                        echo "過負荷状態の期間".$date."から"."過負荷中"."<br>";
                    }
                }
            }
        }
    }
}
?>
</html>