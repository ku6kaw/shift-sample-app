## \#\# 必須環境

このアプリケーションを動かすには、お使いのPC (Windows) に以下のソフトウェアがインストールされている必要があります。

  * **WSL2 (Windows Subsystem for Linux)**
  * **Docker Desktop for Windows**

-----

## \#\# 開発環境の構築手順

WSL (Ubuntu) のターミナルを開き、以下のコマンドを**上から順番に**実行してください。

### \#\#\# 1. プロジェクトのダウンロード

まず、GitHubからプロジェクトのソースコードをダウンロードします。

```bash
git clone https://github.com/ku6kaw/shift-sample-app
```

<br>

### \#\#\# 2. プロジェクトフォルダへの移動

ダウンロードしたプロジェクトのフォルダに移動します。

```bash
cd shift-sample-app
```

<br>

### \#\#\# 3. 設定ファイルの準備

環境設定ファイルのテンプレートをコピーして、自分用の設定ファイルを作成します。

```bash
cp .env.example .env
```

<br>

### \#\#\# 4. 開発環境の起動

Laravel Sailを使って、開発に必要なサーバー（Webサーバー、DBサーバーなど）を起動します。初回は起動に数分かかります。

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd)":/var/www/html \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```


```bash
./vendor/bin/sail up -d
```

*(もし `sail` エイリアスを設定済みの場合は `sail up -d` だけでOKです)*

<br>

### \#\#\# 5. PHP依存パッケージのインストール

プロジェクトが必要とするPHPライブラリをインストールします。

```bash
./vendor/bin/sail composer install
```

<br>

### \#\#\# 6. アプリケーションキーの生成

Laravelアプリケーションに必須の暗号化キーを生成します。

```bash
./vendor/bin/sail php artisan key:generate
```

<br>

### \#\#\# 7. データベースの構築

データベースに必要なテーブルを作成します。

```bash
./vendor/bin/sail php artisan migrate --seed
```

<br>

### \#\#\# 8. JavaScript依存パッケージのインストール

画面の表示で使われるJavaScriptライブラリをインストールします。

```bash
./vendor/bin/sail npm install
```

<br>
これで環境構築は完了です。

-----

## \#\# 開発の始め方

開発作業を始める際は、以下の**2つのコマンド**をそれぞれ別のターミナルで実行してください。

  * **ターミナル1: サーバーの起動**

    ```bash
    ./vendor/bin/sail up -d
    ```

  * **ターミナル2: フロントエンドアセットのビルド**

    ```bash
    ./vendor/bin/sail npm run dev
    ```

両方のコマンドを実行した状態で、ブラウザで `http://localhost` にアクセスすると、アプリケーションが表示されます。

-----

## Render へのデプロイ準備

本リポジトリには Render でそのままビルドできるように Dockerfile を調整済みです。以下のポイントを押さえておくとスムーズにデプロイできます。

### Docker イメージで行っていること

* アプリケーションのソースコードをイメージに含めています (`COPY . .`)。
* `composer install --no-dev --optimize-autoloader` で本番用の PHP 依存関係をインストールします。
* `npm ci` でフロントエンド依存関係を取得し、`npm run build` で Vite ビルド済みアセットを作成しています。
* 不要になった `node_modules` を削除し、`storage` と `bootstrap/cache` の権限を `sail` ユーザーに合わせています。
* `php artisan serve` は Render から渡される `PORT` 環境変数を利用するようにしています。

### Render ダッシュボードでの設定例

1. **New → Web Service** でこのリポジトリを選択します（Dockerfile が自動検出されます）。
2. **Environment** タブで `.env` の内容を登録します。特に `APP_KEY` はローカルで `php artisan key:generate --show` した値を貼り付けてください。
3. `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<サービス名>.onrender.com` のように本番向けの値を設定します。
4. データベースを利用する場合は Render の PostgreSQL を追加し、接続情報を環境変数として入力します。
5. マイグレーションを自動化したい場合は **Deploy → Post-deploy Command** に `php artisan migrate --force` を設定します。

初回デプロイ後は Render のログでビルド／起動状況を確認し、問題があれば依存関係のインストールログを参照してください。
