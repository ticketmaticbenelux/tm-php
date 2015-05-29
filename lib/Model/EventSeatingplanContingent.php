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

namespace Ticketmatic\Model;

use Ticketmatic\Json;

/**
 * Information about a contingent in the seating plan for an event
 * (https://apps.ticketmatic.com/#/knowledgebase/api/types/Event).
 *
 * ## Help Center
 *
 * Full documentation can be found in the Ticketmatic Help Center
 * (https://apps.ticketmatic.com/#/knowledgebase/api/types/EventSeatingplanContingent).
 */
class EventSeatingplanContingent implements \jsonSerializable
{
    /**
     * Create a new EventSeatingplanContingent
     *
     * @param array $data
     */
    public function __construct(array $data = array()) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Contingent ID
     *
     * @var int
     */
    public $id;

    /**
     * Event ID
     *
     * @var int
     */
    public $eventid;

    /**
     * Seat rank ID
     *
     * @var int
     */
    public $seatrankid;

    /**
     * Name of the contingent
     *
     * @var string
     */
    public $name;

    /**
     * Number of tickets in the contingent
     *
     * @var int
     */
    public $amount;

    /**
     * Unpack EventSeatingplanContingent from JSON.
     *
     * @param object $obj
     *
     * @return \Ticketmatic\Model\EventSeatingplanContingent
     */
    public static function fromJson($obj) {
        if ($obj === null) {
            return null;
        }

        return new EventSeatingplanContingent(array(
            "id" => isset($obj->id) ? $obj->id : null,
            "eventid" => isset($obj->eventid) ? $obj->eventid : null,
            "seatrankid" => isset($obj->seatrankid) ? $obj->seatrankid : null,
            "name" => isset($obj->name) ? $obj->name : null,
            "amount" => isset($obj->amount) ? $obj->amount : null,
        ));
    }

    /**
     * Serialize EventSeatingplanContingent to JSON.
     *
     * @return array
     */
    public function jsonSerialize() {
        $result = array();
        if (!is_null($this->id)) {
            $result["id"] = intval($this->id);
        }
        if (!is_null($this->eventid)) {
            $result["eventid"] = intval($this->eventid);
        }
        if (!is_null($this->seatrankid)) {
            $result["seatrankid"] = intval($this->seatrankid);
        }
        if (!is_null($this->name)) {
            $result["name"] = strval($this->name);
        }
        if (!is_null($this->amount)) {
            $result["amount"] = intval($this->amount);
        }

        return $result;
    }
}
