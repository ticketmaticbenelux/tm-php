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

namespace Ticketmatic\Endpoints\Settings\Ticketsales;

use Ticketmatic\Client;
use Ticketmatic\ClientException;
use Ticketmatic\Json;
use Ticketmatic\Model\CreateOrderFee;
use Ticketmatic\Model\OrderFee;
use Ticketmatic\Model\OrderFeeParameters;
use Ticketmatic\Model\UpdateOrderFee;

class Orderfees
{

    /**
     * Get a list of order fees
     *
     * @param Client $client
     * @param \Ticketmatic\Model\OrderFeeParameters|array $params
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\ListOrderFee[]
     */
    public static function getlist(Client $client, $params) {
        if ($params == null || is_array($params)) {
            $params = new OrderFeeParameters($params == null ? array() : $params);
        }
        $req = $client->newRequest("GET", "/{accountname}/settings/ticketsales/orderfees");

        $req->addQuery("includearchived", $params->includearchived);
        $req->addQuery("lastupdatesince", $params->lastupdatesince);
        $req->addQuery("filter", $params->filter);

        $result = $req->run();
        return Json::unpackArray("ListOrderFee", $result);
    }

    /**
     * Get a single order fee
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\OrderFee
     */
    public static function get(Client $client, $id) {
        $req = $client->newRequest("GET", "/{accountname}/settings/ticketsales/orderfees/{id}");
        $req->addParameter("id", $id);


        $result = $req->run();
        return OrderFee::fromJson($result);
    }

    /**
     * Create a new order fee
     *
     * @param Client $client
     * @param \Ticketmatic\Model\CreateOrderFee|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\OrderFee
     */
    public static function create(Client $client, $data) {
        if ($data == null || is_array($data)) {
            $data = new CreateOrderFee($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/settings/ticketsales/orderfees");
        $req->setBody($data);

        $result = $req->run();
        return OrderFee::fromJson($result);
    }

    /**
     * Modify an existing order fee
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\UpdateOrderFee|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\OrderFee
     */
    public static function update(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new UpdateOrderFee($data == null ? array() : $data);
        }
        $req = $client->newRequest("PUT", "/{accountname}/settings/ticketsales/orderfees/{id}");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return OrderFee::fromJson($result);
    }

    /**
     * Remove an order fee
     *
     * Order fees are archivable: this call won't actually delete the object from the database.
     * Instead, it will mark the object as archived, which means it won't show up anymore in most
     * places.
     *
     * Most object types are archivable and can't be deleted: this is needed to ensure consistency of
     * historical data.
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     */
    public static function delete(Client $client, $id) {
        $req = $client->newRequest("DELETE", "/{accountname}/settings/ticketsales/orderfees/{id}");
        $req->addParameter("id", $id);


        $req->run();
    }

    /**
     * Batch modify order fees
     *
     * @param Client $client
     *
     * @throws ClientException
     */
    public static function batch(Client $client) {
        $req = $client->newRequest("PUT", "/{accountname}/settings/ticketsales/orderfees");

        $req->run();
    }
}
