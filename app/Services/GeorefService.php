<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeorefService
{
    private const BASE_URL = 'https://apis.datos.gob.ar/georef/api';

    public function getLocalities(string $provinceId = '34'): array
    {
        return Cache::remember('georef_localities_' . $provinceId, 86400, function () use ($provinceId) {
            $response = Http::get(self::BASE_URL . '/localidades', [
                'provincia' => $provinceId,
                'campos' => 'basico',
                'max' => 100,
                'orden' => 'nombre',
            ]);

            if ($response->failed()) {
                return [];
            }

            return collect($response->json('localidades'))
                ->pluck('nombre', 'id')
                ->toArray();
        });
    }
}
