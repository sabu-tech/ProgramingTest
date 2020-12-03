<?php
$i=0;
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
    for($o=0;$o<=$k-1;$o++){
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