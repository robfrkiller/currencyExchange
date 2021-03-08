# 資料庫測驗

## 題目一

目前 XXXXXX 營運人員想知道 2021 年 2 月，訂單數量前 10 名的旅宿，請用一條 SQL 查詢出結果。

答案也請附上您建立資料表的 SQL 以及使用的資料庫，並說明如果由您來規劃，您會如何調整資料表結構。

- 旅宿資料表 (property)：id, name
- 房間資料表 (room)：id, property_id, name
- 訂單資料表 (orders)：id, room_id, price, created_at

### Answer

```SQL
-- 建立 MySQL 資料表
CREATE DATABASE IF NOT EXISTS `testing`;
USE `testing`;
CREATE TABLE IF NOT EXISTS `property` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `room` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `price` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 查詢 2021 年 2 月，訂單數量前 10 名的旅宿
SELECT
	COUNT(`p`.`id`) `order_count`,
	`p`.`id`
FROM
	`property` `p`
JOIN `room` `r` ON
	`r`.`property_id` = `p`.`id`
JOIN `orders` `o` ON
	`o`.`room_id` = `r`.`id`
	AND `o`.`created_at` BETWEEN '2021-02-01 00:00:00' AND '2021-02-28 23:59:59'
GROUP BY
	`p`.`id`
ORDER BY
	`order_count` DESC
LIMIT 10;

-- 調整資料表結構最佳化查詢
ALTER TABLE `room`
    -- JOIN 條件加上索引
    ADD INDEX idx_property_id(`property_id`);
ALTER TABLE `orders`
    -- 若 orders 加上 property_id 欄位資料就不須 JOIN room
    ADD `property_id` INT NOT NULL AFTER `id`,
    -- JOIN 條件加上索引
    ADD INDEX idx_room_id_created_at(`room_id`, `created_at`);
```

## 題目二

根據題目一的查詢，發現查詢速度過慢（超過 10 秒），您覺得問題可能會是什麼？您會嘗試如何調整？請試著闡述您的方法。

### Answer

- 硬體故障
    - 檢測並修復相關硬體，例如 CPU、RAM、硬碟等
- 硬體資源不足
    - 升級或增加 CPU、RAM、硬碟等硬體
- 資料庫配置錯誤
    - 依據當前硬體環境及使用狀況調整合適的資料庫環境設定，比如最大可連線數、最大記憶體使用量
- 單位時間內查詢量過大
    - 設置資料庫主從架構，將查詢分散至各個伺服器，伺服器數量越多，單個伺服器平均負責的查詢量就越小
- 單個資料表資料量過多
    - 依不同的資料型態分表，比如以訂單建立時間區分出每月訂單表，單個訂單表資料量最多就只剩一個月份量
- JOIN 過多資料表
    - 嘗試反正規化設計資料表，將部份主鍵或不會變動的資訊存至子表，減少為了需要額外資訊而 JOIN 資料表
- 查詢時未使用最佳索引
    - explain 確認是否有依查詢條件使用到最佳索引，若無可依 WHERE 條件欄位建立索引
- 建立過多不必要索引
    - 將效率不足的索引刪除，避免資料庫執行計劃花費更多時間判斷選擇哪一個索引
- 報表類型資料重覆運算
    - 定期排程計算單月統計並另存放於月報表資料表，後續查詢直接查月報表，只須查出一筆資料就可得知排名，也減輕訂單表查詢量
- 未設置快取
    - 較複雜且難以最佳化的查詢可快取其結果，例如使用 Redis、Memcached，已快取過的資料就不再經過資料庫查詢
