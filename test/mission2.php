<!DOCTYPE html>
<html lang="ja">
<body>
    <form action="" method="post">
        故障とみなす回数N：<input type="text"name="error">
        <input type="submit"name="submit">
    </form>
</body>
<?php
$i=0;
if(isset($_POST["submit"])){
    //  Step1.回数Nを入力し、監視ログファイルを読み込み、サーバーアドレス毎を時間順に並び替える
    //回数Nを入力
    $N = $_POST["error"];
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
        //   step2.故障期間を求める
        for($o=0;$o<=$k-1;$o++){
            //番号oで応答結果が”-”、番号o-1で応答結果が”-出ない”となっている番号o（新たに番号pとおく）の時刻を故障開始とする
            if(preg_match('/-/',$list[$o]) && !preg_match('/-/',$list[$o-1])){
                $listcontent = explode(",",$list[$o]);
                $p=$listcontent[0];
                $kaisuu = 0;
                while(1){
                    if(!preg_match('/-/',$list[$p])){
                        if($p-$listcontent[0]<$N)break;
                        $funcontent = explode(",",$list[$p]);
                        echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                        echo "故障期間：".$listcontent[1]." から ".$funcontent[1]."<br>"."<br>";
                        break;
                    }
                    else
                        $p++;
                    if($p>=$k){
                        if($p-$listcontent[0]<$N)break;
                        echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                        echo "故障期間：".$listcontent[1]." から "."故障中"."<br>"."<br>";
                        break;
                    }
                }
            }
        }
    }
}
?>
</html>
