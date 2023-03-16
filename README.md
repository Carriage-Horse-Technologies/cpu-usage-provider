# cpu-usage-provider
cpu使用率をダンプするPHPスクリプトです。

ChatGPT駆動開発しました。（まあ、結局中身は自分で１から考えました）

（`main.php`や`main2.php`は失敗作です。`main3.php`を使ってください。）

## 前提
以下を実行できる環境である必要があります

- php4以上
- vmstat
- awk

## 実行方法

```bash
php main3.php
```

## 結果
実行すると下記のような結果が返却されます。

```json
{"cpuutilization":0}
```

サーバーに負荷がかかると`cpuutilization`が`0~100`で変化します。

## 仕組み

vmstatをawkで整形したするコマンドをPHPでexecしてJSONとしてechoしているだけです！

```php
<?php

$cpu_usage = shell_exec("echo $(vmstat 1 2 | awk 'NR>3{ print 100 - $15 }')");

header('Content-Type: application/json');

echo json_encode([
  'cpuutilization' => floatval($cpu_usage)
]);
```

### 配信方法
PHPのビルトインサーバーで配信できます。

```bash
php -S 0.0.0.0:8080 main3.php
```

ハッカソンではNginxをリバースプロキシとして設定して配信していました。

```
server {
    server_name legacy-stack.yukinissie.com;

    location /metrics/ {
        proxy_pass    http://localhost:8080/;
    }
}
```


---

以下は「centosのCPU使用率をHTTP経由で公開するAPIをどうすれば作れますか」から複数やり取りをした後に「上記プログラムをsystemdで起動できるようにしてください」と質問した際のChatGPTの回答です。

systemdを使用して上記のPHPスクリプトをサービスとして実行することができます。以下の手順に従って、サービスを作成して、CentOSの起動時に自動的に開始するようにします。

1. サービスファイルを作成します。

`/etc/systemd/system`ディレクトリに、`cpu_usage.service`という名前のファイルを作成します。

```bash
sudo vi /etc/systemd/system/cpu_usage.service
```

以下の内容をファイルに追記します。

```makefile
[Unit]
Description=CPU usage API

[Service]
ExecStart=/usr/bin/php -S 0.0.0.0:8080 /path/to/cpu_usage.php
WorkingDirectory=/path/to/
Restart=always
User=nobody

[Install]
WantedBy=multi-user.target
```

`/path/to/`を、PHPスクリプトの場所に置き換えます。

2. サービスを有効化します。

以下のコマンドを使用して、サービスを有効化します。

```bash
sudo systemctl enable cpu_usage.service
```

3. サービスを起動します。

以下のコマンドを使用して、サービスを起動します。

```bash
sudo systemctl start cpu_usage.service
```

4. サービスが正しく動作しているか確認します。

以下のコマンドを使用して、サービスの状態を確認します。

```bash
sudo systemctl status cpu_usage.service
```

サービスが正常に動作している場合、出力に「active (running)」と表示されます。

以上の手順に従って、systemdでCPU使用率APIサービスを作成して、CentOSの起動時に自動的に開始するように設定することができます。


