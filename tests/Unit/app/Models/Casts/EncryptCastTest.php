<?php

use App\Models\Casts\EncryptCast;
use Illuminate\Database\Eloquent\Model;

it('encrypts string field with serialisation by default', function () {
    $modelClass = new class extends Model {
        protected $casts = [
            'name' => EncryptCast::class,
        ];
    };

    $plaintext = str_repeat('a', 256);
    $model = new $modelClass;
    $model->name = $plaintext;
    $encryptCastValue = $model->getAttributes()['name'];

    assertEquals($plaintext, $model->name);
    assertEquals($plaintext, decrypt($encryptCastValue));

    assertNotEquals($plaintext, decrypt($encryptCastValue, false));
})->only();

it('can encrypt string field without serialisation', function () {
    $modelClass = new class extends Model {
        protected $casts = [
            'name' => EncryptCast::class.':0',
        ];
    };

    $plaintext = str_repeat('a', 256);
    $model = new $modelClass;
    $model->name = $plaintext;
    $encryptCastValue = $model->getAttributes()['name'];

    assertEquals($plaintext, $model->name);
    assertEquals($plaintext, decrypt($encryptCastValue, false));

    $this->expectException(\Exception::class);
    decrypt($encryptCastValue);
})->only();
