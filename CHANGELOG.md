# Yii Widget Change Log

## 2.2.1 under development

- Chg #111: Change PHP constraint in `composer.json` to `8.0 - 8.4` (@vjik)

## 2.2.0 December 25, 2023

- Chg #99: Mark `WidgetFactoryInitializationException` as deprecated (@vjik)
- Enh #98: Add ability to set default theme for concrete widget (@vjik)
- Enh #99: Allow to use widgets without widget factory initialization (@vjik)

## 2.1.0 November 16, 2023

- Enh #84, #87: Add protected method `Widget::getThemeConfig()` that allows to implement a logic of configuring
  a theme (@vjik)
- Enh #87: Add widget themes (@vjik)
- Bug #83: Fix merge constructor arguments with array definition configuration into `Widget::widget()` (@vjik)

## 2.0.0 January 22, 2023

- Chg #72, #80: Change `Widget::widget()` method to using constructor arguments only or whole array definition (@vjik)
- Chg #74: Remove from abstract `Widget` class methods `beforeRun()`, `afterRun()` and `run()`, use `render()` only
  instead it (@vjik)

## 1.1.0 November 08, 2022

- Enh #26: Add support for `yiisoft/definitions` version `^3.0` (@vjik)
- Enh #58: Raise minimum PHP version to `^8.0` and minor refactor code (@xepozz, @vjik)
- Enh #61: Add support for `yiisoft/html` version `^3.0` (@vjik)
- Bug #62: Fix typo in `WidgetFactoryInitializationException` solution (@vjik)

## 1.0.1 June 17, 2022

- Enh #55: Add support for `yiisoft/definitions` version `^2.0` (@vjik)
- Bug #51: Fix solution description to `WidgetFactoryInitializationException` (@devanych)

## 1.0.0 December 12, 2021

- Initial release.
