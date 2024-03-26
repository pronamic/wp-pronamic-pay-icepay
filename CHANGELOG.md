# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

## [4.3.7] - 2024-03-26

### Commits

- ncu -u ([47a493e](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/47a493ecdf163dcee1fba2df646b3b289c59ed53))
- Updated .gitattributes ([73dc8cc](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/73dc8cc900245b35dc8abcbf2e585c0b099d3c56))

Full set of changes: [`4.3.6...4.3.7`][4.3.7]

[4.3.7]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.6...v4.3.7

## [4.3.6] - 2023-10-13

### Commits

- The default `sanitize_text_field` function allows double quotes. ([3d788e2](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/3d788e2ded388a76df8642faafc0217f8741aa60))
- No longer use `Server::get`, method will be removed. ([f641da3](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/f641da3081a11d135be15b496d86ad5f55eef438))

### Composer

- Changed `wp-pay/core` from `^4.6` to `v4.13.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.13.0

Full set of changes: [`4.3.5...4.3.6`][4.3.6]

[4.3.6]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.5...v4.3.6

## [4.3.5] - 2023-07-12

### Commits

- Updated for removed payment ID fallback in formatted payment string (pronamic/wp-pronamic-pay-adyen#23). ([b74cb45](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/b74cb45369ca7b51871f63938ad56d1af56007e5))

Full set of changes: [`4.3.4...4.3.5`][4.3.5]

[4.3.5]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.4...v4.3.5

## [4.3.4] - 2023-06-01

### Commits

- Switch from `pronamic/wp-deployer` to `pronamic/pronamic-cli`. ([b7bee4d](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/b7bee4da912433063d213c22e1f265ef9432fef5))
- Updated .gitattributes ([fbb559b](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/fbb559b1c09128364cfc71d8cfbcf73361e56e0d))

Full set of changes: [`4.3.3...4.3.4`][4.3.4]

[4.3.4]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.3...v4.3.4

## [4.3.3] - 2023-03-27

### Commits

- Set Composer type to `wordpress-plugin`. ([0b26a9c](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/0b26a9c30714dec9ebb4581893a35e8c66dd4605))
- Updated .gitattributes ([4bf0f32](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/4bf0f3215ac9f6287eb61d55934eb0989706af31))

Full set of changes: [`4.3.2...4.3.3`][4.3.3]

[4.3.3]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.2...v4.3.3

## [4.3.2] - 2023-02-07
### Commits

- Added support for bank transfer, Giropay and PayPal payment methods (resolves #6). ([5eb45c3](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/5eb45c37bddb93dff945362d3cb85cb533ede349))
- Fixed credit card error "Issuer not supported" for some integrations. ([0935a60](https://github.com/pronamic/wp-pronamic-pay-icepay/commit/0935a60ec3a0fcc7a1cc2e5fc68771fd1d045694))

Full set of changes: [`4.3.1...4.3.2`][4.3.2]

[4.3.2]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.1...v4.3.2

## [4.3.1] - 2023-01-31
### Composer

- Changed `php` from `>=8.0` to `>=7.4`.
Full set of changes: [`4.3.0...4.3.1`][4.3.1]

[4.3.1]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.3.0...v4.3.1

## [4.3.0] - 2022-12-22

### Composer
- Changed `php` from `>=5.6.20` to `>=8.0`.
- Changed `wp-pay/core` from `^4.0` to `v4.6.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.2.0

Full set of changes: [`4.2.0...4.3.0`][4.3.0]

[4.3.0]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/v4.2.0...v4.3.0

## [4.2.0] - 2022-09-26
- Updated payment methods registration.

## [4.1.1] - 2022-08-15
- Switched to new ICEPAY repository from Pronamic.

## [4.1.0] - 2022-04-11
- Remove gateway error usage, exception should be handled downstream.

