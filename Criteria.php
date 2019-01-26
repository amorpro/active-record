<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 25.04.2017
 * Time: 17:44
 */
namespace ActiveRecord;

class Criteria {

    /** Comparison type. */
    const EQUAL = "=";

    /** Comparison type. */
    const NOT_EQUAL = "<>";

    /** Comparison type. */
    const ALT_NOT_EQUAL = "!=";

    /** Comparison type. */
    const GREATER_THAN = ">";

    /** Comparison type. */
    const LESS_THAN = "<";

    /** Comparison type. */
    const GREATER_EQUAL = ">=";

    /** Comparison type. */
    const LESS_EQUAL = "<=";

    /** Comparison type. */
    const LIKE = " LIKE ";

    /** Comparison type. */
    const NOT_LIKE = " NOT LIKE ";

    /** Comparison type. */
    const CUSTOM = "CUSTOM";

    /** Comparison type. */
    const IN = " IN ";

    /** Comparison type. */
    const NOT_IN = " NOT IN ";

    /** "IS NULL" null comparison */
    const ISNULL = " IS NULL ";

    /** "IS NOT NULL" null comparison */
    const ISNOTNULL = " IS NOT NULL ";

    const ASC = SORT_ASC;
    const DESC = SORT_DESC;


}