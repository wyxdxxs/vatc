<?php
// 心流平台配置
$API_URL = "https://apis.iflow.cn/v1/chat/completions";
$API_KEY = "sk-1910f25330033eda8a702ffff0d17083";
$MODEL = "kimi-k2";

$reply = "";
$user_msg = "";

// 接收用户输入
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['msg'])) {
    $user_msg = trim($_POST['msg']);
    
    // 请求AI接口
    $data = [
        'model' => $MODEL,
        'messages' => [
            ['role' => 'system', 'content' => '你是VATC航空公司的专业管制员小v，回答专业、冷静，符合航空管制用语。'],
            ['role' => 'user', 'content' => $user_msg]
        ],
        'temperature' => 0.7
    ];

    $ch = curl_init($API_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $API_KEY
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        $reply = "接口请求失败：" . $error;
    } else {
        $res_arr = json_decode($result, true);
        if (isset($res_arr['choices'][0]['message']['content'])) {
            $reply = $res_arr['choices'][0]['message']['content'];
        } else {
            $reply = "AI返回异常：" . $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>VATC 航空管制员小v</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: "Microsoft YaHei", sans-serif;
        }
        body {
            max-width: 700px;
            margin: 30px auto;
            padding: 0 15px;
            background: #f5f7fa;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-top: 0;
        }
        .chat-area {
            height: 450px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
            margin-bottom: 15px;
            background: #fafafa;
        }
        .msg {
            margin: 10px 0;
            padding: 10px 14px;
            border-radius: 8px;
            max-width: 85%;
            line-height: 1.5;
        }
        .user {
            background: #d1e7ff;
            margin-left: auto;
            text-align: right;
        }
        .bot {
            background: #eeeeee;
            margin-right: auto;
            text-align: left;
        }
        .input-box {
            display: flex;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }
        button {
            padding: 12px 20px;
            background: #2d8cf0;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background: #1b79e0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🛫 VATC 航空管制员小v</h2>
    <div class="chat-area">
        <?php if (!empty($user_msg)): ?>
            <div class="msg user"><?php echo htmlspecialchars($user_msg); ?></div>
            <div class="msg bot"><?php echo htmlspecialchars($reply); ?></div>
        <?php endif; ?>
    </div>
    <form method="post" class="input-box">
        <input type="text" name="msg" placeholder="请输入管制指令..." required autocomplete="off">
        <button type="submit">发送</button>
    </form>
</div>
</body>
</html>
