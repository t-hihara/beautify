# データベース設計

## 概要
このドキュメントでは、美容室サロン予約システムのデータベース設計の概要を説明します。

## 主要テーブル

### 1. users（ユーザー）
ユーザー情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `name` (string) - ユーザー名
- `email` (string, unique) - メールアドレス
- `email_verified_at` (timestamp, nullable) - メール認証日時
- `password` (string) - パスワード（ハッシュ化）
- `phone` (string, nullable) - 電話番号
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `reservations` (1対多)
- `favorite_shops` (多対多)

---

### 2. shops（店舗）
店舗情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `name` (string) - 店舗名
- `description` (text, nullable) - 説明
- `address` (string) - 住所
- `phone` (string) - 電話番号
- `email` (string, nullable) - メールアドレス
- `image_path` (string, nullable) - 画像パス
- `opening_hours` (json, nullable) - 営業時間
- `is_active` (boolean, default: true) - 公開状態
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `menus` (1対多)
- `staff` (1対多)
- `reservations` (1対多)
- `shop_managers` (多対多) - 店舗店長との関連

---

### 3. menus（メニュー）
ヘアメニューを管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `name` (string) - メニュー名（カット、カラー、パーマなど）
- `description` (text, nullable) - 説明
- `price` (decimal) - 料金
- `duration` (integer) - 所要時間（分）（カット30分、カラー90分など）
- `image_path` (string, nullable) - 画像パス
- `category` (string, nullable) - カテゴリー（カット、カラー、パーマ、トリートメント、ヘッドスパなど）
- `is_active` (boolean, default: true) - 公開状態
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `reservations` (1対多)
- `staff` (多対多) - 担当スタイリスト

---

### 4. staff（スタイリスト）
スタイリスト情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `user_id` (bigint, foreign key, nullable) - ユーザーID（スタイリストがユーザー登録している場合）
- `name` (string) - スタイリスト名
- `description` (text, nullable) - プロフィール
- `image_path` (string, nullable) - 画像パス
- `skills` (json, nullable) - スキル（カット、カラー、パーマ、ヘアセットなど）
- `experience_years` (integer, nullable) - 経験年数
- `qualifications` (json, nullable) - 資格情報
- `is_active` (boolean, default: true) - 有効状態
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `user` (多対1, nullable)
- `reservations` (1対多)
- `shifts` (1対多)
- `menus` (多対多) - 担当メニュー

---

### 5. reservations（予約）
予約情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `user_id` (bigint, foreign key, nullable) - ユーザーID（ゲスト予約の場合はnull）
- `customer_id` (bigint, foreign key, nullable) - 顧客ID（ログインユーザーの顧客プロフィール。ゲスト予約の場合はnull）
- `menu_id` (bigint, foreign key) - メニューID
- `staff_id` (bigint, foreign key, nullable) - スタイリストID
- `reservation_date` (date) - 予約日
- `reservation_time` (time) - 予約時間
- `guest_name` (string, nullable) - ゲスト名（ゲスト予約の場合）
- `guest_email` (string, nullable) - ゲストメール（ゲスト予約の場合）
- `guest_phone` (string, nullable) - ゲスト電話（ゲスト予約の場合）
- `status` (string) - ステータス（pending, approved, rejected, cancelled, completed）
- `amount` (decimal, nullable) - 予約金額
- `discount_amount` (decimal, default: 0) - 割引額
- `point_used` (integer, default: 0) - 使用ポイント
- `coupon_id` (bigint, foreign key, nullable) - 使用クーポンID
- `notes` (text, nullable) - 備考
- `reminder_sent` (boolean, default: false) - リマインダー送信済み
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `user` (多対1, nullable)
- `customer` (多対1, nullable)
- `menu` (多対1)
- `staff` (多対1, nullable)
- `coupon` (多対1, nullable)
- `visit_histories` (1対1, オプション)
- `counseling_sheets` (1対1, オプション)
- `treatment_photos` (1対多)
- `sales` (1対1, オプション)

