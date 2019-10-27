# Laravel Nova QR Cod Reader Field

This package allow user to decode qr code inside from field and display it

## Installation
using packagist
```
composer require tsungsoft/qr-code-reader
```
using github repository
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/anditsung/laravel-nova-qr-code-reader"
    }
],
```

## Usage

```
use Tsungsoft\QrCodeReader\QrCodeReader;

public function fields(Request $request)
{
    return [
        ...

        QrCodeReader::make('Name')
            ->canInput(true) // the user able to input the code using keyboard
            ->canSubmit(true) // on modal scan need to click submit to send the code to the input value
            ->qrSize(100), // the size of the qr code

        ...
    ];
}
```
