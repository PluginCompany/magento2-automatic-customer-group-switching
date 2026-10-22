# Changelog: Automatic Customer Group Switching for Magento 2

All releases of this extension, newest first. The released versions are commits on `main`, each tagged with its version number (e.g. [`100.1.17`](https://github.com/PluginCompany/magento2-automatic-customer-group-switching/releases/tag/100.1.17)); Composer installs these tags.

## 100.1.17 — 2026-10-23

- PHP 8.1–8.3 compatibility: declared the `$periodElementFactory` and `$config` properties of the condition plugin (`Plugin/Model/Condition/AbstractConditionPlugin.php`); dynamic properties are deprecated since PHP 8.2.
- PHP 8.1–8.3 compatibility: empty values are no longer passed as `null` to `explode()`, `stristr()`, `str_replace()`, `strtotime()` or `new DateTime()` (deprecated since PHP 8.1). This affected rules with an empty customer group, event or order status field, saving conditions without a from/to date, date conditions without a value, customers without a creation date, and every notification e-mail (no BCC address is set, which made sending fail).
- `composer.json` now allows PHP 8.0 – 8.3.
- Verified by static analysis (PHPCompatibility, php -l on PHP 7.4, 8.1 and 8.3); not run on a live store.

## 100.1.16 — 2021-07-02

- Fixed issue which caused the "to date" to be parsed incorrectly when using a period range condition

## 100.1.15 — 2021-05-28

- Added support for BSS Commerce radio button custom customer attribute

## 100.1.14 — 2021-03-31

- Workaround for inconsistency between regular and API customer login event. Fixes issues with PWA based front-end customer login.
- Improved error handling to prevent functionality breaking on front-end when an error occurs.

## 100.1.13 — 2020-11-25

- Fixed issue causing group switching rule from / to date to be saved incorrectly when Magento admin locale date format is different from US locale

## 100.1.12 — 2020-08-24

- Fixed customer group switch notification e-mail sending issue for Magento 2.4.x
- Fix for strict template filtering enforcement in Magento 2.4.x
- Design improvement: E-mail notifications now use front-end header and footer design templates
- Verified compatibility with Magento 2.4 and PHP 7.4

## 100.1.11 — 2020-02-14

- Fixed admin listing naming conflict with Mirasvit RMA module (and possibly other extensions)

## 100.1.10 — 2019-07-27

- Fixed issue regarding "Ordered Products (new order)" condition type

## 100.1.9 — 2019-07-22

- Fixed issue when creating new group switching rule in Magento 2.1.x with PHP 5.6.x

## 100.1.8 — 2019-07-20

- Fixed issue regarding Store View filter when executing rules

## 100.1.7 — 2019-04-15

- Updated license manager dependency in composer.json
- Updated license config for extension

## 100.1.6 — 2019-04-05

- Magento 2.3.1 compatibility fixes
- Fixed customer group switched notification e-mail sending issue in Magento 2.3.x
- Fixed compatibility with new Magento 2.3.1 Page Builder module
- Fixed invoice totals validation issue in Magento 2.3.x
- Fixed Magento 2.3 Catalog Rule compatibility bug

## 100.1.5 — 2018-09-17

- License Manager update

## 100.1.4 — 2018-06-22

- Fixed issue which occurred some times when "between dates" type rule is used

## 100.1.3 — 2018-06-22

- Bug fix for issue which caused an error in some cases when ordering from the front-end

## 100.1.2 — 2017-11-14

- Fixed from group bug which was introduced in version 100.1.0

## 100.1.1 — 2017-10-12

- Code optimization and improvements

## 100.1.0 — 2017-09-29

- Magento 2.2 compatibility
- Added payment method to order history conditions
- Shipping method condition is now select option
- Magento core bug workaround on customer save event
- E-mail notification bug fix

## 100.0.0 — 2017-09-20

- First release of Magento 2 Automatic Customer Group Switching extension by Plugin Company.
