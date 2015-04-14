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

class UpdateEventLocation implements \jsonSerializable
{
    /**
     * Create a new UpdateEventLocation
     *
     * @param array $data
     */
    public function __construct(array $data = array()) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Name of the location
     *
     * @var string
     */
    public $name;

    /**
     * Street name
     *
     * @var string
     */
    public $street1;

    /**
     * Nr. + Box
     *
     * @var string
     */
    public $street2;

    /**
     * Zipcode
     *
     * @var string
     */
    public $zip;

    /**
     * City
     *
     * @var string
     */
    public $city;

    /**
     * Country code. Should be an ISO 3166-1 alpha-2 (http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * two-letter code.
     *
     * @var string
     */
    public $countrycode;

    /**
     * Unpack UpdateEventLocation from JSON.
     *
     * @param object $obj
     *
     * @return \Ticketmatic\Model\UpdateEventLocation
     */
    public static function fromJson($obj) {
        return new UpdateEventLocation(array(
            "name" => $obj->name,
            "street1" => $obj->street1,
            "street2" => $obj->street2,
            "zip" => $obj->zip,
            "city" => $obj->city,
            "countrycode" => $obj->countrycode,
        ));
    }

    /**
     * Serialize UpdateEventLocation to JSON.
     *
     * @return array
     */
    public function jsonSerialize() {
        $result = array();
        foreach ($fields as $field) {
            if (!is_null($this->name)) {
                $result["name"] = strval($this->name);
            }
            if (!is_null($this->street1)) {
                $result["street1"] = strval($this->street1);
            }
            if (!is_null($this->street2)) {
                $result["street2"] = strval($this->street2);
            }
            if (!is_null($this->zip)) {
                $result["zip"] = strval($this->zip);
            }
            if (!is_null($this->city)) {
                $result["city"] = strval($this->city);
            }
            if (!is_null($this->countrycode)) {
                $result["countrycode"] = strval($this->countrycode);
            }

        }
        return $result;
    }
}