**インデックス**:
- `shop_id`, `reservation_date`, `reservation_time`
- `user_id`, `reservation_date`
- `status`

---

### 6. shifts（シフト）
スタッフのシフトを管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `staff_id` (bigint, foreign key) - スタイリストID
- `shift_date` (date) - シフト日
- `start_time` (time) - 開始時間
- `end_time` (time) - 終了時間
- `break_start` (time, nullable) - 休憩開始時間
- `break_end` (time, nullable) - 休憩終了時間
- `notes` (text, nullable) - 備考
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `staff` (多対1)

**インデックス**:
- `shop_id`, `shift_date`
- `staff_id`, `shift_date`

---

### 7. role_user（ロールとユーザーの関連）
Laravel Permissionパッケージが提供するテーブル

**カラム**:
- `role_id` (bigint, foreign key)
- `user_id` (bigint, foreign key)
- `model_type` (string)
- `model_id` (bigint)

---

### 8. model_has_permissions（モデルと権限の関連）
Laravel Permissionパッケージが提供するテーブル

**カラム**:
- `permission_id` (bigint, foreign key)
- `model_type` (string)
- `model_id` (bigint)

---

### 9. activity_log（アクティビティログ）
Laravel Activity Logパッケージが提供するテーブル

**カラム**:
- `id` (bigint, primary key)
- `log_name` (string)
- `description` (text)
- `subject_type` (string, nullable)
- `subject_id` (bigint, nullable)
- `event` (string, nullable)
- `causer_type` (string, nullable)
- `causer_id` (bigint, nullable)
- `properties` (json, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

---

### 10. favorite_shops（お気に入り店舗）
ユーザーのお気に入り店舗を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key) - ユーザーID
- `shop_id` (bigint, foreign key) - 店舗ID
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `user` (多対1)
- `shop` (多対1)

**ユニーク制約**:
- `user_id`, `shop_id` の組み合わせ

---

## リレーションシップ図

```
users
  ├── reservations (1対多)
  ├── favorite_shops (多対多)
  └── staff (1対1, オプション)

shops
  ├── menus (1対多)
  ├── staff (1対多)
  ├── reservations (1対多)
  ├── shifts (1対多)
  └── shop_managers (多対多)

menus
  ├── shop (多対1)
  ├── reservations (1対多)
  └── staff (多対多)

staff
  ├── shop (多対1)
  ├── user (多対1, オプション)
  ├── reservations (1対多)
  ├── shifts (1対多)
  └── menus (多対多)

reservations
  ├── shop (多対1)
  ├── user (多対1, オプション)
  ├── menu (多対1)
  └── staff (多対1, オプション)

shifts
  ├── shop (多対1)
  └── staff (多対1)
```

---

## インデックス戦略

### 主要なインデックス
1. **reservations テーブル**
   - `shop_id`, `reservation_date`, `reservation_time` - 予約検索の高速化
   - `user_id`, `reservation_date` - ユーザーの予約履歴検索
   - `status` - ステータスでのフィルタリング

2. **shifts テーブル**
   - `shop_id`, `shift_date` - 店舗のシフト検索
   - `staff_id`, `shift_date` - スタッフのシフト検索

3. **menus テーブル**
   - `shop_id`, `is_active` - 店舗の公開メニュー検索

4. **staff テーブル**
   - `shop_id`, `is_active` - 店舗の有効スタッフ検索

---

## データ整合性

