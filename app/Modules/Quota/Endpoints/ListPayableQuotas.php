<?php

namespace App\Modules\Quota\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Quota\Requests\ListPayableQuotasRequest;
use Illuminate\Support\Arr;

class ListPayableQuotas extends Endpoint
{
    public function __invoke(ListPayableQuotasRequest $request): array
    {
        $pricings = config("quota.types.{$request->input('quota_type')}.pricings");

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
