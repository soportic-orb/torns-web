<?php

namespace App\Ai;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Models\BaseModel;
use Botble\Table\Abstracts\TableBulkActionAbstract;
use Illuminate\Database\Eloquent\Model;

/**
 * «Traducir con IA» in the bulk actions of the tag and category lists.
 *
 * Botble's confirmation dialog only sends the selected ids, so this action
 * renders its own menu item and dialog (languages to translate into,
 * overwrite) and its script translates one item at a time through
 * torns-ai.translate-taxonomy, with a progress bar, like the post
 * translation. dispatch() is therefore never reached.
 */
class TranslateTaxonomyBulkAction extends TableBulkActionAbstract
{
    protected string $type = 'tag';

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function render(): string
    {
        return view('admin.ai.taxonomy-bulk-translate', [
            'type' => $this->type,
            'languages' => app(TaxonomyTranslator::class)->languages(),
            'label' => $this->getLabel(),
        ])->render();
    }

    public function dispatch(BaseModel|Model $model, array $ids): BaseHttpResponse
    {
        return BaseHttpResponse::make()->setError()->setMessage(trans('torns.ai_bulk_translate_use_dialog'));
    }
}