### 外部キー制約
- `menus.shop_id` → `shops.id`
- `staff.shop_id` → `shops.id`
- `staff.user_id` → `users.id`
- `reservations.shop_id` → `shops.id`
- `reservations.user_id` → `users.id`
- `reservations.customer_id` → `customers.id`
- `reservations.menu_id` → `menus.id`
- `reservations.staff_id` → `staff.id`
- `reservations.coupon_id` → `coupons.id`
- `shifts.shop_id` → `shops.id`
- `shifts.staff_id` → `staff.id`
- `favorite_shops.user_id` → `users.id`
- `favorite_shops.shop_id` → `shops.id`
- `customers.user_id` → `users.id`
- `visit_histories.customer_id` → `customers.id`
- `visit_histories.reservation_id` → `reservations.id`
- `points.customer_id` → `customers.id`
- `points.reservation_id` → `reservations.id`
- `coupons.shop_id` → `shops.id`
- `coupons.customer_id` → `customers.id`
- `coupon_usages.coupon_id` → `coupons.id`
- `coupon_usages.reservation_id` → `reservations.id`
- `reviews.user_id` → `users.id`
- `reviews.customer_id` → `customers.id`
- `reviews.shop_id` → `shops.id`
- `reviews.staff_id` → `staff.id`
- `waitlists.shop_id` → `shops.id`
- `waitlists.user_id` → `users.id`
- `waitlists.customer_id` → `customers.id`
- `waitlists.menu_id` → `menus.id`
- `waitlists.staff_id` → `staff.id`
- `counseling_sheets.reservation_id` → `reservations.id`
- `counseling_sheets.customer_id` → `customers.id`
- `treatment_photos.reservation_id` → `reservations.id`
- `treatment_photos.customer_id` → `customers.id`
- `inventory_items.shop_id` → `shops.id`
- `inventory_transactions.inventory_item_id` → `inventory_items.id`
- `sales.shop_id` → `shops.id`
- `sales.reservation_id` → `reservations.id`
- `sales.customer_id` → `customers.id`
- `sales.staff_id` → `staff.id`
- `staff_performances.staff_id` → `staff.id`
- `staff_performances.shop_id` → `shops.id`
- `notifications.user_id` → `users.id`
- `notifications.customer_id` → `customers.id`

### カスケード削除
- 店舗削除時: メニュー、スタッフ、予約、シフトも削除（または制約エラー）
- ユーザー削除時: 予約、お気に入りも削除（または制約エラー）

---

### 11. customers（顧客）
ユーザーに1対1で紐づく顧客プロフィール。名前・連絡先・誕生日・性別など、認証とは別に管理する顧客情報。

**カラム**:
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key, unique) - ユーザーID（1ユーザー1顧客）
- `name` (string) - 顧客名
- `phone` (string, nullable) - 電話番号
- `email` (string, nullable) - メールアドレス（ユーザーのログイン用と別に持つ場合はここ）
- `birthday` (date, nullable) - 誕生日
- `gender` (string, nullable) - 性別（male, female, other, prefer_not_to_say など）
- `hair_type` (string, nullable) - 髪質（細い、太い、くせ毛、直毛、硬い、柔らかいなど）
- `allergies` (json, nullable) - アレルギー情報（カラー剤、パーマ剤など）
- `preferences` (json, nullable) - 好み・要望（希望スタイル、カラー希望、パーマ希望など）
- `notes` (text, nullable) - 備考
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `user` (1対1)
- `reservations` (1対多)
- `visit_histories` (1対多)
- `points` (1対多)
- `reviews` (1対多)

**ユニーク制約**:
- `user_id`

---

### 12. visit_histories（来店履歴）
顧客の来店履歴を管理するテーブル。予約に紐づく来店記録。

**カラム**:
- `id` (bigint, primary key)
- `customer_id` (bigint, foreign key) - 顧客ID
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `visit_date` (date) - 来店日
- `menu_id` (bigint, foreign key, nullable) - メニューID
- `staff_id` (bigint, foreign key, nullable) - スタイリストID
- `amount` (decimal) - 利用金額
- `points_earned` (integer, default: 0) - 当該来店で付与したポイント
- `notes` (text, nullable) - 備考
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `customer` (多対1)
- `reservation` (多対1, nullable)
- `menu` (多対1, nullable)
- `staff` (多対1, nullable)

---

### 13. points（ポイント）
ポイントの付与・使用履歴。**予約に対して付与**（来店時など）、使用時は予約に紐づけて記録する。

