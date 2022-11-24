1. 复制一份 `.env.example` 为 `.env`, 并修改配置，如数据库/SERVER_URL/APP_KEFU/APP_NAME等。不要把`.env`加入到版本库！
2. composer install 安装依赖包。`composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/`   `composer self-update`     `composer install`
3. 执行安装脚本 `php artisan aam:install`
4. 权限设置 `chmod -R 766 storage/ && chmod -R 766 bootstrap/cache/`
