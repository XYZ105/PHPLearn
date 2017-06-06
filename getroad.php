<?php
$db = mysqli_connect("localhost", "root", "zhou1990", "traffic") or die("连接失败");
// 请求数据
$url = "http://www.nitrafficindex.com/traffic/getRoadIndex.do";
$postData = array("areaCode"=>"110000","roadLevel"=>"1,2,3,4,5,6,7", "page"=>"1", "rows"=>"2000");
//$postData = "areaCode=110000&roadLevel=1,2,3,4,5,6,7&page=1&rows=10";
$ch = curl_init();
curl_setopt($ch,CURLOPT_PROXY,'127.0.0.1:8888');
curl_setopt($ch, CURLOPT_URL, $url);
// 以字符串返回
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Cookie: JSESSIONID=8D528C34DB9141797FEF32576147EC68",
    "Content-Type:application/x-www-form-urlencoded"
    //"Content-Type:multipart/form-data;charset='utf-8'"
));
$output = curl_exec($ch);
curl_close($ch);

$obj = json_decode($output);

$rows = $obj->rows;

foreach ($rows as $info)
{
    $roadid = $info->id;
    $roadname = $info->name;
    $startName = $info->startName;
    $endName = $info->endName;
    $dir = $info->dir;
    $roadGrade = $info->roadGrade;
    $cIndex = $info->cIndex;
    $sIndex = $info->sIndex;
    $bIndex = $info->bIndex;

    $sql = "insert into roadinfo (id, name, startName, endName, dir, roadGrade, cIndex, sIndex, bIndex) values ('$roadid', '$roadname', '$startName', '$endName', '$dir', $roadGrade, $cIndex, $sIndex, $bIndex)";
    if (!mysqli_query($db, $sql))
    {
        echo "插入失败";
    }
}
mysqli_close($db);
echo "下载完成";
?>
