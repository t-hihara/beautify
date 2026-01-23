# 技術スタック

## バックエンド

### フレームワーク
- **Laravel 12**
  - PHP 8.2以上
  - MVCアーキテクチャ
  - Eloquent ORM
  - ルーティング、ミドルウェア、バリデーション

### 認証・権限管理
- **Laravel Permission** (spatie/laravel-permission)
  - ロールベースのアクセス制御（RBAC）
  - 権限の細かい管理
  - ポリシーとの連携

### ログ管理
- **Laravel Activity Log** (spatie/laravel-activitylog)
  - モデル変更の自動記録
  - カスタムアクティビティログ
  - ログの検索・フィルタリング

### その他の主要パッケージ
- **Laravel Sail**: Docker開発環境
- **Laravel Tinker**: コマンドラインでのデバッグ
- **Laravel Pint**: コードスタイルの統一

---

## フロントエンド

### フレームワーク
- **Vue.js 3**
  - Composition API
  - リアクティブなUI構築
  - コンポーネントベースの開発

### 統合フレームワーク
- **Inertia.js**
  - LaravelとVue.jsの統合
  - SPA的な体験を提供
  - サーバーサイドルーティングとの連携

### ビルドツール
- **Vite**
  - 高速な開発サーバー
  - ホットモジュールリプレースメント（HMR）
  - 本番環境での最適化されたビルド

---

## データベース

### RDBMS
- **MySQL 8.0** または **PostgreSQL 14+**
  - トランザクション対応
  - 外部キー制約
  - インデックス最適化

### マイグレーション
- Laravel Migrations
  - バージョン管理
  - ロールバック機能

---

## 開発環境

### コンテナ化
- **Docker**
  - Laravel Sailによる統合環境
  - 一貫した開発環境の提供

### バージョン管理
- **Git**
  - ソースコードのバージョン管理

---

## その他の技術・ツール

### メール送信
- Laravel Mail
  - SMTP設定
  - メールテンプレート（Blade）

### ファイルストレージ
- Laravel Filesystem
  - ローカルストレージ
  - S3連携（オプション）

### テスト
- **PHPUnit**
  - ユニットテスト
  - フィーチャーテスト

### コード品質
- **Laravel Pint**
  - PSR-12準拠のコードスタイル

---

## パッケージ構成（予定）

### 必須パッケージ
```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-activitylog": "^4.0",
    "inertiajs/inertia-laravel": "^1.0"
  },
  "require-dev": {
    "laravel/sail": "^1.52",
    "laravel/pint": "^1.24",
    "phpunit/phpunit": "^11.5.3"
  }
}
```

### フロントエンドパッケージ（予定）
```json
{
  "dependencies": {
    "vue": "^3.4.0",
    "@inertiajs/vue3": "^1.0",
    "@inertiajs/progress": "^0.2.7"
  },
  "devDependencies": {
    "@vitejs/plugin-vue": "^5.0.0",
    "vite": "^5.0.0"
  }
}
```

---

## アーキテクチャ

### 設計パターン
- **MVC（Model-View-Controller）**
- **Repository Pattern**（オプション）
- **Service Layer Pattern**（オプション）

### ディレクトリ構造（予定）
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Shop/
│   │   ├── Staff/
│   │   └── User/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Models/
│   ├── Shop.php
│   ├── Menu.php
│   ├── Reservation.php
│   ├── Staff.php
│   └── Shift.php
├── Policies/
├── Services/
└── Providers/

resources/
├── js/
│   ├── Pages/
│   │   ├── Admin/
│   │   ├── Shop/
│   │   ├── Staff/
│   │   └── User/
│   ├── Components/
│   └── app.js
└── css/
```

---

## セキュリティ

### 実装予定のセキュリティ対策
- CSRF保護（Laravel標準）
- XSS対策（Vue.jsの自動エスケープ）
- SQLインジェクション対策（Eloquent ORM）
- パスワードハッシュ化（bcrypt）
- 認証トークンの管理
- 権限チェック（Laravel Permission）

---

## パフォーマンス最適化

### 実装予定の最適化
- データベースクエリの最適化（Eager Loading）
- キャッシュの活用（Redis/Memcached）
- 画像の最適化
- フロントエンドのコード分割
- CDNの活用（オプション）

---

## デプロイメント

### 想定環境
- **本番環境**: VPS、クラウドサーバー（AWS、GCP、Azureなど）
- **Webサーバー**: Nginx または Apache
- **PHP-FPM**: PHP 8.2以上
- **プロセス管理**: Supervisor（キュー処理用）
