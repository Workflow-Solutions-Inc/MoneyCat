<?php
/**
 * CreditNote
 *
 * PHP version 5
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Accounting API
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * OpenAPI spec version: 2.0.0
 * Contact: api@xero.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 4.0.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace XeroAPI\XeroPHP\Models\Accounting;

use \ArrayAccess;
use \XeroAPI\XeroPHP\AccountingObjectSerializer;

/**
 * CreditNote Class Doc Comment
 *
 * @category Class
 * @package  XeroAPI\XeroPHP
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class CreditNote implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CreditNote';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'type' => 'string',
        'contact' => '\XeroAPI\XeroPHP\Models\Accounting\Contact',
        'date' => '\DateTime',
        'status' => 'string',
        'line_amount_types' => '\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes',
        'line_items' => '\XeroAPI\XeroPHP\Models\Accounting\LineItem[]',
        'sub_total' => 'double',
        'total_tax' => 'double',
        'total' => 'double',
        'updated_date_utc' => '\DateTime',
        'currency_code' => '\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode',
        'fully_paid_on_date' => '\DateTime',
        'credit_note_id' => 'string',
        'credit_note_number' => 'string',
        'reference' => 'string',
        'sent_to_contact' => 'bool',
        'currency_rate' => 'double',
        'remaining_credit' => 'double',
        'allocations' => '\XeroAPI\XeroPHP\Models\Accounting\Allocation[]',
        'payments' => '\XeroAPI\XeroPHP\Models\Accounting\Payment[]',
        'branding_theme_id' => 'string',
        'has_attachments' => 'bool',
        'validation_errors' => '\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'type' => null,
        'contact' => null,
        'date' => 'date',
        'status' => null,
        'line_amount_types' => null,
        'line_items' => null,
        'sub_total' => 'double',
        'total_tax' => 'double',
        'total' => 'double',
        'updated_date_utc' => 'date-time',
        'currency_code' => null,
        'fully_paid_on_date' => 'date',
        'credit_note_id' => 'uuid',
        'credit_note_number' => null,
        'reference' => null,
        'sent_to_contact' => null,
        'currency_rate' => 'double',
        'remaining_credit' => 'double',
        'allocations' => null,
        'payments' => null,
        'branding_theme_id' => 'uuid',
        'has_attachments' => null,
        'validation_errors' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'type' => 'Type',
        'contact' => 'Contact',
        'date' => 'Date',
        'status' => 'Status',
        'line_amount_types' => 'LineAmountTypes',
        'line_items' => 'LineItems',
        'sub_total' => 'SubTotal',
        'total_tax' => 'TotalTax',
        'total' => 'Total',
        'updated_date_utc' => 'UpdatedDateUTC',
        'currency_code' => 'CurrencyCode',
        'fully_paid_on_date' => 'FullyPaidOnDate',
        'credit_note_id' => 'CreditNoteID',
        'credit_note_number' => 'CreditNoteNumber',
        'reference' => 'Reference',
        'sent_to_contact' => 'SentToContact',
        'currency_rate' => 'CurrencyRate',
        'remaining_credit' => 'RemainingCredit',
        'allocations' => 'Allocations',
        'payments' => 'Payments',
        'branding_theme_id' => 'BrandingThemeID',
        'has_attachments' => 'HasAttachments',
        'validation_errors' => 'ValidationErrors'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'contact' => 'setContact',
        'date' => 'setDate',
        'status' => 'setStatus',
        'line_amount_types' => 'setLineAmountTypes',
        'line_items' => 'setLineItems',
        'sub_total' => 'setSubTotal',
        'total_tax' => 'setTotalTax',
        'total' => 'setTotal',
        'updated_date_utc' => 'setUpdatedDateUtc',
        'currency_code' => 'setCurrencyCode',
        'fully_paid_on_date' => 'setFullyPaidOnDate',
        'credit_note_id' => 'setCreditNoteId',
        'credit_note_number' => 'setCreditNoteNumber',
        'reference' => 'setReference',
        'sent_to_contact' => 'setSentToContact',
        'currency_rate' => 'setCurrencyRate',
        'remaining_credit' => 'setRemainingCredit',
        'allocations' => 'setAllocations',
        'payments' => 'setPayments',
        'branding_theme_id' => 'setBrandingThemeId',
        'has_attachments' => 'setHasAttachments',
        'validation_errors' => 'setValidationErrors'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'contact' => 'getContact',
        'date' => 'getDate',
        'status' => 'getStatus',
        'line_amount_types' => 'getLineAmountTypes',
        'line_items' => 'getLineItems',
        'sub_total' => 'getSubTotal',
        'total_tax' => 'getTotalTax',
        'total' => 'getTotal',
        'updated_date_utc' => 'getUpdatedDateUtc',
        'currency_code' => 'getCurrencyCode',
        'fully_paid_on_date' => 'getFullyPaidOnDate',
        'credit_note_id' => 'getCreditNoteId',
        'credit_note_number' => 'getCreditNoteNumber',
        'reference' => 'getReference',
        'sent_to_contact' => 'getSentToContact',
        'currency_rate' => 'getCurrencyRate',
        'remaining_credit' => 'getRemainingCredit',
        'allocations' => 'getAllocations',
        'payments' => 'getPayments',
        'branding_theme_id' => 'getBrandingThemeId',
        'has_attachments' => 'getHasAttachments',
        'validation_errors' => 'getValidationErrors'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    const TYPE_ACCPAYCREDIT = 'ACCPAYCREDIT';
    const TYPE_ACCRECCREDIT = 'ACCRECCREDIT';
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_SUBMITTED = 'SUBMITTED';
    const STATUS_DELETED = 'DELETED';
    const STATUS_AUTHORISED = 'AUTHORISED';
    const STATUS_PAID = 'PAID';
    const STATUS_VOIDED = 'VOIDED';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_ACCPAYCREDIT,
            self::TYPE_ACCRECCREDIT,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_DELETED,
            self::STATUS_AUTHORISED,
            self::STATUS_PAID,
            self::STATUS_VOIDED,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['contact'] = isset($data['contact']) ? $data['contact'] : null;
        $this->container['date'] = isset($data['date']) ? $data['date'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['line_amount_types'] = isset($data['line_amount_types']) ? $data['line_amount_types'] : null;
        $this->container['line_items'] = isset($data['line_items']) ? $data['line_items'] : null;
        $this->container['sub_total'] = isset($data['sub_total']) ? $data['sub_total'] : null;
        $this->container['total_tax'] = isset($data['total_tax']) ? $data['total_tax'] : null;
        $this->container['total'] = isset($data['total']) ? $data['total'] : null;
        $this->container['updated_date_utc'] = isset($data['updated_date_utc']) ? $data['updated_date_utc'] : null;
        $this->container['currency_code'] = isset($data['currency_code']) ? $data['currency_code'] : null;
        $this->container['fully_paid_on_date'] = isset($data['fully_paid_on_date']) ? $data['fully_paid_on_date'] : null;
        $this->container['credit_note_id'] = isset($data['credit_note_id']) ? $data['credit_note_id'] : null;
        $this->container['credit_note_number'] = isset($data['credit_note_number']) ? $data['credit_note_number'] : null;
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['sent_to_contact'] = isset($data['sent_to_contact']) ? $data['sent_to_contact'] : null;
        $this->container['currency_rate'] = isset($data['currency_rate']) ? $data['currency_rate'] : null;
        $this->container['remaining_credit'] = isset($data['remaining_credit']) ? $data['remaining_credit'] : null;
        $this->container['allocations'] = isset($data['allocations']) ? $data['allocations'] : null;
        $this->container['payments'] = isset($data['payments']) ? $data['payments'] : null;
        $this->container['branding_theme_id'] = isset($data['branding_theme_id']) ? $data['branding_theme_id'] : null;
        $this->container['has_attachments'] = isset($data['has_attachments']) ? $data['has_attachments'] : null;
        $this->container['validation_errors'] = isset($data['validation_errors']) ? $data['validation_errors'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'status', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string|null $type See Credit Note Types
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($type) && !in_array($type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets contact
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\Contact|null
     */
    public function getContact()
    {
        return $this->container['contact'];
    }

    /**
     * Sets contact
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\Contact|null $contact contact
     *
     * @return $this
     */
    public function setContact($contact)
    {
        $this->container['contact'] = $contact;

        return $this;
    }

    /**
     * Gets date
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->container['date'];
    }

    /**
     * Sets date
     *
     * @param \DateTime|null $date The date the credit note is issued YYYY-MM-DD. If the Date element is not specified then it will default to the current date based on the timezone setting of the organisation
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->container['date'] = $date;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string|null $status See Credit Note Status Codes
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($status) && !in_array($status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'status', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets line_amount_types
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes|null
     */
    public function getLineAmountTypes()
    {
        return $this->container['line_amount_types'];
    }

    /**
     * Sets line_amount_types
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes|null $line_amount_types line_amount_types
     *
     * @return $this
     */
    public function setLineAmountTypes($line_amount_types)
    {
        $this->container['line_amount_types'] = $line_amount_types;

        return $this;
    }

    /**
     * Gets line_items
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\LineItem[]|null
     */
    public function getLineItems()
    {
        return $this->container['line_items'];
    }

    /**
     * Sets line_items
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\LineItem[]|null $line_items See Invoice Line Items
     *
     * @return $this
     */
    public function setLineItems($line_items)
    {
        $this->container['line_items'] = $line_items;

        return $this;
    }

    /**
     * Gets sub_total
     *
     * @return double|null
     */
    public function getSubTotal()
    {
        return $this->container['sub_total'];
    }

    /**
     * Sets sub_total
     *
     * @param double|null $sub_total The subtotal of the credit note excluding taxes
     *
     * @return $this
     */
    public function setSubTotal($sub_total)
    {
        $this->container['sub_total'] = $sub_total;

        return $this;
    }

    /**
     * Gets total_tax
     *
     * @return double|null
     */
    public function getTotalTax()
    {
        return $this->container['total_tax'];
    }

    /**
     * Sets total_tax
     *
     * @param double|null $total_tax The total tax on the credit note
     *
     * @return $this
     */
    public function setTotalTax($total_tax)
    {
        $this->container['total_tax'] = $total_tax;

        return $this;
    }

    /**
     * Gets total
     *
     * @return double|null
     */
    public function getTotal()
    {
        return $this->container['total'];
    }

    /**
     * Sets total
     *
     * @param double|null $total The total of the Credit Note(subtotal + total tax)
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->container['total'] = $total;

        return $this;
    }

    /**
     * Gets updated_date_utc
     *
     * @return \DateTime|null
     */
    public function getUpdatedDateUtc()
    {
        return $this->container['updated_date_utc'];
    }

    /**
     * Sets updated_date_utc
     *
     * @param \DateTime|null $updated_date_utc UTC timestamp of last update to the credit note
     *
     * @return $this
     */
    public function setUpdatedDateUtc($updated_date_utc)
    {
        $this->container['updated_date_utc'] = $updated_date_utc;

        return $this;
    }

    /**
     * Gets currency_code
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\CurrencyCode|null
     */
    public function getCurrencyCode()
    {
        return $this->container['currency_code'];
    }

    /**
     * Sets currency_code
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\CurrencyCode|null $currency_code currency_code
     *
     * @return $this
     */
    public function setCurrencyCode($currency_code)
    {
        $this->container['currency_code'] = $currency_code;

        return $this;
    }

    /**
     * Gets fully_paid_on_date
     *
     * @return \DateTime|null
     */
    public function getFullyPaidOnDate()
    {
        return $this->container['fully_paid_on_date'];
    }

    /**
     * Sets fully_paid_on_date
     *
     * @param \DateTime|null $fully_paid_on_date Date when credit note was fully paid(UTC format)
     *
     * @return $this
     */
    public function setFullyPaidOnDate($fully_paid_on_date)
    {
        $this->container['fully_paid_on_date'] = $fully_paid_on_date;

        return $this;
    }

    /**
     * Gets credit_note_id
     *
     * @return string|null
     */
    public function getCreditNoteId()
    {
        return $this->container['credit_note_id'];
    }

    /**
     * Sets credit_note_id
     *
     * @param string|null $credit_note_id Xero generated unique identifier
     *
     * @return $this
     */
    public function setCreditNoteId($credit_note_id)
    {
        $this->container['credit_note_id'] = $credit_note_id;

        return $this;
    }

    /**
     * Gets credit_note_number
     *
     * @return string|null
     */
    public function getCreditNoteNumber()
    {
        return $this->container['credit_note_number'];
    }

    /**
     * Sets credit_note_number
     *
     * @param string|null $credit_note_number ACCRECCREDIT – Unique alpha numeric code identifying credit note (when missing will auto-generate from your Organisation Invoice Settings)
     *
     * @return $this
     */
    public function setCreditNoteNumber($credit_note_number)
    {
        $this->container['credit_note_number'] = $credit_note_number;

        return $this;
    }

    /**
     * Gets reference
     *
     * @return string|null
     */
    public function getReference()
    {
        return $this->container['reference'];
    }

    /**
     * Sets reference
     *
     * @param string|null $reference ACCRECCREDIT only – additional reference number
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->container['reference'] = $reference;

        return $this;
    }

    /**
     * Gets sent_to_contact
     *
     * @return bool|null
     */
    public function getSentToContact()
    {
        return $this->container['sent_to_contact'];
    }

    /**
     * Sets sent_to_contact
     *
     * @param bool|null $sent_to_contact boolean to indicate if a credit note has been sent to a contact via  the Xero app (currently read only)
     *
     * @return $this
     */
    public function setSentToContact($sent_to_contact)
    {
        $this->container['sent_to_contact'] = $sent_to_contact;

        return $this;
    }

    /**
     * Gets currency_rate
     *
     * @return double|null
     */
    public function getCurrencyRate()
    {
        return $this->container['currency_rate'];
    }

    /**
     * Sets currency_rate
     *
     * @param double|null $currency_rate The currency rate for a multicurrency invoice. If no rate is specified, the XE.com day rate is used
     *
     * @return $this
     */
    public function setCurrencyRate($currency_rate)
    {
        $this->container['currency_rate'] = $currency_rate;

        return $this;
    }

    /**
     * Gets remaining_credit
     *
     * @return double|null
     */
    public function getRemainingCredit()
    {
        return $this->container['remaining_credit'];
    }

    /**
     * Sets remaining_credit
     *
     * @param double|null $remaining_credit The remaining credit balance on the Credit Note
     *
     * @return $this
     */
    public function setRemainingCredit($remaining_credit)
    {
        $this->container['remaining_credit'] = $remaining_credit;

        return $this;
    }

    /**
     * Gets allocations
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\Allocation[]|null
     */
    public function getAllocations()
    {
        return $this->container['allocations'];
    }

    /**
     * Sets allocations
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\Allocation[]|null $allocations See Allocations
     *
     * @return $this
     */
    public function setAllocations($allocations)
    {
        $this->container['allocations'] = $allocations;

        return $this;
    }

    /**
     * Gets payments
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\Payment[]|null
     */
    public function getPayments()
    {
        return $this->container['payments'];
    }

    /**
     * Sets payments
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\Payment[]|null $payments See Payments
     *
     * @return $this
     */
    public function setPayments($payments)
    {
        $this->container['payments'] = $payments;

        return $this;
    }

    /**
     * Gets branding_theme_id
     *
     * @return string|null
     */
    public function getBrandingThemeId()
    {
        return $this->container['branding_theme_id'];
    }

    /**
     * Sets branding_theme_id
     *
     * @param string|null $branding_theme_id See BrandingThemes
     *
     * @return $this
     */
    public function setBrandingThemeId($branding_theme_id)
    {
        $this->container['branding_theme_id'] = $branding_theme_id;

        return $this;
    }

    /**
     * Gets has_attachments
     *
     * @return bool|null
     */
    public function getHasAttachments()
    {
        return $this->container['has_attachments'];
    }

    /**
     * Sets has_attachments
     *
     * @param bool|null $has_attachments boolean to indicate if a credit note has an attachment
     *
     * @return $this
     */
    public function setHasAttachments($has_attachments)
    {
        $this->container['has_attachments'] = $has_attachments;

        return $this;
    }

    /**
     * Gets validation_errors
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\ValidationError[]|null
     */
    public function getValidationErrors()
    {
        return $this->container['validation_errors'];
    }

    /**
     * Sets validation_errors
     *
     * @param \XeroAPI\XeroPHP\Models\Accounting\ValidationError[]|null $validation_errors Displays array of validation error messages from the API
     *
     * @return $this
     */
    public function setValidationErrors($validation_errors)
    {
        $this->container['validation_errors'] = $validation_errors;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            AccountingObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}


