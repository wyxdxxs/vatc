import requests
import json
import time

# 心流平台配置
API_URL = "https://apis.iflow.cn/v1/chat/completions"
API_KEY = "sk-1910f25330033eda8a702ffff0d17083"
MODEL = "kimi-k2"

def call_xinliu_ai(prompt):
    headers = {
        "Content-Type": "application/json",
        "Authorization": f"Bearer {API_KEY}"
    }
    data = {
        "model": MODEL,
        "messages": [
            {"role": "system", "content": "你是VATC航空公司的专业管制员小v，回答专业、冷静，符合航空管制用语。"},
            {"role": "user", "content": prompt}
        ],
        "temperature": 0.7
    }
    try:
        response = requests.post(API_URL, headers=headers, json=data)
        response.raise_for_status()
        result = response.json()
        return result["choices"][0]["message"]["content"]
    except Exception as e:
        return f"调用心流平台失败：{str(e)}"

def main():
    print("VATC 管制员小v 已启动...")
    while True:
        user_input = input("请输入指令（输入 'exit' 退出）：")
        if user_input.lower() == "exit":
            break
        reply = call_xinliu_ai(user_input)
        print(f"小v: {reply}\n")
        time.sleep(1)

if __name__ == "__main__":
    main()
