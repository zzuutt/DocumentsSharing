<?php

namespace DocumentsSharing\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use DocumentsSharing\Model\DocumentsSharing as ChildDocumentsSharing;
use DocumentsSharing\Model\DocumentsSharingGroupe as ChildDocumentsSharingGroupe;
use DocumentsSharing\Model\DocumentsSharingGroupeQuery as ChildDocumentsSharingGroupeQuery;
use DocumentsSharing\Model\DocumentsSharingI18n as ChildDocumentsSharingI18n;
use DocumentsSharing\Model\DocumentsSharingI18nQuery as ChildDocumentsSharingI18nQuery;
use DocumentsSharing\Model\DocumentsSharingQuery as ChildDocumentsSharingQuery;
use DocumentsSharing\Model\Map\DocumentsSharingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Thelia\Model\Customer as ChildCustomer;
use Thelia\Model\CustomerQuery;

abstract class DocumentsSharing implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DocumentsSharing\\Model\\Map\\DocumentsSharingTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the share_key field.
     * @var        string
     */
    protected $share_key;

    /**
     * The value for the document_id field.
     * @var        string
     */
    protected $document_id;

    /**
     * The value for the customer_id field.
     * @var        int
     */
    protected $customer_id;

    /**
     * The value for the groupe_id field.
     * @var        int
     */
    protected $groupe_id;

    /**
     * The value for the date_end_share field.
     * @var        string
     */
    protected $date_end_share;

    /**
     * The value for the date_last_download field.
     * @var        string
     */
    protected $date_last_download;

    /**
     * The value for the delete_file_after field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $delete_file_after;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        Customer
     */
    protected $aCustomer;

    /**
     * @var        DocumentsSharingGroupe
     */
    protected $aDocumentsSharingGroupe;

    /**
     * @var        ObjectCollection|ChildDocumentsSharingI18n[] Collection to store aggregation of ChildDocumentsSharingI18n objects.
     */
    protected $collDocumentsSharingI18ns;
    protected $collDocumentsSharingI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildDocumentsSharingI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $documentsSharingI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->delete_file_after = 0;
    }

    /**
     * Initializes internal state of DocumentsSharing\Model\Base\DocumentsSharing object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>DocumentsSharing</code> instance.  If
     * <code>obj</code> is an instance of <code>DocumentsSharing</code>, delegates to
     * <code>equals(DocumentsSharing)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return DocumentsSharing The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return DocumentsSharing The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [share_key] column value.
     *
     * @return   string
     */
    public function getShareKey()
    {

        return $this->share_key;
    }

    /**
     * Get the [document_id] column value.
     *
     * @return   string
     */
    public function getDocumentId()
    {

        return $this->document_id;
    }

    /**
     * Get the [customer_id] column value.
     *
     * @return   int
     */
    public function getCustomerId()
    {

        return $this->customer_id;
    }

    /**
     * Get the [groupe_id] column value.
     *
     * @return   int
     */
    public function getGroupeId()
    {

        return $this->groupe_id;
    }

    /**
     * Get the [optionally formatted] temporal [date_end_share] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateEndShare($format = NULL)
    {
        if ($format === null) {
            return $this->date_end_share;
        } else {
            return $this->date_end_share instanceof \DateTime ? $this->date_end_share->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [date_last_download] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateLastDownload($format = NULL)
    {
        if ($format === null) {
            return $this->date_last_download;
        } else {
            return $this->date_last_download instanceof \DateTime ? $this->date_last_download->format($format) : null;
        }
    }

    /**
     * Get the [delete_file_after] column value.
     *
     * @return   int
     */
    public function getDeleteFileAfter()
    {

        return $this->delete_file_after;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [share_key] column.
     *
     * @param      string $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setShareKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->share_key !== $v) {
            $this->share_key = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::SHARE_KEY] = true;
        }


        return $this;
    } // setShareKey()

    /**
     * Set the value of [document_id] column.
     *
     * @param      string $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setDocumentId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->document_id !== $v) {
            $this->document_id = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::DOCUMENT_ID] = true;
        }


        return $this;
    } // setDocumentId()

    /**
     * Set the value of [customer_id] column.
     *
     * @param      int $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_id !== $v) {
            $this->customer_id = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::CUSTOMER_ID] = true;
        }

        if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
            $this->aCustomer = null;
        }


        return $this;
    } // setCustomerId()

    /**
     * Set the value of [groupe_id] column.
     *
     * @param      int $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setGroupeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->groupe_id !== $v) {
            $this->groupe_id = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::GROUPE_ID] = true;
        }

        if ($this->aDocumentsSharingGroupe !== null && $this->aDocumentsSharingGroupe->getId() !== $v) {
            $this->aDocumentsSharingGroupe = null;
        }


        return $this;
    } // setGroupeId()

    /**
     * Sets the value of [date_end_share] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setDateEndShare($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->date_end_share !== null || $dt !== null) {
            if ($dt !== $this->date_end_share) {
                $this->date_end_share = $dt;
                $this->modifiedColumns[DocumentsSharingTableMap::DATE_END_SHARE] = true;
            }
        } // if either are not null


        return $this;
    } // setDateEndShare()

    /**
     * Sets the value of [date_last_download] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setDateLastDownload($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->date_last_download !== null || $dt !== null) {
            if ($dt !== $this->date_last_download) {
                $this->date_last_download = $dt;
                $this->modifiedColumns[DocumentsSharingTableMap::DATE_LAST_DOWNLOAD] = true;
            }
        } // if either are not null


        return $this;
    } // setDateLastDownload()

    /**
     * Set the value of [delete_file_after] column.
     *
     * @param      int $v new value
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setDeleteFileAfter($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->delete_file_after !== $v) {
            $this->delete_file_after = $v;
            $this->modifiedColumns[DocumentsSharingTableMap::DELETE_FILE_AFTER] = true;
        }


        return $this;
    } // setDeleteFileAfter()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[DocumentsSharingTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[DocumentsSharingTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->delete_file_after !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : DocumentsSharingTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : DocumentsSharingTableMap::translateFieldName('ShareKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->share_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : DocumentsSharingTableMap::translateFieldName('DocumentId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->document_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : DocumentsSharingTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : DocumentsSharingTableMap::translateFieldName('GroupeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->groupe_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : DocumentsSharingTableMap::translateFieldName('DateEndShare', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date_end_share = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : DocumentsSharingTableMap::translateFieldName('DateLastDownload', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date_last_download = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : DocumentsSharingTableMap::translateFieldName('DeleteFileAfter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->delete_file_after = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : DocumentsSharingTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : DocumentsSharingTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = DocumentsSharingTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \DocumentsSharing\Model\DocumentsSharing object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCustomer !== null && $this->customer_id !== $this->aCustomer->getId()) {
            $this->aCustomer = null;
        }
        if ($this->aDocumentsSharingGroupe !== null && $this->groupe_id !== $this->aDocumentsSharingGroupe->getId()) {
            $this->aDocumentsSharingGroupe = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildDocumentsSharingQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCustomer = null;
            $this->aDocumentsSharingGroupe = null;
            $this->collDocumentsSharingI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see DocumentsSharing::setDeleted()
     * @see DocumentsSharing::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildDocumentsSharingQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(DocumentsSharingTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(DocumentsSharingTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(DocumentsSharingTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DocumentsSharingTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCustomer !== null) {
                if ($this->aCustomer->isModified() || $this->aCustomer->isNew()) {
                    $affectedRows += $this->aCustomer->save($con);
                }
                $this->setCustomer($this->aCustomer);
            }

            if ($this->aDocumentsSharingGroupe !== null) {
                if ($this->aDocumentsSharingGroupe->isModified() || $this->aDocumentsSharingGroupe->isNew()) {
                    $affectedRows += $this->aDocumentsSharingGroupe->save($con);
                }
                $this->setDocumentsSharingGroupe($this->aDocumentsSharingGroupe);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->documentsSharingI18nsScheduledForDeletion !== null) {
                if (!$this->documentsSharingI18nsScheduledForDeletion->isEmpty()) {
                    \DocumentsSharing\Model\DocumentsSharingI18nQuery::create()
                        ->filterByPrimaryKeys($this->documentsSharingI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->documentsSharingI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collDocumentsSharingI18ns !== null) {
            foreach ($this->collDocumentsSharingI18ns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[DocumentsSharingTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DocumentsSharingTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DocumentsSharingTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::SHARE_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'SHARE_KEY';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::DOCUMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'DOCUMENT_ID';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'CUSTOMER_ID';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::GROUPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'GROUPE_ID';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::DATE_END_SHARE)) {
            $modifiedColumns[':p' . $index++]  = 'DATE_END_SHARE';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD)) {
            $modifiedColumns[':p' . $index++]  = 'DATE_LAST_DOWNLOAD';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::DELETE_FILE_AFTER)) {
            $modifiedColumns[':p' . $index++]  = 'DELETE_FILE_AFTER';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(DocumentsSharingTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO documents_sharing (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'SHARE_KEY':
                        $stmt->bindValue($identifier, $this->share_key, PDO::PARAM_STR);
                        break;
                    case 'DOCUMENT_ID':
                        $stmt->bindValue($identifier, $this->document_id, PDO::PARAM_STR);
                        break;
                    case 'CUSTOMER_ID':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);
                        break;
                    case 'GROUPE_ID':
                        $stmt->bindValue($identifier, $this->groupe_id, PDO::PARAM_INT);
                        break;
                    case 'DATE_END_SHARE':
                        $stmt->bindValue($identifier, $this->date_end_share ? $this->date_end_share->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'DATE_LAST_DOWNLOAD':
                        $stmt->bindValue($identifier, $this->date_last_download ? $this->date_last_download->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'DELETE_FILE_AFTER':
                        $stmt->bindValue($identifier, $this->delete_file_after, PDO::PARAM_INT);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DocumentsSharingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getShareKey();
                break;
            case 2:
                return $this->getDocumentId();
                break;
            case 3:
                return $this->getCustomerId();
                break;
            case 4:
                return $this->getGroupeId();
                break;
            case 5:
                return $this->getDateEndShare();
                break;
            case 6:
                return $this->getDateLastDownload();
                break;
            case 7:
                return $this->getDeleteFileAfter();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['DocumentsSharing'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DocumentsSharing'][$this->getPrimaryKey()] = true;
        $keys = DocumentsSharingTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getShareKey(),
            $keys[2] => $this->getDocumentId(),
            $keys[3] => $this->getCustomerId(),
            $keys[4] => $this->getGroupeId(),
            $keys[5] => $this->getDateEndShare(),
            $keys[6] => $this->getDateLastDownload(),
            $keys[7] => $this->getDeleteFileAfter(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCustomer) {
                $result['Customer'] = $this->aCustomer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDocumentsSharingGroupe) {
                $result['DocumentsSharingGroupe'] = $this->aDocumentsSharingGroupe->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDocumentsSharingI18ns) {
                $result['DocumentsSharingI18ns'] = $this->collDocumentsSharingI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DocumentsSharingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setShareKey($value);
                break;
            case 2:
                $this->setDocumentId($value);
                break;
            case 3:
                $this->setCustomerId($value);
                break;
            case 4:
                $this->setGroupeId($value);
                break;
            case 5:
                $this->setDateEndShare($value);
                break;
            case 6:
                $this->setDateLastDownload($value);
                break;
            case 7:
                $this->setDeleteFileAfter($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = DocumentsSharingTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setShareKey($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDocumentId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCustomerId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setGroupeId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDateEndShare($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDateLastDownload($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setDeleteFileAfter($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DocumentsSharingTableMap::DATABASE_NAME);

        if ($this->isColumnModified(DocumentsSharingTableMap::ID)) $criteria->add(DocumentsSharingTableMap::ID, $this->id);
        if ($this->isColumnModified(DocumentsSharingTableMap::SHARE_KEY)) $criteria->add(DocumentsSharingTableMap::SHARE_KEY, $this->share_key);
        if ($this->isColumnModified(DocumentsSharingTableMap::DOCUMENT_ID)) $criteria->add(DocumentsSharingTableMap::DOCUMENT_ID, $this->document_id);
        if ($this->isColumnModified(DocumentsSharingTableMap::CUSTOMER_ID)) $criteria->add(DocumentsSharingTableMap::CUSTOMER_ID, $this->customer_id);
        if ($this->isColumnModified(DocumentsSharingTableMap::GROUPE_ID)) $criteria->add(DocumentsSharingTableMap::GROUPE_ID, $this->groupe_id);
        if ($this->isColumnModified(DocumentsSharingTableMap::DATE_END_SHARE)) $criteria->add(DocumentsSharingTableMap::DATE_END_SHARE, $this->date_end_share);
        if ($this->isColumnModified(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD)) $criteria->add(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD, $this->date_last_download);
        if ($this->isColumnModified(DocumentsSharingTableMap::DELETE_FILE_AFTER)) $criteria->add(DocumentsSharingTableMap::DELETE_FILE_AFTER, $this->delete_file_after);
        if ($this->isColumnModified(DocumentsSharingTableMap::CREATED_AT)) $criteria->add(DocumentsSharingTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(DocumentsSharingTableMap::UPDATED_AT)) $criteria->add(DocumentsSharingTableMap::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(DocumentsSharingTableMap::DATABASE_NAME);
        $criteria->add(DocumentsSharingTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \DocumentsSharing\Model\DocumentsSharing (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setShareKey($this->getShareKey());
        $copyObj->setDocumentId($this->getDocumentId());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setGroupeId($this->getGroupeId());
        $copyObj->setDateEndShare($this->getDateEndShare());
        $copyObj->setDateLastDownload($this->getDateLastDownload());
        $copyObj->setDeleteFileAfter($this->getDeleteFileAfter());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDocumentsSharingI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDocumentsSharingI18n($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \DocumentsSharing\Model\DocumentsSharing Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildCustomer object.
     *
     * @param                  ChildCustomer $v
     * @return                 \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCustomer(ChildCustomer $v = null)
    {
        if ($v === null) {
            $this->setCustomerId(NULL);
        } else {
            $this->setCustomerId($v->getId());
        }

        $this->aCustomer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCustomer object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentsSharing($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCustomer object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildCustomer The associated ChildCustomer object.
     * @throws PropelException
     */
    public function getCustomer(ConnectionInterface $con = null)
    {
        if ($this->aCustomer === null && ($this->customer_id !== null)) {
            $this->aCustomer = CustomerQuery::create()->findPk($this->customer_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCustomer->addDocumentsSharings($this);
             */
        }

        return $this->aCustomer;
    }

    /**
     * Declares an association between this object and a ChildDocumentsSharingGroupe object.
     *
     * @param                  ChildDocumentsSharingGroupe $v
     * @return                 \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDocumentsSharingGroupe(ChildDocumentsSharingGroupe $v = null)
    {
        if ($v === null) {
            $this->setGroupeId(NULL);
        } else {
            $this->setGroupeId($v->getId());
        }

        $this->aDocumentsSharingGroupe = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDocumentsSharingGroupe object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentsSharing($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDocumentsSharingGroupe object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildDocumentsSharingGroupe The associated ChildDocumentsSharingGroupe object.
     * @throws PropelException
     */
    public function getDocumentsSharingGroupe(ConnectionInterface $con = null)
    {
        if ($this->aDocumentsSharingGroupe === null && ($this->groupe_id !== null)) {
            $this->aDocumentsSharingGroupe = ChildDocumentsSharingGroupeQuery::create()->findPk($this->groupe_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDocumentsSharingGroupe->addDocumentsSharings($this);
             */
        }

        return $this->aDocumentsSharingGroupe;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('DocumentsSharingI18n' == $relationName) {
            return $this->initDocumentsSharingI18ns();
        }
    }

    /**
     * Clears out the collDocumentsSharingI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDocumentsSharingI18ns()
     */
    public function clearDocumentsSharingI18ns()
    {
        $this->collDocumentsSharingI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDocumentsSharingI18ns collection loaded partially.
     */
    public function resetPartialDocumentsSharingI18ns($v = true)
    {
        $this->collDocumentsSharingI18nsPartial = $v;
    }

    /**
     * Initializes the collDocumentsSharingI18ns collection.
     *
     * By default this just sets the collDocumentsSharingI18ns collection to an empty array (like clearcollDocumentsSharingI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocumentsSharingI18ns($overrideExisting = true)
    {
        if (null !== $this->collDocumentsSharingI18ns && !$overrideExisting) {
            return;
        }
        $this->collDocumentsSharingI18ns = new ObjectCollection();
        $this->collDocumentsSharingI18ns->setModel('\DocumentsSharing\Model\DocumentsSharingI18n');
    }

    /**
     * Gets an array of ChildDocumentsSharingI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDocumentsSharing is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildDocumentsSharingI18n[] List of ChildDocumentsSharingI18n objects
     * @throws PropelException
     */
    public function getDocumentsSharingI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDocumentsSharingI18nsPartial && !$this->isNew();
        if (null === $this->collDocumentsSharingI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocumentsSharingI18ns) {
                // return empty collection
                $this->initDocumentsSharingI18ns();
            } else {
                $collDocumentsSharingI18ns = ChildDocumentsSharingI18nQuery::create(null, $criteria)
                    ->filterByDocumentsSharing($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDocumentsSharingI18nsPartial && count($collDocumentsSharingI18ns)) {
                        $this->initDocumentsSharingI18ns(false);

                        foreach ($collDocumentsSharingI18ns as $obj) {
                            if (false == $this->collDocumentsSharingI18ns->contains($obj)) {
                                $this->collDocumentsSharingI18ns->append($obj);
                            }
                        }

                        $this->collDocumentsSharingI18nsPartial = true;
                    }

                    reset($collDocumentsSharingI18ns);

                    return $collDocumentsSharingI18ns;
                }

                if ($partial && $this->collDocumentsSharingI18ns) {
                    foreach ($this->collDocumentsSharingI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collDocumentsSharingI18ns[] = $obj;
                        }
                    }
                }

                $this->collDocumentsSharingI18ns = $collDocumentsSharingI18ns;
                $this->collDocumentsSharingI18nsPartial = false;
            }
        }

        return $this->collDocumentsSharingI18ns;
    }

    /**
     * Sets a collection of DocumentsSharingI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $documentsSharingI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildDocumentsSharing The current object (for fluent API support)
     */
    public function setDocumentsSharingI18ns(Collection $documentsSharingI18ns, ConnectionInterface $con = null)
    {
        $documentsSharingI18nsToDelete = $this->getDocumentsSharingI18ns(new Criteria(), $con)->diff($documentsSharingI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->documentsSharingI18nsScheduledForDeletion = clone $documentsSharingI18nsToDelete;

        foreach ($documentsSharingI18nsToDelete as $documentsSharingI18nRemoved) {
            $documentsSharingI18nRemoved->setDocumentsSharing(null);
        }

        $this->collDocumentsSharingI18ns = null;
        foreach ($documentsSharingI18ns as $documentsSharingI18n) {
            $this->addDocumentsSharingI18n($documentsSharingI18n);
        }

        $this->collDocumentsSharingI18ns = $documentsSharingI18ns;
        $this->collDocumentsSharingI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DocumentsSharingI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DocumentsSharingI18n objects.
     * @throws PropelException
     */
    public function countDocumentsSharingI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDocumentsSharingI18nsPartial && !$this->isNew();
        if (null === $this->collDocumentsSharingI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocumentsSharingI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocumentsSharingI18ns());
            }

            $query = ChildDocumentsSharingI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDocumentsSharing($this)
                ->count($con);
        }

        return count($this->collDocumentsSharingI18ns);
    }

    /**
     * Method called to associate a ChildDocumentsSharingI18n object to this object
     * through the ChildDocumentsSharingI18n foreign key attribute.
     *
     * @param    ChildDocumentsSharingI18n $l ChildDocumentsSharingI18n
     * @return   \DocumentsSharing\Model\DocumentsSharing The current object (for fluent API support)
     */
    public function addDocumentsSharingI18n(ChildDocumentsSharingI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collDocumentsSharingI18ns === null) {
            $this->initDocumentsSharingI18ns();
            $this->collDocumentsSharingI18nsPartial = true;
        }

        if (!in_array($l, $this->collDocumentsSharingI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDocumentsSharingI18n($l);
        }

        return $this;
    }

    /**
     * @param DocumentsSharingI18n $documentsSharingI18n The documentsSharingI18n object to add.
     */
    protected function doAddDocumentsSharingI18n($documentsSharingI18n)
    {
        $this->collDocumentsSharingI18ns[]= $documentsSharingI18n;
        $documentsSharingI18n->setDocumentsSharing($this);
    }

    /**
     * @param  DocumentsSharingI18n $documentsSharingI18n The documentsSharingI18n object to remove.
     * @return ChildDocumentsSharing The current object (for fluent API support)
     */
    public function removeDocumentsSharingI18n($documentsSharingI18n)
    {
        if ($this->getDocumentsSharingI18ns()->contains($documentsSharingI18n)) {
            $this->collDocumentsSharingI18ns->remove($this->collDocumentsSharingI18ns->search($documentsSharingI18n));
            if (null === $this->documentsSharingI18nsScheduledForDeletion) {
                $this->documentsSharingI18nsScheduledForDeletion = clone $this->collDocumentsSharingI18ns;
                $this->documentsSharingI18nsScheduledForDeletion->clear();
            }
            $this->documentsSharingI18nsScheduledForDeletion[]= clone $documentsSharingI18n;
            $documentsSharingI18n->setDocumentsSharing(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->share_key = null;
        $this->document_id = null;
        $this->customer_id = null;
        $this->groupe_id = null;
        $this->date_end_share = null;
        $this->date_last_download = null;
        $this->delete_file_after = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collDocumentsSharingI18ns) {
                foreach ($this->collDocumentsSharingI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collDocumentsSharingI18ns = null;
        $this->aCustomer = null;
        $this->aDocumentsSharingGroupe = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DocumentsSharingTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildDocumentsSharing The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[DocumentsSharingTableMap::UPDATED_AT] = true;

        return $this;
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildDocumentsSharing The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildDocumentsSharingI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collDocumentsSharingI18ns) {
                foreach ($this->collDocumentsSharingI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildDocumentsSharingI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildDocumentsSharingI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addDocumentsSharingI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildDocumentsSharing The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildDocumentsSharingI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collDocumentsSharingI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collDocumentsSharingI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildDocumentsSharingI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return   string
         */
        public function getTitle()
        {
        return $this->getCurrentTranslation()->getTitle();
    }


        /**
         * Set the value of [title] column.
         *
         * @param      string $v new value
         * @return   \DocumentsSharing\Model\DocumentsSharingI18n The current object (for fluent API support)
         */
        public function setTitle($v)
        {    $this->getCurrentTranslation()->setTitle($v);

        return $this;
    }


        /**
         * Get the [description] column value.
         *
         * @return   string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param      string $v new value
         * @return   \DocumentsSharing\Model\DocumentsSharingI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
    }


        /**
         * Get the [chapo] column value.
         *
         * @return   string
         */
        public function getChapo()
        {
        return $this->getCurrentTranslation()->getChapo();
    }


        /**
         * Set the value of [chapo] column.
         *
         * @param      string $v new value
         * @return   \DocumentsSharing\Model\DocumentsSharingI18n The current object (for fluent API support)
         */
        public function setChapo($v)
        {    $this->getCurrentTranslation()->setChapo($v);

        return $this;
    }


        /**
         * Get the [postscriptum] column value.
         *
         * @return   string
         */
        public function getPostscriptum()
        {
        return $this->getCurrentTranslation()->getPostscriptum();
    }


        /**
         * Set the value of [postscriptum] column.
         *
         * @param      string $v new value
         * @return   \DocumentsSharing\Model\DocumentsSharingI18n The current object (for fluent API support)
         */
        public function setPostscriptum($v)
        {    $this->getCurrentTranslation()->setPostscriptum($v);

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
