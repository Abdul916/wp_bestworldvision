<?php

// File generated from our OpenAPI spec

namespace AmeliaStripe\Service\TestHelpers\Treasury;

class OutboundTransferService extends \AmeliaStripe\Service\AbstractService
{
    /**
     * Transitions a test mode created OutboundTransfer to the <code>failed</code>
     * status. The OutboundTransfer must already be in the <code>processing</code>
     * state.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\AmeliaStripe\Util\RequestOptions $opts
     *
     * @throws \AmeliaStripe\Exception\ApiErrorException if the request fails
     *
     * @return \AmeliaStripe\Treasury\OutboundTransfer
     */
    public function fail($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/test_helpers/treasury/outbound_transfers/%s/fail', $id), $params, $opts);
    }

    /**
     * Transitions a test mode created OutboundTransfer to the <code>posted</code>
     * status. The OutboundTransfer must already be in the <code>processing</code>
     * state.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\AmeliaStripe\Util\RequestOptions $opts
     *
     * @throws \AmeliaStripe\Exception\ApiErrorException if the request fails
     *
     * @return \AmeliaStripe\Treasury\OutboundTransfer
     */
    public function post($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/test_helpers/treasury/outbound_transfers/%s/post', $id), $params, $opts);
    }

    /**
     * Transitions a test mode created OutboundTransfer to the <code>returned</code>
     * status. The OutboundTransfer must already be in the <code>processing</code>
     * state.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\AmeliaStripe\Util\RequestOptions $opts
     *
     * @throws \AmeliaStripe\Exception\ApiErrorException if the request fails
     *
     * @return \AmeliaStripe\Treasury\OutboundTransfer
     */
    public function returnOutboundTransfer($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/test_helpers/treasury/outbound_transfers/%s/return', $id), $params, $opts);
    }
}
