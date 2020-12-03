<?php
$i=0;
$lines = file("data.txt");
//     Step1.監視ログファイルを読み込み、サーバーアドレス毎を時間順に並び替える
foreach($lines as $line){//ログファイルを開く
    $content = explode(",",$line);
    $add[$i]=$content[1];//explodeを使って各サーバーアドレスのみを取得
    $i++;
    $address = array_unique($add);//同じサーバーアドレスは空にし配列（配列addressとする）の要素に入れる 
}
//もう一度ログファイルを開く
for($j=0;$j<=count($add);$j++){//ある配列addressの要素と一致いた順から番号（番号k）をつける
    $k=0;//一つの要素の識別が終わったら番号をリセットする(番号k=0)
    foreach($lines as $line){
        $content = explode(",",$line);
        if($content[1]===$address[$j]){
            $list[$k] = $k.",".$line;
            $k++;   
        }
    }
    //    step2.故障期間を求める
    for($o=0;$o<=$k-1;$o++){
        //番号oで応答結果が”-”、番号o-1で応答結果が”-出ない”となっている番号o（新たに番号pとおく）の時刻を故障開始とする
        if((preg_match('/-/',$list[$o]) && !preg_match('/-/',$list[$o-1])) || preg_match('/-/',$list[0])){
            $listcontent = explode(",",$list[$o]);
            $p=$listcontent[0];
            while(1){
                if(!preg_match('/-/',$list[$p])){
                    $funcontent = explode(",",$list[$p]);
                    echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                    echo "故障期間：".$listcontent[1]." から ".$funcontent[1]."<br>"."<br>";
                    break;
                }
                else
                    $p++;
                if($p>=$k){
                    echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                    echo "故障期間：".$listcontent[1]." から "."故障中"."<br>"."<br>";
                    break;
                }
            }
        }
    }
}
?>
