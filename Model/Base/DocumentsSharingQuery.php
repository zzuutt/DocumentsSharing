<?php

namespace DocumentsSharing\Model\Base;

use \Exception;
use \PDO;
use DocumentsSharing\Model\DocumentsSharing as ChildDocumentsSharing;
use DocumentsSharing\Model\DocumentsSharingI18nQuery as ChildDocumentsSharingI18nQuery;
use DocumentsSharing\Model\DocumentsSharingQuery as ChildDocumentsSharingQuery;
use DocumentsSharing\Model\Map\DocumentsSharingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Customer;

/**
 * Base class that represents a query for the 'documents_sharing' table.
 *
 *
 *
 * @method     ChildDocumentsSharingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDocumentsSharingQuery orderByShareKey($order = Criteria::ASC) Order by the share_key column
 * @method     ChildDocumentsSharingQuery orderByDocumentId($order = Criteria::ASC) Order by the document_id column
 * @method     ChildDocumentsSharingQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildDocumentsSharingQuery orderByGroupeId($order = Criteria::ASC) Order by the groupe_id column
 * @method     ChildDocumentsSharingQuery orderByDateEndShare($order = Criteria::ASC) Order by the date_end_share column
 * @method     ChildDocumentsSharingQuery orderByDateLastDownload($order = Criteria::ASC) Order by the date_last_download column
 * @method     ChildDocumentsSharingQuery orderByDeleteFileAfter($order = Criteria::ASC) Order by the delete_file_after column
 * @method     ChildDocumentsSharingQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildDocumentsSharingQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildDocumentsSharingQuery groupById() Group by the id column
 * @method     ChildDocumentsSharingQuery groupByShareKey() Group by the share_key column
 * @method     ChildDocumentsSharingQuery groupByDocumentId() Group by the document_id column
 * @method     ChildDocumentsSharingQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildDocumentsSharingQuery groupByGroupeId() Group by the groupe_id column
 * @method     ChildDocumentsSharingQuery groupByDateEndShare() Group by the date_end_share column
 * @method     ChildDocumentsSharingQuery groupByDateLastDownload() Group by the date_last_download column
 * @method     ChildDocumentsSharingQuery groupByDeleteFileAfter() Group by the delete_file_after column
 * @method     ChildDocumentsSharingQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildDocumentsSharingQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildDocumentsSharingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDocumentsSharingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDocumentsSharingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDocumentsSharingQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildDocumentsSharingQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildDocumentsSharingQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildDocumentsSharingQuery leftJoinDocumentsSharingGroupe($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocumentsSharingGroupe relation
 * @method     ChildDocumentsSharingQuery rightJoinDocumentsSharingGroupe($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocumentsSharingGroupe relation
 * @method     ChildDocumentsSharingQuery innerJoinDocumentsSharingGroupe($relationAlias = null) Adds a INNER JOIN clause to the query using the DocumentsSharingGroupe relation
 *
 * @method     ChildDocumentsSharingQuery leftJoinDocumentsSharingI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocumentsSharingI18n relation
 * @method     ChildDocumentsSharingQuery rightJoinDocumentsSharingI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocumentsSharingI18n relation
 * @method     ChildDocumentsSharingQuery innerJoinDocumentsSharingI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the DocumentsSharingI18n relation
 *
 * @method     ChildDocumentsSharing findOne(ConnectionInterface $con = null) Return the first ChildDocumentsSharing matching the query
 * @method     ChildDocumentsSharing findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDocumentsSharing matching the query, or a new ChildDocumentsSharing object populated from the query conditions when no match is found
 *
 * @method     ChildDocumentsSharing findOneById(int $id) Return the first ChildDocumentsSharing filtered by the id column
 * @method     ChildDocumentsSharing findOneByShareKey(string $share_key) Return the first ChildDocumentsSharing filtered by the share_key column
 * @method     ChildDocumentsSharing findOneByDocumentId(string $document_id) Return the first ChildDocumentsSharing filtered by the document_id column
 * @method     ChildDocumentsSharing findOneByCustomerId(int $customer_id) Return the first ChildDocumentsSharing filtered by the customer_id column
 * @method     ChildDocumentsSharing findOneByGroupeId(int $groupe_id) Return the first ChildDocumentsSharing filtered by the groupe_id column
 * @method     ChildDocumentsSharing findOneByDateEndShare(string $date_end_share) Return the first ChildDocumentsSharing filtered by the date_end_share column
 * @method     ChildDocumentsSharing findOneByDateLastDownload(string $date_last_download) Return the first ChildDocumentsSharing filtered by the date_last_download column
 * @method     ChildDocumentsSharing findOneByDeleteFileAfter(int $delete_file_after) Return the first ChildDocumentsSharing filtered by the delete_file_after column
 * @method     ChildDocumentsSharing findOneByCreatedAt(string $created_at) Return the first ChildDocumentsSharing filtered by the created_at column
 * @method     ChildDocumentsSharing findOneByUpdatedAt(string $updated_at) Return the first ChildDocumentsSharing filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildDocumentsSharing objects filtered by the id column
 * @method     array findByShareKey(string $share_key) Return ChildDocumentsSharing objects filtered by the share_key column
 * @method     array findByDocumentId(string $document_id) Return ChildDocumentsSharing objects filtered by the document_id column
 * @method     array findByCustomerId(int $customer_id) Return ChildDocumentsSharing objects filtered by the customer_id column
 * @method     array findByGroupeId(int $groupe_id) Return ChildDocumentsSharing objects filtered by the groupe_id column
 * @method     array findByDateEndShare(string $date_end_share) Return ChildDocumentsSharing objects filtered by the date_end_share column
 * @method     array findByDateLastDownload(string $date_last_download) Return ChildDocumentsSharing objects filtered by the date_last_download column
 * @method     array findByDeleteFileAfter(int $delete_file_after) Return ChildDocumentsSharing objects filtered by the delete_file_after column
 * @method     array findByCreatedAt(string $created_at) Return ChildDocumentsSharing objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildDocumentsSharing objects filtered by the updated_at column
 *
 */
