<?php

namespace Botble\Captcha;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

class CaptchaV3
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $siteKey;

    /**
     * @var string
     */
    protected $origin;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->secret = $app['config']->get('plugins.captcha.general.secret');
        $this->siteKey = $app['config']->get('plugins.captcha.general.site_key');
        $this->origin = 'https://www.google.com/recaptcha';
    }

    /**
     * Verify the given token and return the score.
     * Returns false if token is invalid.
     * Returns the score if the token is valid.
     *
     * @param string $token
     * @param string $clientIp
     * @param array $parameters
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify($token, $clientIp, $parameters = [])
    {
        $client = new Client;

        $response = $client->request('POST', $this->origin . '/api/siteverify', [
            'form_params' => [
                'secret'   => $this->secret,
                'response' => $token,
                'remoteip' => $clientIp,
            ],
        ]);

        $body = json_decode($response->getBody(), true);

        if (!isset($body['success']) || $body['success'] !== true) {
            return false;
        }

        $action = $parameters[0];
        $minScore = isset($parameters[1]) ? (float)$parameters[1] : 0.5;

        if ($action && (!isset($body['action']) || $action != $body['action'])) {
            return false;
        }

        $score = isset($body['score']) ? $body['score'] : false;

        return $score && $score >= $minScore;
    }

    /**
     * @param string[] $attributes
     * @param array $options
     * @return string
     */
    public function display($attributes = ['action' => 'form'], $options = ['name' => 'g-recaptcha-response'])
    {
        if (!$this->siteKey) {
            return null;
        }

        $action = Arr::get($attributes, 'action', 'form');
        $name = Arr::get($options, 'name', 'g-recaptcha-response');

        $fieldId = uniqid($name . '-', false);
        $html = '<input type="hidden" name="' . $name . '" id="' . $fieldId . '">';

        if (Arr::get($attributes, 'add-js', true)) {
            $html .= $this->initJs();
        }

        $html .= "<script>
  grecaptcha.ready(function() {
      grecaptcha.execute('" . $this->siteKey . "', {action: '" . $action . "'}).then(function(token) {
         document.getElementById('" . $fieldId . "').value = token;
      });
  });
  </script>";
        return $html;
    }

    /**
     * @return string
     */
    public function initJs()
    {
        return '<script src="' . $this->origin . '/api.js?render=' . $this->siteKey . '&hl=' . app()->getLocale() . '"></script>';
    }
}
