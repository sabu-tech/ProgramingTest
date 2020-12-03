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
$o=0;
if(isset($_POST["submit"])){
    //Step1.ネットワーク毎に分ける
$N = $_POST["error"];
$lines = file("data.txt");
foreach($lines as $line){                      
    $content = explode(",",$line);
    $data[$i] = "num".$i."_".$line; //上から順番にナンバーをつける(numi_)これを配列dataとする
    $add[$i]=$content[1]; //explodeを使って各サーバーアドレスのみを取得
    $i++;
    $address = array_unique($add);
}
    
for($i=0;$i<count($add);$i++){  //サーバーアドレスから同じネットワークを探す                  
    $content = explode("/",$add[$i]);
    $net[$i] = $content[0];
    $netcontent = explode(".",$net[$i]);
    $net_2[$i] = sprintf('%08d',decbin($netcontent[0])).sprintf('%08d',decbin($netcontent[1])).
    sprintf('%08d',decbin($netcontent[2])).sprintf('%08d',decbin($netcontent[3]));
    $network[$i] = substr($net_2[$i],0,$content[1]);
    $network = array_unique($network);
}
for($i=0;$i<count($add);$i++){//networkとが一致しているnet_2を探す
    for($j=0;$j<count($add);$j++ ){
        if(preg_match("/".$network[$i]."/",$net_2[$j])&& !empty($network[$i])){
            $x[$o]="num".$j."_";
            $o++;
        }
    }
}
for($i=0;$i<count($add);$i++){
    for($j=0;$j<count($add);$j++){
        if(preg_match("/".$x[$i]."/",$data[$j])){
            $content = explode(",",$data[$j]);
            $add_2[$i] = $content[1];
            $address_2 = array_unique($add_2);
        }
    }
    
}
for($o=0;$o<count($add);$o++){//Step2.同じネットワーク内でサーバーアドレス毎に分ける
    $k=0;
    for($i=0;$i<count($add);$i++){
        for($j=0;$j<count($add);$j++){
            if(preg_match("/".$x[$i]."/",$data[$j])){
                $content = explode(",",$data[$j]);
                if($content[1]==$address_2[$o] ){
                    $datalist[$k] = $k.",".$data[$j];
                    $k++;
                }
            }
        }
    }
    //Step3.ネットワークの故障期間を求める (ここからできていない)
    for($q=0;$q<=$k-1;$q++){
        if(preg_match('/-/',$datalist[$q]) && !preg_match('/-/',$datalist[$q-1])){
            $listcontent = explode(",",$datalist[$q]);
            $p=$listcontent[0];
            while(1){
                if(!preg_match('/-/',$datalist[$p])){
                    if($p-$listcontent[0]<$N)break;
                    echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                    $funcontent = explode(",",$datalist[$p]);
                    $funcontent = explode("_",$funcontent[1]);
                    $listcontent=explode("_",$listcontent[1]);
                    
                    echo "故障期間：".$listcontent[1]." から ".$funcontent[1]."<br>"."<br>";
                    break;
                }
                else
                    $p++;
                if($p>=$k){
                    if($p-$listcontent[0]<$N)break;
                    echo "故障状態のサーバーアドレス：".$listcontent[2]."<br>";
                    $listcontent=explode("_",$listcontent[1]);
                    
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