## [4.0.0] - 2022-01-11
### Changed
- Updated to https://github.com/pronamic/wp-pay-core/releases/tag/4.0.0.
- Update setting country on payment start ([pronamic/wp-pronamic-pay#275](https://github.com/pronamic/wp-pronamic-pay/issues/275)).

## [3.0.0] - 2021-08-05
- Updated to `pronamic/wp-pay-core`  version `3.0.0`.
- Updated to `pronamic/wp-money`  version `2.0.0`.
- Switched to `pronamic/wp-coding-standards`.

## [2.1.1] - 2021-04-26
- Happy 2021.

## [2.1.0] - 2020-03-19
- Fixed "$result is always a sub-type of Icepay_Result".
- Extend from AbstractGatewayIntegration class.

## [2.0.6] - 2019-12-22
- Added URL to manual in gateway settings.
- Fixed processing ICEPAY postback.
- Updated usage of deprecated `get_cents()` method.

## [2.0.5] - 2019-10-04
- Added support for Klarna (Directebank) payment method.
- Update ICEPAY library version from 2.4.0 to 2.5.3.

## [2.0.4] - 2019-08-27
- Updated packages.

## [2.0.3] - 2019-05-15
- Set country from billing address.

## [2.0.2] - 2019-01-24
- Improved setting required country and language.

## [2.0.1] - 2018-12-12
- Fixed "Fatal error: Uncaught Exception: MerchantID not valid" in test meta box.
- Use issuer field from core gateway.
- Updated deprecated function calls.

## [2.0.0] - 2018-05-09
- Switched to PHP namespaces.

## [1.3.1] - 2017-12-12
- Set payment success and error return URLs.

## [1.3.0] - 2017-02-08
- Added order ID setting.

## [1.2.9] - 2016-10-20
- Added support for new Bancontact constant.

## [1.2.8] - 2016-06-08
- Simplified the gateway payment start function.

## [1.2.7] - 2016-03-22
- Added product URL.
- Updated gateway settings.

## [1.2.6] - 2016-03-02
- Fixed fatal error in case of ICEPAY merchants that not have the iDEAL payment method activated.
- Added get settings function.

## [1.2.5] - 2016-02-01
- Added an gateway settings class.

## [1.2.4] - 2015-10-19
- Fixed fatal error with wrong constant usage.

## [1.2.3] - 2015-10-19
- Only return the iDEAL issuers field if the payment method is iDEAL.

## [1.2.2] - 2015-10-14
- Make sure to use language and country values from payment data object.

## [1.2.1] - 2015-03-03
- Changed WordPress pay core library requirement from `~1.0.0` to `>=1.0.0`.

## [1.2.0] - 2015-02-16
- Added support for multiple payment methods.

## [1.1.0] - 2015-02-16
- Require the icepay/icepay library version 2.4.0.
- Improved the ICEPAY listener condition.

## 1.0.0 - 2015-01-19
- First release.

[unreleased]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/4.2.0...HEAD
[4.2.0]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/4.1.1...4.2.0
[4.1.1]: https://github.com/pronamic/wp-pronamic-pay-icepay/compare/4.1.0...4.1.1
[4.1.0]: https://github.com/wp-pay-gateways/icepay/compare/4.0.0...4.1.0
[4.0.0]: https://github.com/wp-pay-gateways/icepay/compare/3.0.0...4.0.0
[3.0.0]: https://github.com/wp-pay-gateways/icepay/compare/2.1.0...3.0.0
[2.1.1]: https://github.com/wp-pay-gateways/icepay/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/wp-pay-gateways/icepay/compare/2.0.6...2.1.0
[2.0.6]: https://github.com/wp-pay-gateways/icepay/compare/2.0.5...2.0.6
[2.0.5]: https://github.com/wp-pay-gateways/icepay/compare/2.0.4...2.0.5
[2.0.4]: https://github.com/wp-pay-gateways/icepay/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/wp-pay-gateways/icepay/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/wp-pay-gateways/icepay/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/wp-pay-gateways/icepay/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/wp-pay-gateways/icepay/compare/1.3.1...2.0.0
[1.3.1]: https://github.com/wp-pay-gateways/icepay/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/wp-pay-gateways/icepay/compare/1.2.9...1.3.0
[1.2.9]: https://github.com/wp-pay-gateways/icepay/compare/1.2.8...1.2.9
[1.2.8]: https://github.com/wp-pay-gateways/icepay/compare/1.2.7...1.2.8
[1.2.7]: https://github.com/wp-pay-gateways/icepay/compare/1.2.6...1.2.7
[1.2.6]: https://github.com/wp-pay-gateways/icepay/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/wp-pay-gateways/icepay/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/wp-pay-gateways/icepay/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/wp-pay-gateways/icepay/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/wp-pay-gateways/icepay/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/wp-pay-gateways/icepay/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/wp-pay-gateways/icepay/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/wp-pay-gateways/icepay/compare/1.0.0...1.1.0
