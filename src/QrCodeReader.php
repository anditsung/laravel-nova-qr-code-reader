<?php

namespace Tsungsoft\QrCodeReader;

use Closure;
use Illuminate\Http\Request;
use Laravel\Nova\Contracts\RelatableField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FormatsRelatableDisplayValues;
use Laravel\Nova\Fields\ResolvesReverseRelation;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

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
     * The field's relationship status
     *
     * @var boolean
     */
    public $relationship;

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
     * Create a new field.
     *
     * @param string $name
     * @param bool $relationship
     * @param string|null $attribute
     * @param string|null $resource
     */
    public function __construct($name, $relationship = false, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute);

        $this->relationship = $relationship;

        if($relationship) {
            $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);
            $this->resourceClass = $resource;
            $this->resourceName = $resource::uriKey();
            $this->belongsToRelationship = $this->attribute;
        }
    }

    /**
     * Specify if the related resource can be viewed.
     *
     * @param  bool  $value
     * @return $this
     */
    public function viewable($value = true)
    {
        if(!$this->relationship) {
            $value = false;
        }
        $this->viewable = $value;

        return $this;
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
                $value = $resource->{$this->attribute}()->withoutGlobalScopes()->getResults();
            }

            if ($value) {
                $this->belongsToId = $value->getKey();

                $resource = new $this->resourceClass($value);

                $this->value = $this->formatDisplayValue($resource);

                $this->viewable = $this->viewable
                    && $resource->authorizedToView(request());
            }
        }
        else {
            if(! $value) {
                $value = $resource->{$this->attribute};
            }

            if($value) {
                $this->value = $value;
            }
        }
    }

    /**
     * Get additional meta information to merge with the field payload.
     *
     * @return array
     */
    public function meta()
    {
        if($this->relationship) {
            return array_merge([
                'resourceName' => $this->resourceName,
                'label' => forward_static_call([$this->resourceClass, 'label']),
                'singularLabel' => $this->singularLabel ?? $this->name ?? forward_static_call([$this->resourceClass, 'singularLabel']),
                'belongsToRelationship' => $this->belongsToRelationship,
                'belongsToId' => $this->belongsToId,
                'viewable' => $this->viewable,
                'reverse' => $this->isReverseRelation(app(NovaRequest::class)),
            ], $this->meta);
        }
        return array_merge([
            'viewable' => $this->viewable,
        ]);
    }

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
