<?php
/**
 * Copyright (C) 2014-2015 by Ticketmatic BVBA <developers@ticketmatic.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @license     MIT X11 http://opensource.org/licenses/MIT
 * @author      Ticketmatic BVBA <developers@ticketmatic.com>
 * @copyright   Ticketmatic BVBA
 * @link        http://www.ticketmatic.com/
 */

namespace Ticketmatic\Endpoints;

use Ticketmatic\Client;
use Ticketmatic\ClientException;
use Ticketmatic\Json;
use Ticketmatic\Model\AddPayments;
use Ticketmatic\Model\AddRefunds;
use Ticketmatic\Model\AddTickets;
use Ticketmatic\Model\AddTicketsResult;
use Ticketmatic\Model\CreateOrder;
use Ticketmatic\Model\DeleteTickets;
use Ticketmatic\Model\LogItem;
use Ticketmatic\Model\Order;
use Ticketmatic\Model\OrderQuery;
use Ticketmatic\Model\TicketsEmaildeliveryRequest;
use Ticketmatic\Model\TicketsPdfRequest;
use Ticketmatic\Model\UpdateOrder;
use Ticketmatic\Model\UpdateTickets;
use Ticketmatic\Model\Url;

/**
 * Orders are one of the main concepts in Ticketmatic. These operations allow you
 * to create, update and read orders. You can add and remove tickets to orders and
 * add payments.
 *
 * ## Help Center
 *
 * Full documentation can be found in the Ticketmatic Help Center
 * (https://apps.ticketmatic.com/#/knowledgebase/api/orders).
 */
class Orders
{

    /**
     * Get a list of orders
     *
     * @param Client $client
     * @param \Ticketmatic\Model\OrderQuery|array $params
     *
     * @throws ClientException
     *
     * @return OrdersList
     */
    public static function getlist(Client $client, $params) {
        if ($params == null || is_array($params)) {
            $params = new OrderQuery($params == null ? array() : $params);
        }
        $req = $client->newRequest("GET", "/{accountname}/orders");

        $req->addQuery("filter", $params->filter);
        $req->addQuery("includearchived", $params->includearchived);
        $req->addQuery("lastupdatesince", $params->lastupdatesince);
        $req->addQuery("limit", $params->limit);
        $req->addQuery("offset", $params->offset);
        $req->addQuery("orderby", $params->orderby);
        $req->addQuery("output", $params->output);
        $req->addQuery("searchterm", $params->searchterm);
        $req->addQuery("simplefilter", $params->simplefilter);

        $result = $req->run();
        return OrdersList::fromJson($result);
    }

    /**
     * Get a single order
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function get(Client $client, $id) {
        $req = $client->newRequest("GET", "/{accountname}/orders/{id}");
        $req->addParameter("id", $id);


        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Create a new order
     *
     * Creates a new empty order.
     *
     * Each order is linked to a sales channel
     * (https://apps.ticketmatic.com/#/knowledgebase/api/types/SalesChannel), which
     * needs to be supplied when creating.
     *
     * @param Client $client
     * @param \Ticketmatic\Model\CreateOrder|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function create(Client $client, $data) {
        if ($data == null || is_array($data)) {
            $data = new CreateOrder($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders");
        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Update an order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\UpdateOrder|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function update(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new UpdateOrder($data == null ? array() : $data);
        }
        $req = $client->newRequest("PUT", "/{accountname}/orders/{id}");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Confirm an order
     *
     * Marks the order as confirmed.
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function confirm(Client $client, $id) {
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}");
        $req->addParameter("id", $id);


        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Add tickets to order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\AddTickets|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\AddTicketsResult
     */
    public static function addtickets(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new AddTickets($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}/tickets");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return AddTicketsResult::fromJson($result);
    }

    /**
     * Modify tickets in order
     *
     * Individual tickets can be updated. Per call you can specify any number of ticket
     * IDs and one operation.
     *
     * Each operation accepts different parameters, dependent on the operation type:
     *
     * * **Set ticket holders**: an array of ticket holder IDs (see Contact
     * (https://apps.ticketmatic.com/#/knowledgebase/api/types/Contact)), one for each
     * ticket (`ticketholderids`).
     *
     * * **Update price type**: an array of ticket price type IDs (as can be found in
     * the Event pricing
     * (https://apps.ticketmatic.com/#/knowledgebase/api/types/Event)), one for each
     * ticket (`tickettypepriceids`).
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\UpdateTickets|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function updatetickets(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new UpdateTickets($data == null ? array() : $data);
        }
        $req = $client->newRequest("PUT", "/{accountname}/orders/{id}/tickets");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Remove tickets from order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\DeleteTickets|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function deletetickets(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new DeleteTickets($data == null ? array() : $data);
        }
        $req = $client->newRequest("DELETE", "/{accountname}/orders/{id}/tickets");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Add payments to order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\AddPayments|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function addpayments(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new AddPayments($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}/payments");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Add refund for payment for order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\AddRefunds|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function addrefunds(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new AddRefunds($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}/refunds");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }

    /**
     * Get the log history for an order
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\LogItem[]
     */
    public static function getlogs(Client $client, $id) {
        $req = $client->newRequest("GET", "/{accountname}/orders/{id}/logs");
        $req->addParameter("id", $id);


        $result = $req->run();
        return Json::unpackArray("LogItem", $result);
    }

    /**
     * Get the PDF for (some or all) tickets in the order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\TicketsPdfRequest|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Url
     */
    public static function postticketspdf(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new TicketsPdfRequest($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}/tickets/pdf");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Url::fromJson($result);
    }

    /**
     * Send the delivery e-mail for the order
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\TicketsEmaildeliveryRequest|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\Order
     */
    public static function postticketsemaildelivery(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new TicketsEmaildeliveryRequest($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/orders/{id}/tickets/emaildelivery");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return Order::fromJson($result);
    }
}
