<?php

namespace App\Tests\Acceptance;

use App\Shared\Domain\Service\Assert;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiContext extends BaseContext
{
    private array $requestHeaders;
    private array $parameters;
    private Response $response;

    public function __construct(
        KernelInterface $kernel
    ) {
        parent::__construct($kernel);
        $this->requestHeaders = [];
        $this->parameters = [];
    }

    /**
     * @When I send a :action request to :endpoint
     */
    public function iSendARequestTo(string $action, string $endpoint): void
    {
        $options = ['headers' => $this->requestHeaders];
        $this->doRequest($action, $endpoint, $options);
    }

    /**
     * @When I send a :action request to :endpoint with parameters
     */
    public function iSendARequestToWithParameters($action, $endpoint, PyStringNode $parameters): void
    {
        $this->parameters = json_decode((string) $parameters, true);
        $options['headers'] = $this->requestHeaders;

        $this->doRequest($action, $endpoint, $options);
    }

    /**
     * @When I send a :action request to :endpoint with body
     */
    public function iSendARequestToWithBody($action, $endpoint, PyStringNode $body): void
    {
        $options['body'] = $body->getRaw();
        $options['headers'] = $this->requestHeaders;

        $this->doRequest($action, $endpoint, $options);
    }

    private function doRequest(
        string $method,
        string $endpoint,
        array $options = []
    ): void {
        $request = Request::create($endpoint, $method, $this->parameters, [], [], [], $options['body'] ?? null);
        foreach ($options['headers'] ?? [] as $headerKey => $headerValue) {
            $request->headers->set($headerKey, $headerValue);
        }

        $this->response = $this->kernel->handle($request);
    }

    /**
     * @Then the response code should be :responseCode
     */
    public function theResponseCodeShouldBe(int $responseCode): void
    {
        Assert::same(
            $responseCode,
            $this->response->getStatusCode(),
            sprintf('Expected HTTP Status Code %d . Actual: %d', $responseCode, $this->response->getStatusCode())
        );
    }

    /**
     * @Then /^the response body should be:$/
     */
    public function theResponseBodyShouldBe(PyStringNode $body): void
    {
        $response         = json_decode($this->response->getContent());
        $expectedResponse = json_decode($body->getRaw());

        \PHPUnit\Framework\Assert::assertEquals($response, $expectedResponse);
    }
}
