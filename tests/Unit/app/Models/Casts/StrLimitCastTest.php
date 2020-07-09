<?php

use App\Models\Casts\StrLimitCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

it('limits string field length', function () {

    $modelClass = new class extends Model {
        protected $casts = [
            'name' => StrLimitCast::class,
        ];
    };

    $tooLongString = str_repeat('a', 256);
    $model = new $modelClass;
    $model->name = $tooLongString;

    $expectedStringLimit = Str::limit($tooLongString, 255);
    assertEquals($expectedStringLimit, $model->name);
    assertStringEndsWith('...', $model->name);

});
