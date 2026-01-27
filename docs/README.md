# 美容室サロン予約システム ドキュメント

## ドキュメント一覧

### ナレッジ (knowledge/)
技術的な概念やプロジェクト内の実装の解説です。

- [PHP Enum 入門ガイド](./knowledge/php-enum-guide.md) — Enum の `cases()` / `::class` の違い、`match` と `$this`、HasOptions の `description` の説明

### 仕様書 (specification/)
1. [プロジェクト概要](./specification/01-overview.md)
2. [要件定義](./specification/02-requirements.md)
3. [ロールと権限](./specification/03-roles-and-permissions.md)
4. [機能一覧](./specification/04-features.md)
5. [技術スタック](./specification/05-technology-stack.md)
6. [データベース設計](./specification/06-database-design.md)

## 主要機能

- **予約管理**: 予約の作成・管理・キャンセル待ち機能
- **顧客管理**: 顧客カード、来店履歴、髪質・希望スタイルの記録
- **ポイント・クーポン**: ポイント付与、クーポン発行、会員ランク制度
- **レビュー・評価**: 施術後のレビュー投稿・閲覧
- **カウンセリング**: カウンセリングシートによる情報管理（髪質、カラー希望など）
- **施術写真**: カット前後・カラー前後の写真管理・共有
- **在庫管理**: カラー剤・パーマ剤・シャンプーなどの在庫管理
- **売上管理**: 売上集計・分析・レポート
- **スタイリスト実績**: スタイリストの実績・評価管理

## プロジェクト情報

- **プロジェクト名**: 美容室サロン予約システム
- **フレームワーク**: Laravel 12
- **フロントエンド**: Vue.js + Inertia.js
- **認証・権限管理**: Laravel Permission
- **ログ管理**: Laravel Activity Log