**カラム**:
- `id` (bigint, primary key)
- `customer_id` (bigint, foreign key) - 顧客ID（付与先・使用元）
- `reservation_id` (bigint, foreign key, nullable) - 予約ID（付与元の予約、または使用した予約）
- `points` (integer) - ポイント数（付与は正、使用は負）
- `type` (string) - タイプ（earned, used, expired）
- `description` (string, nullable) - 説明
- `expires_at` (timestamp, nullable) - 有効期限
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `customer` (多対1)
- `reservation` (多対1, nullable)

**インデックス**:
- `customer_id`, `type`
- `reservation_id` - 予約ごとの付与・使用検索
- `expires_at` - 有効期限切れポイント検索

---

### 14. coupons（クーポン）
クーポン情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID（個別発行の場合）
- `code` (string, unique) - クーポンコード
- `name` (string) - クーポン名
- `description` (text, nullable) - 説明
- `type` (string) - タイプ（percentage, fixed, free_service）
- `discount_value` (decimal) - 割引値（割引率または割引額）
- `min_amount` (decimal, nullable) - 最低利用金額
- `max_discount` (decimal, nullable) - 最大割引額
- `usage_limit` (integer, nullable) - 使用回数制限
- `used_count` (integer, default: 0) - 使用回数
- `starts_at` (timestamp) - 有効開始日時
- `expires_at` (timestamp) - 有効期限
- `is_active` (boolean, default: true) - 有効状態
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `customer` (多対1, nullable)
- `coupon_usages` (1対多)

---

### 15. coupon_usages（クーポン使用履歴）
クーポンの使用履歴を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `coupon_id` (bigint, foreign key) - クーポンID
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `discount_amount` (decimal) - 割引額
- `used_at` (timestamp) - 使用日時
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `coupon` (多対1)
- `reservation` (多対1, nullable)
- `customer` (多対1, nullable)

---

### 16. reviews（レビュー）
レビュー情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `shop_id` (bigint, foreign key) - 店舗ID
- `staff_id` (bigint, foreign key, nullable) - スタイリストID
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `shop_rating` (integer) - 店舗評価（1-5）
- `staff_rating` (integer, nullable) - スタイリスト評価（1-5）
- `title` (string, nullable) - タイトル
- `content` (text) - レビュー本文
- `images` (json, nullable) - 画像パス
- `helpful_count` (integer, default: 0) - 役に立った数
- `status` (string, default: 'pending') - ステータス（pending, approved, rejected）
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `user` (多対1, nullable)
- `customer` (多対1, nullable)
- `shop` (多対1)
- `staff` (多対1, nullable)
- `reservation` (多対1, nullable)

**インデックス**:
- `shop_id`, `status`
- `staff_id`, `status`

---

### 17. review_helpfuls（レビュー評価）
レビューの「役に立った」評価を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `review_id` (bigint, foreign key) - レビューID
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `review` (多対1)
- `user` (多対1, nullable)

**ユニーク制約**:
- `review_id`, `user_id` の組み合わせ

---

### 18. waitlists（キャンセル待ち）
キャンセル待ち情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `menu_id` (bigint, foreign key) - メニューID
- `staff_id` (bigint, foreign key, nullable) - スタイリストID
- `desired_date` (date) - 希望日
- `desired_time` (time) - 希望時間
- `priority` (integer, default: 0) - 優先順位
- `status` (string, default: 'waiting') - ステータス（waiting, notified, cancelled, completed）
- `notified_at` (timestamp, nullable) - 通知日時
- `expires_at` (timestamp, nullable) - 有効期限
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `user` (多対1, nullable)
- `customer` (多対1, nullable)
- `menu` (多対1)
- `staff` (多対1, nullable)

**インデックス**:
- `shop_id`, `desired_date`, `status`
- `status`, `priority`

---

