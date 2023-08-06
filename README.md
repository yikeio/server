# 一刻

此项目为 <https://yike.io> 服务端源码，项目基于 Laravel 10 开发。

> **Warning**
> 本项目当前版本出自开发者的业余时间，可能存在一些问题，如果你发现了任何问题，请提交 PR。本项目开源仅出于学习交流目的，不建议直接用于生产环境，不提供任何解答咨询服务。

## 项目源码

- [yikeio/app](https://github.com/yikeio/app) - 前端源码
- [yikeio/server](https://github.com/yikeio/server) - 服务端源码
- [yikeio/dashboard](https://github.com/yikeio/dashboard) - 管理后台源码

## 技术栈

- openai-php/client - OpenAI API 客户端
- laravel/passport - 用户认证
- overtrue/easy-sms - 短信验证码
- overtrue/socialite - 第三方登录
- overtrue/laravel-like - 点赞
- payjs.cn - 微信支付
- 微软云 Azure OpenAI - OpenAI 国内接口

## 编译安装步骤

1. 安装依赖

    ```bash
    composer install
    ```

1. 配置

    拷贝创建 `.env` 文件：

    ```bash
    cp .env.example .env
    ```

    修改 `.env` 文件中的各项配置，包括但不限于：

    - `APP_URL` - 项目 URL
    - `DB_*` - 数据库配置
    - `OPENAI_*` - OpenAI 配置
    - `PAYJS_*` - 微信支付配置（如没有接入，请自行二开实现其他支付渠道）
    - `SMS_*` - 短信验证码配置

// todo @ranpro 补充

## 贡献

欢迎任何形式的贡献，包括但不限于提交问题、需求、功能、文档、测试用例、演示等。

## 合作

如果你希望在此项目上合作或付费技术支持，请联系我们：<anzhengchao@gmail.com>。

## 核心团队

- [@overtrue](https://github.com/overtrue) - 前端开发者，后端开发者
- [@ranpro](https://github.com/ranpro) - 后端开发者
- [@PengYYYYY](https://github.com/PengYYYYY) - 前端开发者
- [@honkinglin](https://github.com/honkinglin) - 前端开发者
- [@xixileng](https://github.com/xixileng) - 前端开发者

## License

Licensed under the [MIT license](https://github.com/yikeio/app/blob/main/LICENSE.md).
