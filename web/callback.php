<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');
//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);
//ユーザタイプ取得
$type = $jsonObj->{"events"}[0]->{"source"}->{"type"};
if ($type == "group") {
  //グループID取得
  $returnId = $jsonObj->{"events"}[0]->{"source"}->{"groupId"};
} elseif ($type == "room") {
  //ルームID取得
  $returnId = $jsonObj->{"events"}[0]->{"source"}->{"roomId"};
} else {
  //ユーザID取得
  $returnId = $jsonObj->{"events"}[0]->{"source"}->{"userId"};
}
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};
//返信データ作成
$response_format_text = [
  "type" => "text",
  "text" => "type=" . $type . ", id=" . $returnId
];
$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
	];
$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