### 19. counseling_sheets（カウンセリングシート）
カウンセリング情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `desired_style` (text, nullable) - 希望スタイル（ショート、ボブ、ロング、希望の長さなど）
- `color_preference` (text, nullable) - カラー希望（明るく、暗く、トーンなど）
- `perm_preference` (text, nullable) - パーマ希望（ストレートパーマ、縮毛矯正など）
- `concerns` (text, nullable) - 悩み・要望（ボリュームアップ、くせ毛の悩みなど）
- `previous_treatments` (json, nullable) - 過去の施術歴（前回のカット、カラー、パーマなど）
- `allergies` (json, nullable) - アレルギー情報（カラー剤、パーマ剤など）
- `staff_notes` (text, nullable) - スタイリストの記録
- `treatment_notes` (text, nullable) - 施術内容の記録（カットの長さ、カラーの色番、パーマの種類、使用したカラー剤・パーマ剤など）
- `next_recommendations` (text, nullable) - 次回の提案（次回のカット周期、カラー提案など）
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `reservation` (多対1, nullable)
- `customer` (多対1, nullable)
- `user` (多対1, nullable)

---

### 20. treatment_photos（施術写真）
施術前後の写真を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `photo_type` (string) - 写真タイプ（cut_before, cut_after, color_before, color_after, perm_before, perm_afterなど）
- `image_path` (string) - 画像パス
- `description` (text, nullable) - 説明
- `taken_at` (timestamp, nullable) - 撮影日時
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `reservation` (多対1, nullable)
- `customer` (多対1, nullable)
- `user` (多対1, nullable)

---

### 21. inventory_items（在庫アイテム）
在庫アイテムを管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `name` (string) - アイテム名
- `category` (string, nullable) - カテゴリー（color_agent, perm_agent, shampoo, treatment, otherなど）
- `unit` (string, nullable) - 単位（本、個など）
- `current_stock` (integer, default: 0) - 現在の在庫数
- `min_stock` (integer, nullable) - 最小在庫数（アラート用）
- `unit_price` (decimal, nullable) - 単価
- `supplier` (string, nullable) - 仕入先
- `notes` (text, nullable) - 備考
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `inventory_transactions` (1対多)

---

### 22. inventory_transactions（在庫取引）
在庫の入出荷を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `inventory_item_id` (bigint, foreign key) - 在庫アイテムID
- `reservation_id` (bigint, foreign key, nullable) - 予約ID（使用時）
- `type` (string) - タイプ（in, out, adjustment）
- `quantity` (integer) - 数量
- `unit_price` (decimal, nullable) - 単価
- `total_amount` (decimal, nullable) - 合計金額
- `notes` (text, nullable) - 備考
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `inventory_item` (多対1)
- `reservation` (多対1, nullable)

---

### 23. sales（売上）
売上情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `shop_id` (bigint, foreign key) - 店舗ID
- `reservation_id` (bigint, foreign key, nullable) - 予約ID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `staff_id` (bigint, foreign key, nullable) - スタイリストID
- `menu_id` (bigint, foreign key, nullable) - メニューID
- `amount` (decimal) - 売上金額
- `discount_amount` (decimal, default: 0) - 割引額
- `point_used` (integer, default: 0) - 使用ポイント
- `coupon_discount` (decimal, default: 0) - クーポン割引額
- `material_cost` (decimal, nullable) - 材料費
- `profit` (decimal, nullable) - 利益
- `payment_method` (string, nullable) - 支払い方法
- `sale_date` (date) - 売上日
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `shop` (多対1)
- `reservation` (多対1, nullable)
- `customer` (多対1, nullable)
- `staff` (多対1, nullable)
- `menu` (多対1, nullable)

**インデックス**:
- `shop_id`, `sale_date`
- `staff_id`, `sale_date`
- `menu_id`, `sale_date`

---

### 24. staff_performances（スタッフ実績）
スタッフの実績を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `staff_id` (bigint, foreign key) - スタイリストID
- `shop_id` (bigint, foreign key) - 店舗ID
- `period_type` (string) - 期間タイプ（daily, monthly, yearly）
- `period_start` (date) - 期間開始日
- `period_end` (date) - 期間終了日
- `reservation_count` (integer, default: 0) - 予約数
- `total_sales` (decimal, default: 0) - 総売上
- `average_rating` (decimal, nullable) - 平均評価
- `review_count` (integer, default: 0) - レビュー数
- `work_hours` (decimal, default: 0) - 稼働時間
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `staff` (多対1)
- `shop` (多対1)