abstract class DocumentsSharingQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \DocumentsSharing\Model\Base\DocumentsSharingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\DocumentsSharing\\Model\\DocumentsSharing', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDocumentsSharingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDocumentsSharingQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \DocumentsSharing\Model\DocumentsSharingQuery) {
            return $criteria;
        }
        $query = new \DocumentsSharing\Model\DocumentsSharingQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDocumentsSharing|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DocumentsSharingTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildDocumentsSharing A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, SHARE_KEY, DOCUMENT_ID, CUSTOMER_ID, GROUPE_ID, DATE_END_SHARE, DATE_LAST_DOWNLOAD, DELETE_FILE_AFTER, CREATED_AT, UPDATED_AT FROM documents_sharing WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildDocumentsSharing();
            $obj->hydrate($row);
            DocumentsSharingTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildDocumentsSharing|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DocumentsSharingTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DocumentsSharingTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the share_key column
     *
     * Example usage:
     * <code>
     * $query->filterByShareKey('fooValue');   // WHERE share_key = 'fooValue'
     * $query->filterByShareKey('%fooValue%'); // WHERE share_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shareKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByShareKey($shareKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shareKey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shareKey)) {
                $shareKey = str_replace('*', '%', $shareKey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::SHARE_KEY, $shareKey, $comparison);
    }

    /**
     * Filter the query on the document_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDocumentId('fooValue');   // WHERE document_id = 'fooValue'
     * $query->filterByDocumentId('%fooValue%'); // WHERE document_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $documentId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDocumentId($documentId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($documentId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $documentId)) {
                $documentId = str_replace('*', '%', $documentId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::DOCUMENT_ID, $documentId, $comparison);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @see       filterByCustomer()
     *
     * @param     mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query on the groupe_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupeId(1234); // WHERE groupe_id = 1234
     * $query->filterByGroupeId(array(12, 34)); // WHERE groupe_id IN (12, 34)
     * $query->filterByGroupeId(array('min' => 12)); // WHERE groupe_id > 12
     * </code>
     *
     * @see       filterByDocumentsSharingGroupe()
     *
     * @param     mixed $groupeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByGroupeId($groupeId = null, $comparison = null)
    {
        if (is_array($groupeId)) {
            $useMinMax = false;
            if (isset($groupeId['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::GROUPE_ID, $groupeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupeId['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::GROUPE_ID, $groupeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::GROUPE_ID, $groupeId, $comparison);
    }

    /**
     * Filter the query on the date_end_share column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEndShare('2011-03-14'); // WHERE date_end_share = '2011-03-14'
     * $query->filterByDateEndShare('now'); // WHERE date_end_share = '2011-03-14'
     * $query->filterByDateEndShare(array('max' => 'yesterday')); // WHERE date_end_share > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEndShare The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDateEndShare($dateEndShare = null, $comparison = null)
    {
        if (is_array($dateEndShare)) {
            $useMinMax = false;
            if (isset($dateEndShare['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DATE_END_SHARE, $dateEndShare['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEndShare['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DATE_END_SHARE, $dateEndShare['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::DATE_END_SHARE, $dateEndShare, $comparison);
    }

    /**
     * Filter the query on the date_last_download column
     *
     * Example usage:
     * <code>
     * $query->filterByDateLastDownload('2011-03-14'); // WHERE date_last_download = '2011-03-14'
     * $query->filterByDateLastDownload('now'); // WHERE date_last_download = '2011-03-14'
     * $query->filterByDateLastDownload(array('max' => 'yesterday')); // WHERE date_last_download > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateLastDownload The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDateLastDownload($dateLastDownload = null, $comparison = null)
    {
        if (is_array($dateLastDownload)) {
            $useMinMax = false;
            if (isset($dateLastDownload['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD, $dateLastDownload['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateLastDownload['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD, $dateLastDownload['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::DATE_LAST_DOWNLOAD, $dateLastDownload, $comparison);
    }

    /**
     * Filter the query on the delete_file_after column
     *
     * Example usage:
     * <code>
     * $query->filterByDeleteFileAfter(1234); // WHERE delete_file_after = 1234
     * $query->filterByDeleteFileAfter(array(12, 34)); // WHERE delete_file_after IN (12, 34)
     * $query->filterByDeleteFileAfter(array('min' => 12)); // WHERE delete_file_after > 12
     * </code>
     *
     * @param     mixed $deleteFileAfter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDeleteFileAfter($deleteFileAfter = null, $comparison = null)
    {
        if (is_array($deleteFileAfter)) {
            $useMinMax = false;
            if (isset($deleteFileAfter['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DELETE_FILE_AFTER, $deleteFileAfter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deleteFileAfter['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::DELETE_FILE_AFTER, $deleteFileAfter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::DELETE_FILE_AFTER, $deleteFileAfter, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DocumentsSharingTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Customer object
     *
     * @param \Thelia\Model\Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = null)
    {
        if ($customer instanceof \Thelia\Model\Customer) {
            return $this
                ->addUsingAlias(DocumentsSharingTableMap::CUSTOMER_ID, $customer->getId(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentsSharingTableMap::CUSTOMER_ID, $customer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \Thelia\Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function joinCustomer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\Thelia\Model\CustomerQuery');
    }

    /**
     * Filter the query by a related \DocumentsSharing\Model\DocumentsSharingGroupe object
     *
     * @param \DocumentsSharing\Model\DocumentsSharingGroupe|ObjectCollection $documentsSharingGroupe The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDocumentsSharingGroupe($documentsSharingGroupe, $comparison = null)
    {
        if ($documentsSharingGroupe instanceof \DocumentsSharing\Model\DocumentsSharingGroupe) {
            return $this
                ->addUsingAlias(DocumentsSharingTableMap::GROUPE_ID, $documentsSharingGroupe->getId(), $comparison);
        } elseif ($documentsSharingGroupe instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentsSharingTableMap::GROUPE_ID, $documentsSharingGroupe->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDocumentsSharingGroupe() only accepts arguments of type \DocumentsSharing\Model\DocumentsSharingGroupe or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocumentsSharingGroupe relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function joinDocumentsSharingGroupe($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocumentsSharingGroupe');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'DocumentsSharingGroupe');
        }

        return $this;
    }

    /**
     * Use the DocumentsSharingGroupe relation DocumentsSharingGroupe object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DocumentsSharing\Model\DocumentsSharingGroupeQuery A secondary query class using the current class as primary query
     */
    public function useDocumentsSharingGroupeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocumentsSharingGroupe($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocumentsSharingGroupe', '\DocumentsSharing\Model\DocumentsSharingGroupeQuery');
    }

    /**
     * Filter the query by a related \DocumentsSharing\Model\DocumentsSharingI18n object
     *
     * @param \DocumentsSharing\Model\DocumentsSharingI18n|ObjectCollection $documentsSharingI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function filterByDocumentsSharingI18n($documentsSharingI18n, $comparison = null)
    {
        if ($documentsSharingI18n instanceof \DocumentsSharing\Model\DocumentsSharingI18n) {
            return $this
                ->addUsingAlias(DocumentsSharingTableMap::ID, $documentsSharingI18n->getId(), $comparison);
        } elseif ($documentsSharingI18n instanceof ObjectCollection) {
            return $this
                ->useDocumentsSharingI18nQuery()
                ->filterByPrimaryKeys($documentsSharingI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDocumentsSharingI18n() only accepts arguments of type \DocumentsSharing\Model\DocumentsSharingI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocumentsSharingI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function joinDocumentsSharingI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocumentsSharingI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'DocumentsSharingI18n');
        }

        return $this;
    }

    /**
     * Use the DocumentsSharingI18n relation DocumentsSharingI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DocumentsSharing\Model\DocumentsSharingI18nQuery A secondary query class using the current class as primary query
     */
    public function useDocumentsSharingI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinDocumentsSharingI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocumentsSharingI18n', '\DocumentsSharing\Model\DocumentsSharingI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDocumentsSharing $documentsSharing Object to remove from the list of results
     *
     * @return ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function prune($documentsSharing = null)
    {
        if ($documentsSharing) {
            $this->addUsingAlias(DocumentsSharingTableMap::ID, $documentsSharing->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the documents_sharing table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DocumentsSharingTableMap::clearInstancePool();
            DocumentsSharingTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildDocumentsSharing or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildDocumentsSharing object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DocumentsSharingTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        DocumentsSharingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DocumentsSharingTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DocumentsSharingTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DocumentsSharingTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DocumentsSharingTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DocumentsSharingTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DocumentsSharingTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DocumentsSharingTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'DocumentsSharingI18n';

        return $this
            ->joinDocumentsSharingI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildDocumentsSharingQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('DocumentsSharingI18n');
        $this->with['DocumentsSharingI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildDocumentsSharingI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocumentsSharingI18n', '\DocumentsSharing\Model\DocumentsSharingI18nQuery');
    }

} // DocumentsSharingQuery
