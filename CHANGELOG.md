# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [3.0.0](https://github.com/dansmaculotte/laravel-mail-template/compare/v2.0.0...v3.0.0) (2020-11-30)


### ⚠ BREAKING CHANGES

* drop support of Illuminate/Support 6

### Features

* provide a debug email address to get template error reports ([4c94fc3](https://github.com/dansmaculotte/laravel-mail-template/commit/4c94fc30dce612f00e50d88843427cbcce77df1a))


* update dependencies for Laravel 8 support ([d97a593](https://github.com/dansmaculotte/laravel-mail-template/commit/d97a59314752c91e477264be2604edd3cf39d625))

## [2.0.0](https://github.com/DansMaCulotte/laravel-mail-template/compare/v1.2.1...v2.0.0) (2020-05-06)


### ⚠ BREAKING CHANGES

* drop support of laravel 5

### Features

* allow laravel 7 ([52159ec](https://github.com/DansMaCulotte/laravel-mail-template/commit/52159ec))

### [1.2.1](https://github.com/DansMaCulotte/laravel-mail-template/compare/v1.2.0...v1.2.1) (2020-03-27)

## [1.2.0](https://github.com/DansMaCulotte/laravel-mail-template/compare/v1.1.0...v1.2.0) (2020-03-27)


### Features

* add make method to reset message and body ([636e271](https://github.com/DansMaCulotte/laravel-mail-template/commit/636e271ea5d2921f65ba27d6a606f5517e81bb4f))

## 1.1.0 (2019-09-09)


### Features

* add packages suggest in composer ([e9945f8](https://github.com/DansMaCulotte/laravel-mail-template/commit/e9945f8))
* add support for laravel 6 ([7d3abf0](https://github.com/DansMaCulotte/laravel-mail-template/commit/7d3abf0))

## 1.0.0 (2019-08-19)


### Bug Fixes

* add catch error mailgun missing key and/or domain ([6a1354f](https://github.com/DansMaCulotte/laravel-mail-template/commit/6a1354f))
* add catch error on sending method ([6618e7b](https://github.com/DansMaCulotte/laravel-mail-template/commit/6618e7b))
* add catch error sendinblue missing key ([8923089](https://github.com/DansMaCulotte/laravel-mail-template/commit/8923089))
* add missing dev dependencies for mailgun ([0b89a42](https://github.com/DansMaCulotte/laravel-mail-template/commit/0b89a42))
* fix mailgun facade test ([ef5cc3f](https://github.com/DansMaCulotte/laravel-mail-template/commit/ef5cc3f))
* fix tests typos ([130404b](https://github.com/DansMaCulotte/laravel-mail-template/commit/130404b))
* remove setTemplate param type ([55a5eb2](https://github.com/DansMaCulotte/laravel-mail-template/commit/55a5eb2))
* update sendinblue methods ([499e8f1](https://github.com/DansMaCulotte/laravel-mail-template/commit/499e8f1))


### Features

* add mailgun config ([e2f8e8b](https://github.com/DansMaCulotte/laravel-mail-template/commit/e2f8e8b))
* add mailgun driver ([e8ca33a](https://github.com/DansMaCulotte/laravel-mail-template/commit/e8ca33a))
* add sendgrid driver ([27f2fb5](https://github.com/DansMaCulotte/laravel-mail-template/commit/27f2fb5))
* add sendgrid to service provider ([6f2107f](https://github.com/DansMaCulotte/laravel-mail-template/commit/6f2107f))
* add seninblue driver ([62d9930](https://github.com/DansMaCulotte/laravel-mail-template/commit/62d9930))
* update service provider ([b794e01](https://github.com/DansMaCulotte/laravel-mail-template/commit/b794e01))

## 0.1.0 (2019-06-16)


### Bug Fixes

* **readme:** adjustments ([900230c](https://github.com/DansMaCulotte/laravel-mail-template/commit/900230c))
* adjust mandril and mailjet implementations ([93dacb6](https://github.com/DansMaCulotte/laravel-mail-template/commit/93dacb6))


### Features

* add base files ([0ccb8a3](https://github.com/DansMaCulotte/laravel-mail-template/commit/0ccb8a3))
* add config and implement driver interface for mandrill and mailjet ([379fdaf](https://github.com/DansMaCulotte/laravel-mail-template/commit/379fdaf))
* update namespace, readme and others ([4bdc0ec](https://github.com/DansMaCulotte/laravel-mail-template/commit/4bdc0ec))
