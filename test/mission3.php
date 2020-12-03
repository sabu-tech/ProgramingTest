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
    //Step1.直近m、過負荷状態時間tを入力し、監視ログファイルを読み込み、サーバーアドレス毎を時間順に並び替える
    $m = $_POST["ave"];
    $t = $_POST["time"];
    $lines = file("data.txt");
    foreach($lines as $line){//explodeを使って各サーバーアドレスのみを取得
        $content = explode(",",$line);
        $add[$i]=$content[1];
        $i++;
        $address = array_unique($add);//同じサーバーアドレスは空にし配列（配列addressとする）の要素に入れる 
    }
    for($j=0;$j<=count($add);$j++){//ある配列addressの要素と一致いた順から番号（番号k）をつける
        $k=0;
        foreach($lines as $line){
            $content = explode(",",$line);
            if($content[1]===$address[$j]){
                $list[$k] = $k.",".$line;
                $k++;   
            }
        }
        //Step2.直近m回での応答平均時間を求める
        for($o=$m-1;$o<$k;$o++){
            $totaltime =0;
            for($p=$o;$p>$o-$m;$p--){
                $listcontent = explode(",",$list[$p]);
                //その時応答結果が”-”のとき
                if(preg_match('/-/',$list[$p])){
                    $listcontent[3]=0;
                }
                $totaltime += $listcontent[3];  
            }
            $averagetime[$o] = $totaltime/$m;
        }
        //番号oから番号k（そのサーバーアドレスの最後の番号）まで繰り返す
        for($o=$m-1;$o<=$k;$o++){
            //番号oの平均時間が過負荷状態時間tより大きく、番号o-1の平均時間が過負荷状態時間tより小さい時
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
