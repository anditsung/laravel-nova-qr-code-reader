<?php

namespace Tsungsoft\QrCodeReader;

use Laravel\Nova\Contracts\RelatableField;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FormatsRelatableDisplayValues;
use Laravel\Nova\Fields\ResolvesReverseRelation;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;
use Laravel\Nova\Http\Requests\NovaRequest;

class QrCodeReader extends Field implements RelatableField
{
    use FormatsRelatableDisplayValues;
    use ResolvesReverseRelation;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'qr-code-reader';

    /**
     * The field's resource
     *
     * @var string
     */
    public $resource;

    /**
     * The class name of the related resource.
     *
     * @var string
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The name of the Eloquent "belongs to" relationship.
     *
     * @var string
     */
    public $belongsToRelationship;

    /**
     * The key of the related Eloquent model.
     *
     * @var string
     */
    public $belongsToId;

    /**
     * The column that should be displayed for the field.
     *
     * @var \Closure
     */
    public $display;

    /**
     * Indicates if the related resource can be viewed.
     *
     * @var bool
     */
    public $viewable = true;

    /**
     * default value for this field
     */
    public $canSubmit = false;
    public $canInput = false;
    public $qrSizeIndex = 30;
    public $qrSizeDetail = 100;
    public $qrSizeForm = 50;
    public $displayValue = false;
    public $relationship = false;

    /**
     * Create a new field.
     *
     * @param string $name
     * @param string|null $attribute
     * @param string|null $resource
     */
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);
        if(class_exists($resource)) {
            $this->resourceClass = $resource;
            $this->resourceName = $resource::uriKey();
            $this->belongsToRelationship = $this->attribute;

            $this->relationship = true;
        }
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $value = null;

        if($this->relationship) {
            if ($resource->relationLoaded($this->attribute)) {
                $value = $resource->getRelation($this->attribute);
            }

            if (! $value) {
                // bikin relation dari column yang disave
                // tidak boleh diganti karena akan digunakan untuk penyimpanan data

                $index = strpos($this->attribute, '_id');
                if($index > 0) {
                    $relationshipName = substr($this->attribute, 0, $index);
                }
                else {
                    $relationshipName = $this->attribute;
                }
                $value = $resource->{$relationshipName}()->withoutGlobalScopes()->getResults();
            }

            if ($value) {
                $this->belongsToId = $value->getKey();
                $resource = new $this->resourceClass($value);
                $this->value = $this->formatDisplayValue($resource);
                $this->viewable = $this->viewable && $resource->authorizedToView(request());
            }
        }
        else {
            if(! $value) {
                $value = $resource->{$this->attribute};
            }
            if($value) {
                $this->value = $value;
                $this->viewable = false;
            }
        }
    }


//    /**
//     * Get additional meta information to merge with the field payload.
//     *
//     * @return array
//     */
//    public function meta()
//    {
//        if(!$this->relationship) {
//            return array_merge([
//                'viewable' => $this->viewable,
//            ], $this->meta);
//        }
//        return array_merge([
//            'resourceName' => $this->resourceName,
//            'label' => forward_static_call([$this->resourceClass, 'label']),
//            'singularLabel' => $this->singularLabel ?? $this->name ?? forward_static_call([$this->resourceClass, 'singularLabel']),
//            'belongsToRelationship' => $this->belongsToRelationship,
//            'belongsToId' => $this->belongsToId,
//            'viewable' => $this->viewable,
//            'reverse' => $this->isReverseRelation(app(NovaRequest::class)),
//        ], $this->meta);
//    }

    public function canSubmit($canSubmit = true)
    {
        $this->canSubmit = $canSubmit;

        return $this;
    }

    public function canInput($canInput = true)
    {
        $this->canInput = $canInput;

        return $this;
    }

    public function qrSizeIndex($qrSizeIndex = 30)
    {
        $this->qrSizeIndex = $qrSizeIndex;

        return $this;
    }

    public function qrSizeDetail($qrSizeDetail = 100)
    {
        $this->qrSizeDetail = $qrSizeDetail;

        return $this;
    }

    public function qrSizeForm($qrSizeForm = 50)
    {
        $this->qrSizeForm = $qrSizeForm;

        return $this;
    }

    public function displayValue($displayValue = true)
    {
        $this->displayValue = $displayValue;

        return $this;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $meta = '';

        if(!$this->relationship) {
            $meta = [
                'qrSizeIndex' => $this->qrSizeIndex,
                'qrSizeDetail' => $this->qrSizeDetail,
                'qrSizeForm' => $this->qrSizeForm,
                'canSubmit' => $this->canSubmit,
                'canInput' => $this->canInput,
                'displayValue' => $this->displayValue,
                'viewable' => $this->viewable,
            ];
        }
        else {
            $meta = [
                'qrSizeIndex' => $this->qrSizeIndex,
                'qrSizeDetail' => $this->qrSizeDetail,
                'qrSizeForm' => $this->qrSizeForm,
                'canSubmit' => $this->canSubmit,
                'canInput' => $this->canInput,
                'displayValue' => $this->displayValue,
                'resourceName' => $this->resourceName,
                'label' => forward_static_call([$this->resourceClass, 'label']),
                'singularLabel' => $this->singularLabel ?? $this->name ?? forward_static_call([$this->resourceClass, 'singularLabel']),
                'belongsToRelationship' => $this->belongsToRelationship,
                'belongsToId' => $this->belongsToId,
                'viewable' => $this->viewable,
                'reverse' => $this->isReverseRelation(app(NovaRequest::class)),
            ];
        }
        return array_merge(parent::jsonSerialize(), $meta);
    }
}
