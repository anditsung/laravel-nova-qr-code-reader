# Laravel Nova QR Cod Reader Field

This package allow user to decode qr code inside from field and display it

## Installation
using packagist
```
composer require tsungsoft/qr-code-reader
```
using github repository add this on composer.json
```
"require": {
    "tsungsoft/qr-code-reader": "*"
}
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/anditsung/laravel-nova-qr-code-reader"
    }
],
```

## Configuration

### Localization
publish lang files
```
php artisan vendor:publish --tag=qr-code-reader-lang
```
make a copy of `resources/lang/vendor/qr-code-reader/en.json` for each locale

## Usage

```php
use Illuminate\Http\Request;
use Laravel\Nova\Resource;
use Tsungsoft\QrCodeReader\QrCodeReader;

class ResourceName extends Resource {
    //...

    public function fields(Request $request)
    {
        return [
            //...
    
            QrCodeReader::make('Name', 'name_id')   // Name -> label name, name_id -> save to column
                ->canInput()                        // the user able to input the code using keyboard, default false
                ->canSubmit()                       // on modal scan need to click submit to send the code to the input value, default false
                ->displayValue()                    // display value beside qr code on detail view, default false
                ->qrSizeIndex()                     // set qr size on index, default 30
                ->qrSizeDetail()                    // set qr size on detail, default 100
                ->qrSizeForm()                      // set qr size on form, default 50
                ->viewable()                        // set viewable if has belongto value, default true
                ->displayWidth('720px'),            // set display width, default auto
    
            //...
        ];
    }
}
```
