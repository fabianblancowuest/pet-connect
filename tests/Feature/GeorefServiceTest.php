<?php

use App\Services\GeorefService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

test('fetches localities from the Georef API', function () {
    Http::fake([
        'apis.datos.gob.ar/georef/api/localidades*' => Http::response([
            'localidades' => [
                ['id' => '34014020', 'nombre' => 'Formosa'],
                ['id' => '34049010', 'nombre' => 'Clorinda'],
                ['id' => '34056040', 'nombre' => 'Pirané'],
            ],
            'total' => 3,
        ]),
    ]);

    $service = app(GeorefService::class);
    $localities = $service->getLocalities('34');

    expect($localities)->toBe([
        '34014020' => 'Formosa',
        '34049010' => 'Clorinda',
        '34056040' => 'Pirané',
    ]);
});

test('caches localities after first request', function () {
    Http::fake([
        'apis.datos.gob.ar/georef/api/localidades*' => Http::response([
            'localidades' => [
                ['id' => '34014020', 'nombre' => 'Formosa'],
            ],
            'total' => 1,
        ]),
    ]);

    $service = app(GeorefService::class);
    $service->getLocalities('34');

    Http::assertSentCount(1);

    $service->getLocalities('34');

    Http::assertSentCount(1);
});

test('returns empty array on API failure', function () {
    Http::fake([
        'apis.datos.gob.ar/georef/api/localidades*' => Http::response(null, 500),
    ]);

    $service = app(GeorefService::class);
    $localities = $service->getLocalities('34');

    expect($localities)->toBe([]);
});

test('caches different province IDs separately', function () {
    Http::fake([
        'apis.datos.gob.ar/georef/api/localidades*' => Http::response([
            'localidades' => [
                ['id' => '34014020', 'nombre' => 'Formosa'],
            ],
            'total' => 1,
        ]),
    ]);

    $service = app(GeorefService::class);
    $service->getLocalities('34');
    $service->getLocalities('02');

    Http::assertSentCount(2);
});
