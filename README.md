# 本アプリの環境構築

## セットアップ手順

1. **リポジトリをクローン**
   ```bash
   git clone <リポジトリURL>
   ```

2. **dockerディレクトリに移動**
   ```bash
   cd docker
   ```

3. **データベースの設定**
   <br>`.env`ファイルを使用する形式にしているため、注意して欲しい。
   ```bash
   cp .env.example .env
   ```
   passwordやroot_passwordを各自設定する。
   <br>例：
   ```.env
   DB_DATABASE=mydb
   DB_USER=fuel_user
   DB_PASSWORD=safe_password
   DB_ROOT_PASSWORD=most_safe_password
   ```

4. **Dockerイメージのビルド**
   ```bash
   docker-compose build
   ```

5. **コンテナの起動**
   ```bash
   docker-compose up -d
   ```
6. **migrationの実行**
   <br>実行しないと立ち上がらないと思われる。
   ```bash
   # dockerコンテナのbashに入る
   docker compose exec app bash

   # 存在する場合。私の環境ではうまく行かなかった。
   # rm -rf my_fuel_project/fuel/app/config/development/migrations.php

   # oilによるマイグレーションを実行(authから)
   php oil refine migrate --packages=auth
   php oil refine migrate
   ```
   014まで完了していたら、成功
7. **ブラウザからlocalhostにアクセス**
   <br>loginを促す画面が出ていたら成功

## PHP周りのバージョン
- **PHP**: 7.3
- **FuelPHP**: 1.8

## ログについて
- **アクセスログ**: Dockerのコンテナのログ
- **FuelPHPのエラーログ**: /var/www/html/intern_kadai/fuel/app/logs/
  - 年月日ごとにログが管理されている
  - tail -f {見たいログファイル}でログを出力

## MySQLコンテナ設定
- **MySQLバージョン**: 8.0
- **ポート**: `3306`
- **環境変数**:
  - `MYSQL_ROOT_PASSWORD`: `.env`ファイルで設定した`DB_ROOT_PASSWORD`の値
  - `MYSQL_DATABASE`: `.env`ファイルで設定した`DB_DATABASE`の値
  - `MYSQL_USER`: `.env`ファイルで設定した`DB_USER`の値
  - `MYSQL_PASSWORD`: `.env`ファイルで設定した`DB_PASSWORD`の値

### アクセス情報
- **ホスト**: `localhost`
- **ポート**: `3306`
- **ユーザー名**: `root`
- **パスワード**: `.env`ファイルで設定した`DB_ROOT_PASSWORD`の値
- **データベース名**: `.env`ファイルで設定した`DB_DATABASE`の値