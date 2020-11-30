<?php

use App\Utils\Ses\SesSignatureValidator;
use App\Utils\Ses\SesWebhookConfig;
use Illuminate\Http\Request;
use function Tests\getStub;

// From: laravel-mailcoach-ses-feedback/tests/SesSignatureValidatorTest.php

beforeEach(function () {
    $this->config = SesWebhookConfig::get();

    $this->validator = new SesSignatureValidator();
});

it('requires signature data', function () {
    $params = getStub('sesBounceWebhookContent');
    $request = Request::create('/aws-ses-feedback', 'POST', [], [], [], [], json_encode($params));

    $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] = 'SubscriptionConfirmation';

    $this->assertTrue($this->validator->isValid($request, $this->config));
});

it('calls the subscribe url when its a subscription confirmation request', function () {
    $params = getStub('sesSubscriptionConfirmation');
    $params['SubscribeURL'] = url('test-route');

    $request = Request::create('/aws-ses-feedback', 'POST', [], [], [], [], json_encode($params));

    $this->expectExceptionMessage("file_get_contents(".url('test-route').")");

    $this->validator->isValid($request, $this->config);
});
