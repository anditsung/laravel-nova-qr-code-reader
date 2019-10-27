<?php

namespace Tsungsoft\QrCodeReader;

use Laravel\Nova\Fields\Field;

class QrCodeReader extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'qr-code-reader';

    public function canSubmit($canSubmit = false)
    {
        return $this->withMeta(['canSubmit' => $canSubmit]);
    }

    public function canInput($canInput = false)
    {
        return $this->withMeta(['canInput' => $canInput]);
    }

    public function qrSize($size = 200)
    {
        return $this->withMeta(['qrSize' => $size]);
    }
}
