# Yii Widget Upgrading Instructions 

> This file contains the upgrade notes. These notes highlight changes that could break your
> application when you upgrade the package from one version to another.

<div class="alert alert-warning" style="background-color: #e2a03f14;display: flex;padding: 1rem 1rem;margin-bottom: 1rem;border: none;border-left: 3px solid #e2a03f;border-radius: 4px;"><span style="color:red; font-weight: bold;"> **!!! IMPORTANT !!!**<br>The following upgrading instructions are CUMULATIVES. That is, if you want to upgrade from version A to version C and there is  version B between A and C, you need to following the instructions for both A and B.</span>
</div>

## Upgrade from 1.x

* `beforeRun`, `afterRun` and `run` methods have been removed from the `Widget` class, and only the `render` method 
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
