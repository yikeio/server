## Yike Tech 容器化部署

### 系统

- Debian:stable

### 软件

- Nginx
- PHP 8.1
- Supervisord
- Cron

### 启动

- start-all 单实例模式
- start-container Web 容器
- start-crond 定时容器
- start-queue 队列容器

### 环境变量
- PHP_FPM_PM_MAX_CHILDREN
- LARAVEL_DEFAULT_WORKER_COUNT
