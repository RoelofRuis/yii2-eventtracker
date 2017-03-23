<?php

namespace branchonline\eventtracker;

use InvalidArgumentException;

/**
 * Represents timestamps as used by the tracker. Uses unix timestamp format with 4 decimal places of added precision.
 *
 * Fixes issues with possible int overflow on 32 bit systems and guarantees correct values.
 *
 * @author Roelof Ruis <roelof@branchonline.nl>
 */
final class TrackerTime {

    /** @var string Internally holds the tracker time as a string. */
    private $_value;

    /**
     * Returns the current timestamp in a tracker time format.
     *
     * @return TrackerTime
     */
    public static function getCurrent(): TrackerTime {
        $tracker_time = round(microtime(true) * 10000);
        return new TrackerTime($tracker_time);
    }

    /**
     * Instantiate a new tracker time from a given Unix timestamp.
     *
     * @param int|string $timestamp The given Unix timestamp.
     * @return TrackerTime The tracker time instance.
     */
    public static function fromUnixTimestamp($timestamp): TrackerTime {
        $tracker_time = ((string) $timestamp) . '0000';
        return new TrackerTime($tracker_time);
    }

    /**
     * Instantiates a new tracker time from a given tracker timestamp. When you have a UNIX timestamp instead of a
     * tracker time use the [[fromUnixTimestamp()]] function.
     *
     * @param string $timestamp The tracker timestamp.
     * @return TrackerTime The tracker time instance.
     */
    public static function fromTrackerTimestamp(string $timestamp): TrackerTime {
        return new TrackerTime($timestamp);
    }

    /**
     * Construct a new tracker time from a string value. Use the public instantiation methods of this object to perform
     * actual construction.
     *
     * @param string $value The tracker timestamp value given as a string. Should be given as an integer value in string
     * format, without any leading zero's.
     * @throws InvalidArgumentException Whenever an invalid tracker time is given.
     */
    private function __construct(string $value) {
        if (!$this->_isTimestampValid($value)) {
            throw new InvalidArgumentException('Invalid tracker time value given.');
        }

        $this->_value = $value;
    }

    /** @return string The string timestamp wrapped by this instance. */
    public function getValue(): string {
        return $this->_value;
    }

    /**
     * Checks whether the given timestamp value matches the required format.
     *
     * @param string $value The timestamp value to check.
     * @return bool Whether the timestamp is valid.
     */
    private function _isTimestampValid(string $value): bool {
        return (1 === preg_match('/^[1-9][0-9]*$/', $value));
    }

}