# 店舗画像 登録・更新 パラメータ設計

## 制約

- 画像枚数: **最小 1 枚・最大 5 枚**
- 新規: 常に画像ファイル（1〜5 枚）をアップロード
- 更新: 既存画像の「残す／削除」＋ 新規ファイルの追加で、最終 1〜5 枚にする

---

## 更新（PATCH）時のパラメータ設計

### 送信したいことの整理

| 操作           | 内容                         |
|----------------|------------------------------|
| 既存画像を残す | どの ID を残すか             |
| 既存画像を消す | 残さない = 削除対象          |
| 新規追加       | 新規アップロードするファイル |

「残す ID」を送るか「削除する ID」を送るかで設計が分かれる。

---

### 案 A: 残す既存 ID ＋ 新規ファイル（推奨）

| パラメータ       | 型        | 必須 | 説明 |
|------------------|-----------|------|------|
| `keepImageIds`   | `number[]`| 任意 | 残す既存画像の `shop_images.id`。空 or 省略 = 既存は全て削除。 |
| `newImages`      | `File[]`  | 任意 | 新規アップロードする画像ファイル。 |

**ルール**

- 最終枚数 = `len(keepImageIds) + len(newImages)` が **1 以上 5 以下** であること。
- `keepImageIds` の ID はすべて「当該 shop に紐づく既存画像」であること（他店舗・存在しない ID は 400）。

**メリット**

- フロント: 「残す」だけ送ればよく、削除は「送らなければ削除」で表現できる。
- バック: 「keep に無い既存 = 削除」で一括削除しやすく、バリデーションも単純。

**送信例（Laravel/Inertia 想定）**

- 既存 3 枚のうち 1, 3 番を残し、新規 1 枚追加  
  `keepImageIds[]=1&keepImageIds[]=3` ＋ `newImages[]=<file>`
- 既存は全部消して新規 2 枚  
  `keepImageIds` なし（または `[]`）＋ `newImages[]=<file1>&newImages[]=<file2>`
- 既存 2 枚そのまま、変更なし  
  `keepImageIds[]=1&keepImageIds[]=2` ＋ `newImages` なし

---

### 案 B: 削除する ID ＋ 新規ファイル

| パラメータ       | 型        | 必須 | 説明 |
|------------------|-----------|------|------|
| `deleteImageIds` | `number[]`| 任意 | 削除する既存画像の `shop_images.id`。 |
| `newImages`      | `File[]`  | 任意 | 新規アップロードする画像。 |

**ルール**

- 最終枚数 = (現在の枚数 − len(deleteImageIds)) + len(newImages) が **1 以上 5 以下**。
- `deleteImageIds` はすべて当該 shop の既存画像 ID であること。

**メリット**

- 「削除するもの」が明示的。
**デメリット**

- 変更なしのときは「何も送らない」でよいが、「全削除して新規だけ」のときは delete に全 ID を列挙する必要がある。

---

### 案 C: 最終状態を「残す ID ＋ 新規の順」で表現（表示順も扱う場合）

| パラメータ   | 型           | 必須 | 説明 |
|--------------|--------------|------|------|
| `imageOrder` | `(number \| 'new')[]` | 任意 | 最終的な並び。既存は ID、新規は `'new'` などで表現。長さ 1〜5。 |
| `newImages`  | `File[]`     | 任意 | 新規ファイル。`imageOrder` に `'new'` が N 個なら N 枚対応。 |

- 表示順を厳密にしたい場合や、将来 `sort_order` カラムを入れる場合に向く。
- 実装・バリデーションはやや重い。

---

## 推奨: 案 A（keepImageIds + newImages）

- 更新は「残す ID ＋ 新規ファイル」で設計する。
- リクエスト例:
  - 既存はそのまま 2 枚、新規 1 枚追加 → `keepImageIds: [1, 2]`, `newImages: [File]`
  - 既存 1 枚削除、新規 2 枚追加 → `keepImageIds: [1]`, `newImages: [File, File]`
  - 全差し替え（既存 0、新規 3 枚）→ `keepImageIds: []`, `newImages: [File, File, File]`
  - 変更なし（既存 3 枚のまま）→ `keepImageIds: [1, 2, 3]`, `newImages: []`

---

## 新規（POST）時のパラメータ（参考）

- `images: File[]` のみ。枚数 1〜5 でバリデーション。
- 更新と名前を揃えるなら `newImages` でもよいが、新規は「画像だけ」なので `images` の方が分かりやすい可能性あり。

---

## 編集画面で渡す既存画像（GET 用）

- `PrepareShopEditFormUseCase` で `shop.images` を返す想定。
- 各要素の例: `{ id, filePath (または url), filename, mimeType, fileSize }`  
  - 表示用 URL は `file_path` から生成するか、アクセサで `url` を付与する。

---

## バリデーション（更新時・案 A）

- `keepImageIds`: 任意、配列、各要素 integer、当該 shop に属する ID のみ許可。
- `newImages`: 任意、ファイル配列、画像 MIME、枚数制限（例: 各 2MB、5 枚まで）。
- 全体: `count(keepImageIds) + count(newImages)` が 1 以上 5 以下。

以上、設計のみ（実装は未反映）。