**インデックス**:
- `staff_id`, `period_type`, `period_start`
- `shop_id`, `period_type`, `period_start`

---

### 25. notifications（通知）
通知情報を管理するテーブル

**カラム**:
- `id` (bigint, primary key)
- `user_id` (bigint, foreign key, nullable) - ユーザーID
- `customer_id` (bigint, foreign key, nullable) - 顧客ID
- `type` (string) - 通知タイプ（reservation, point, coupon, reviewなど）
- `title` (string) - タイトル
- `message` (text) - メッセージ
- `related_id` (bigint, nullable) - 関連ID（予約ID、クーポンIDなど）
- `related_type` (string, nullable) - 関連タイプ
- `is_read` (boolean, default: false) - 既読状態
- `read_at` (timestamp, nullable) - 既読日時
- `created_at` (timestamp)
- `updated_at` (timestamp)

**リレーション**:
- `user` (多対1, nullable)
- `customer` (多対1, nullable)

**インデックス**:
- `user_id`, `is_read`
- `customer_id`, `is_read`
- `type`, `created_at`

---

## 更新されたリレーションシップ図

```
users
  ├── customer (1対1, オプション) … 顧客プロフィール
  ├── reservations (1対多)
  ├── favorite_shops (多対多)
  ├── reviews (1対多)
  └── notifications (1対多)

customers
  ├── user (1対1)
  ├── reservations (1対多)
  ├── visit_histories (1対多)
  ├── points (1対多) … 予約ごとの付与・使用
  └── reviews (1対多)

shops
  ├── menus (1対多)
  ├── staff (1対多)
  ├── reservations (1対多)
  ├── shifts (1対多)
  ├── coupons (1対多)
  ├── reviews (1対多)
  ├── waitlists (1対多)
  ├── inventory_items (1対多)
  ├── sales (1対多)
  └── shop_managers (多対多)

reservations
  ├── shop (多対1)
  ├── user (多対1, オプション)
  ├── customer (多対1, オプション)
  ├── menu (多対1)
  ├── staff (多対1, オプション)
  ├── visit_histories (1対1, オプション)
  ├── counseling_sheets (1対1, オプション)
  ├── treatment_photos (1対多)
  ├── points (1対多) … 当該予約での付与・使用
  └── sales (1対1, オプション)

staff
  ├── shop (多対1)
  ├── user (多対1, オプション)
  ├── reservations (1対多)
  ├── shifts (1対多)
  ├── menus (多対多)
  ├── reviews (1対多)
  ├── sales (1対多)
  └── staff_performances (1対多)
```

---

## 追加のインデックス戦略

### 新規追加テーブルのインデックス
1. **customers テーブル**
   - `user_id` (unique) - ユーザーとの1対1紐付け

2. **points テーブル**
   - `customer_id`, `type` - 顧客ごとのポイント履歴検索
   - `reservation_id` - 予約ごとの付与・使用検索
   - `expires_at` - 有効期限切れポイント検索

3. **coupons テーブル**
   - `shop_id`, `is_active`, `expires_at` - 有効クーポン検索
   - `code` - クーポンコード検索

4. **reviews テーブル**
   - `shop_id`, `status` - 承認済みレビュー検索
   - `staff_id`, `status` - スタッフレビュー検索

5. **waitlists テーブル**
   - `shop_id`, `desired_date`, `status` - キャンセル待ち検索
   - `status`, `priority` - 優先順位検索

6. **sales テーブル**
   - `shop_id`, `sale_date` - 日次売上検索
   - `staff_id`, `sale_date` - スタッフ売上検索

---

## 将来の拡張性

### 検討中の追加テーブル
- `shop_categories` - 店舗カテゴリー
- `menu_categories` - メニューカテゴリー
- `payments` - 決済情報（詳細）
- `holidays` - 店舗の休業日管理
- `point_rules` - ポイント付与ルール設定（予約・来店時の付与率など）
- `campaigns` - キャンペーン管理
