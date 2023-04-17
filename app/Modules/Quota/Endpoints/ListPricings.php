<?php

namespace App\Modules\Quota\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ListPricings extends Endpoint
{
    public function __invoke(Request $request): array
    {
        $pricings = config('quota.pricings');

        foreach ($pricings as $index => $pricing) {
            $pricings[$index] = Arr::only($pricing, [
                'title',
                'tokens_count',
                'days',
                'price',
            ]);

            $pricings[$index]['pricing'] = $index;
        }

        return array_values($pricings);
    }
}
