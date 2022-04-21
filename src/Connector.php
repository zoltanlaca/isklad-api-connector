<?php

namespace ZoltanLaca\IskladApiConnector;

/**
 * Class Connector
 * @package ZoltanLaca\IskladApiConnector
 */
class Connector
{
    private string $apiUrl;

    private string $authId;
    private string $authKey;
    private string $authToken;

    private array $request;
    private array $response;

    /**
     * @param int $authId
     * @param string $authKey
     * @param string $authToken
     * @param string|null $apiUrl
     */
    public function __construct(string $authId, string $authKey, string $authToken, ?string $apiUrl = null)
    {
        $this->authId = $authId;
        $this->authKey = $authKey;
        $this->authToken = $authToken;
        $this->apiUrl = $apiUrl ?? 'https://api.isklad.eu/rest/v1';
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response['response'];
    }

    /**
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->response['headers'];
    }

    /**
     * @return string
     */
    public function getResponseRaw(): string
    {
        return $this->response['responseRaw'];
    }

    /**
     * @param string $method
     * @param array $data
     * @return $this
     */
    public function createRequest(string $method, array $data): self
    {
        $this->request = [
            'auth' => [
                'auth_id' => $this->authId,
                'auth_key' => $this->authKey,
                'auth_token' => $this->authToken,
            ],
            'request' => [
                'req_method' => $method,
                'req_data' => $data,
            ],
        ];

        return $this;
    }

    /**
     * @param bool $doNotVerifySsl
     * @return $this
     * @throws ConnectorException
     */
    public function send(bool $doNotVerifySsl = false): self
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($this->request),
            CURLOPT_HTTPHEADER => [
                'Content-type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => !$doNotVerifySsl,
        ]);

        $result = curl_exec($curl);
        $headers = curl_getinfo($curl);
        $curlErrorCode = curl_errno($curl);
        $curlErrorMessage = curl_error($curl);

        curl_close($curl);

        if($curlErrorCode !== CURLE_OK){
            Throw new ConnectorException($curlErrorMessage, $curlErrorCode);
        }

        $this->response = [
            'headers' => $headers,
            'response' => json_decode($result, true) ?? [],
            'responseRaw' => $result,
        ];

        return $this;
    }
}
