# API 文件

## 匯率轉換

將來源幣別金額轉換為目標幣別金額

### Request

請求方法： GET /api/currency/exchange.php

參數名稱 | 型態 | 必要 | 說明 | 範例
|---|---|:---:|---|---|
src | string | Y | 來源幣別，參考 <a href="#支援幣別">#支援幣別</a> | TWD
dst | string | Y | 目標幣別，參考 <a href="#支援幣別">#支援幣別</a> | USD
amount | float | Y | 來源幣別金額數字 | 123.321

#### 請求範例
```bash
curl 'http://domain.com/api/currency/exchange.php?src=TWD&dst=USD&amount=123.321'
```

### Response

回應內容為 JSON 格式，需解析後取出資料

參數名稱 | 型態 | 說明 | 範例
|---|---|---|---|
success | bool | 是否請求成功 | true
message | string | 請求錯誤時相關的錯誤訊息，請求成功時為空字串 | Parameter error.
result | string | 請求成功回傳目標幣別金額數字，轉換後數值將增加逗點分隔做為千分位表示 | 123,456.78

#### 回應範例
```json
{
    "success": true,
    "message": "",
    "result": "123,456.78"
}
```

> ※ 須判斷 Response status code 為 200 及回應參數 success 為 true 才算請求成功，其餘狀況皆為請求失敗，可參照 <a href="#異常錯誤說明">#異常錯誤說明</a> 嘗試修正問題

## 異常錯誤說明

回應錯誤訊息(message) | 說明
---|---
Parameter error. | 請求參數錯誤，須確認有帶入必要參數及資料型態正確性
Unsupported source currency. | 尚未支援的來源幣別
Unsupported destination currency. | 尚未支援的目標幣別

## 支援幣別

來源幣別 | 說明
|:---:|---
TWD |
JPY |
USD |

目標幣別 | 說明
:---:|---
TWD |
JPY |
USD |
