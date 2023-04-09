<?php

namespace App\Modules\Quota\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Requests\ListPricingsRequest;
use Illuminate\Support\Arr;

class ListPricings extends Endpoint
{
    public function __invoke(ListPricingsRequest $request): array
    {
        $pricings = config("quota.pricings.{$request->input('quota_type')}");

        foreach ($pricings as $index => $pricing) {
            $pricings[$index] = Arr::only($pricing, [
                'title',
                'tokens_count',
                'days',
                'price',
            ]);

            $pricings[$index]['quota_type'] = $request->input('quota_type');
            $pricings[$index]['pricing'] = $index;
        }

        return array_values($pricings);
    }
}
