# Upgrading Instructions for Yii Widget 

### !!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to following the instructions
for both A and B.

## Upgrade from Yii Widget 1.1.0

* `beforeRun`, `afterRun` and `run` method have been removed from the `Widget` class, and only the `render` method 
  can now be used, so you may need to make some changes. For example:
  ```php
  protected function beforeRun(): bool
  {
      //...
      return true;
  }
  
  protected function afterRun(string $result): string
  {
      //...
      return $result;
  }
  
  protected function run(): string
  {
       //...
       return $content;
  }
  ```
  will be:
  ```php
  public function render(): string|\Stringable
  {
      // "beforeRun" stuff
      // "run" stuff
      // "afterRun" stuff
      return $result;
  }
  ```
