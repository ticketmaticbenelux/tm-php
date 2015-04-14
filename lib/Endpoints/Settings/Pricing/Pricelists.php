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

namespace Ticketmatic\Endpoints\Settings\Pricing;

use Ticketmatic\Client;
use Ticketmatic\ClientException;
use Ticketmatic\Json;
use Ticketmatic\Model\CreatePriceList;
use Ticketmatic\Model\PriceList;
use Ticketmatic\Model\PriceListParameters;
use Ticketmatic\Model\UpdatePriceList;

/**
 * Price lists are used to define the actual prices that will be available for one
 * or more events. You can create a price list for a selection of seat ranks or for
 * a simple contingent without seatranks.
 *
 * In each price list prices are defined for a selection of price types.
 * Additionally, conditions for each price type can be defined.
 *
 * The possible conditions are:
 *
 * * **Ticketlimit**: There is a limited amount of tickets available for the
 * selected price type.
 *
 * * **Date validity**: The price type is only available in this period.
 *
 * * **Promocode**: The price type is only available if the customer provides a
 * promocode.
 *
 * * **Customers tagged as**: The price type is only available if the customer is
 * authenticated and has a certain tag.
 *
 * * **Max number of tickets per customer**: the maximum number of tickets a
 * customer can buy of this specific price type.
 *
 * The prices for an event are defined by linking a price list to the event. The
 * same price list can be linked to multiple events.
 *
 * Changing the price in a price list will automatically change the price in all
 * events that have linked this price list. (Remark: the new prices will only be
 * applied for new orders, prices for tickets that are already sold will not
 * change)
 *
 * ## Help Center
 *
 * Full documentation can be found in the Ticketmatic Help Center
 * (https://apps.ticketmatic.com/#/knowledgebase/api/settings_pricing_pricelists).
 */
class Pricelists
{

    /**
     * Get a list of price lists
     *
     * @param Client $client
     * @param \Ticketmatic\Model\PriceListParameters|array $params
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\ListPriceList[]
     */
    public static function getlist(Client $client, $params) {
        if ($params == null || is_array($params)) {
            $params = new PriceListParameters($params == null ? array() : $params);
        }
        $req = $client->newRequest("GET", "/{accountname}/settings/pricing/pricelists");

        $req->addQuery("includearchived", $params->includearchived);
        $req->addQuery("lastupdatesince", $params->lastupdatesince);
        $req->addQuery("filter", $params->filter);

        $result = $req->run();
        return Json::unpackArray("ListPriceList", $result);
    }

    /**
     * Get a single price list
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\PriceList
     */
    public static function get(Client $client, $id) {
        $req = $client->newRequest("GET", "/{accountname}/settings/pricing/pricelists/{id}");
        $req->addParameter("id", $id);


        $result = $req->run();
        return PriceList::fromJson($result);
    }

    /**
     * Create a new price list
     *
     * @param Client $client
     * @param \Ticketmatic\Model\CreatePriceList|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\PriceList
     */
    public static function create(Client $client, $data) {
        if ($data == null || is_array($data)) {
            $data = new CreatePriceList($data == null ? array() : $data);
        }
        $req = $client->newRequest("POST", "/{accountname}/settings/pricing/pricelists");
        $req->setBody($data);

        $result = $req->run();
        return PriceList::fromJson($result);
    }

    /**
     * Modify an existing price list
     *
     * @param Client $client
     * @param int $id
     * @param \Ticketmatic\Model\UpdatePriceList|array $data
     *
     * @throws ClientException
     *
     * @return \Ticketmatic\Model\PriceList
     */
    public static function update(Client $client, $id, $data) {
        if ($data == null || is_array($data)) {
            $data = new UpdatePriceList($data == null ? array() : $data);
        }
        $req = $client->newRequest("PUT", "/{accountname}/settings/pricing/pricelists/{id}");
        $req->addParameter("id", $id);

        $req->setBody($data);

        $result = $req->run();
        return PriceList::fromJson($result);
    }

    /**
     * Remove a price list
     *
     * Price lists are archivable: this call won't actually delete the object from the
     * database. Instead, it will mark the object as archived, which means it won't
     * show up anymore in most places.
     *
     * Most object types are archivable and can't be deleted: this is needed to ensure
     * consistency of historical data.
     *
     * @param Client $client
     * @param int $id
     *
     * @throws ClientException
     */
    public static function delete(Client $client, $id) {
        $req = $client->newRequest("DELETE", "/{accountname}/settings/pricing/pricelists/{id}");
        $req->addParameter("id", $id);


        $req->run();
    }

    /**
     * Batch modify price lists
     *
     * @param Client $client
     *
     * @throws ClientException
     */
    public static function batch(Client $client) {
        $req = $client->newRequest("PUT", "/{accountname}/settings/pricing/pricelists");

        $req->run();
    }
}
