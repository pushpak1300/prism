<?php

declare(strict_types=1);

namespace Prism\Prism\Audio;

use Illuminate\Http\Client\RequestException;
use Prism\Prism\Concerns\ConfiguresClient;
use Prism\Prism\Concerns\ConfiguresModels;
use Prism\Prism\Concerns\ConfiguresProviders;
use Prism\Prism\Concerns\HasProviderOptions;
use Prism\Prism\ValueObjects\Messages\Support\Audio;

class PendingRequest
{
    use ConfiguresClient;
    use ConfiguresModels;
    use ConfiguresProviders;
    use HasProviderOptions;

    protected string|Audio $input = '';

    public function withInput(string|Audio $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function asAudio(): AudioResponse
    {
        $request = $this->toTextToSpeechRequest();

        try {
            return $this->provider->textToSpeech($request);
        } catch (RequestException $e) {
            $this->provider->handleRequestException($request->model(), $e);
        }
    }

    public function asText(): TextResponse
    {
        $request = $this->toSpeechToTextRequest();

        try {
            return $this->provider->speechToText($request);
        } catch (RequestException $e) {
            $this->provider->handleRequestException($request->model(), $e);
        }
    }

    protected function toTextToSpeechRequest(): TextToSpeechRequest
    {
        return new TextToSpeechRequest(
            model: $this->model,
            providerKey: $this->providerKey(),
            input: $this->input,
            clientOptions: $this->clientOptions,
            clientRetry: $this->clientRetry,
            providerOptions: $this->providerOptions,
        );
    }

    protected function toSpeechToTextRequest(): SpeechToTextRequest
    {
        return new SpeechToTextRequest(
            model: $this->model,
            providerKey: $this->providerKey(),
            input: $this->input,
            clientOptions: $this->clientOptions,
            clientRetry: $this->clientRetry,
            providerOptions: $this->providerOptions,
        );
    }
}
