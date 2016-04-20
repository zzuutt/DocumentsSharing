<?php

namespace DocumentsSharing\Model\Base;

use \Exception;
use \PDO;
use DocumentsSharing\Model\DocumentsSharingGroupe as ChildDocumentsSharingGroupe;
use DocumentsSharing\Model\DocumentsSharingGroupeQuery as ChildDocumentsSharingGroupeQuery;
use DocumentsSharing\Model\Map\DocumentsSharingGroupeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'documents_sharing_groupe' table.
 *
 *
 *
 * @method     ChildDocumentsSharingGroupeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDocumentsSharingGroupeQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildDocumentsSharingGroupeQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 *
 * @method     ChildDocumentsSharingGroupeQuery groupById() Group by the id column
 * @method     ChildDocumentsSharingGroupeQuery groupByTitle() Group by the title column
 * @method     ChildDocumentsSharingGroupeQuery groupByCustomerId() Group by the customer_id column
 *
 * @method     ChildDocumentsSharingGroupeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDocumentsSharingGroupeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDocumentsSharingGroupeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDocumentsSharingGroupeQuery leftJoinDocumentsSharing($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocumentsSharing relation
 * @method     ChildDocumentsSharingGroupeQuery rightJoinDocumentsSharing($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocumentsSharing relation
 * @method     ChildDocumentsSharingGroupeQuery innerJoinDocumentsSharing($relationAlias = null) Adds a INNER JOIN clause to the query using the DocumentsSharing relation
 *
 * @method     ChildDocumentsSharingGroupe findOne(ConnectionInterface $con = null) Return the first ChildDocumentsSharingGroupe matching the query
 * @method     ChildDocumentsSharingGroupe findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDocumentsSharingGroupe matching the query, or a new ChildDocumentsSharingGroupe object populated from the query conditions when no match is found
 *
 * @method     ChildDocumentsSharingGroupe findOneById(int $id) Return the first ChildDocumentsSharingGroupe filtered by the id column
 * @method     ChildDocumentsSharingGroupe findOneByTitle(string $title) Return the first ChildDocumentsSharingGroupe filtered by the title column
 * @method     ChildDocumentsSharingGroupe findOneByCustomerId(string $customer_id) Return the first ChildDocumentsSharingGroupe filtered by the customer_id column
 *
 * @method     array findById(int $id) Return ChildDocumentsSharingGroupe objects filtered by the id column
 * @method     array findByTitle(string $title) Return ChildDocumentsSharingGroupe objects filtered by the title column
 * @method     array findByCustomerId(string $customer_id) Return ChildDocumentsSharingGroupe objects filtered by the customer_id column
 *
 */
abstract class DocumentsSharingGroupeQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \DocumentsSharing\Model\Base\DocumentsSharingGroupeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\DocumentsSharing\\Model\\DocumentsSharingGroupe', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDocumentsSharingGroupeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDocumentsSharingGroupeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \DocumentsSharing\Model\DocumentsSharingGroupeQuery) {
            return $criteria;
        }
        $query = new \DocumentsSharing\Model\DocumentsSharingGroupeQuery();
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
     * @return ChildDocumentsSharingGroupe|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DocumentsSharingGroupeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DocumentsSharingGroupeTableMap::DATABASE_NAME);
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
     * @return   ChildDocumentsSharingGroupe A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, TITLE, CUSTOMER_ID FROM documents_sharing_groupe WHERE ID = :p0';
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
            $obj = new ChildDocumentsSharingGroupe();
            $obj->hydrate($row);
            DocumentsSharingGroupeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildDocumentsSharingGroupe|array|mixed the result, formatted by the current formatter
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
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentsSharingGroupeTableMap::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId('fooValue');   // WHERE customer_id = 'fooValue'
     * $query->filterByCustomerId('%fooValue%'); // WHERE customer_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $customerId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($customerId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $customerId)) {
                $customerId = str_replace('*', '%', $customerId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentsSharingGroupeTableMap::CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query by a related \DocumentsSharing\Model\DocumentsSharing object
     *
     * @param \DocumentsSharing\Model\DocumentsSharing|ObjectCollection $documentsSharing  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function filterByDocumentsSharing($documentsSharing, $comparison = null)
    {
        if ($documentsSharing instanceof \DocumentsSharing\Model\DocumentsSharing) {
            return $this
                ->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $documentsSharing->getGroupeId(), $comparison);
        } elseif ($documentsSharing instanceof ObjectCollection) {
            return $this
                ->useDocumentsSharingQuery()
                ->filterByPrimaryKeys($documentsSharing->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDocumentsSharing() only accepts arguments of type \DocumentsSharing\Model\DocumentsSharing or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocumentsSharing relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function joinDocumentsSharing($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocumentsSharing');

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
            $this->addJoinObject($join, 'DocumentsSharing');
        }

        return $this;
    }

    /**
     * Use the DocumentsSharing relation DocumentsSharing object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DocumentsSharing\Model\DocumentsSharingQuery A secondary query class using the current class as primary query
     */
    public function useDocumentsSharingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocumentsSharing($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocumentsSharing', '\DocumentsSharing\Model\DocumentsSharingQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDocumentsSharingGroupe $documentsSharingGroupe Object to remove from the list of results
     *
     * @return ChildDocumentsSharingGroupeQuery The current query, for fluid interface
     */
    public function prune($documentsSharingGroupe = null)
    {
        if ($documentsSharingGroupe) {
            $this->addUsingAlias(DocumentsSharingGroupeTableMap::ID, $documentsSharingGroupe->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the documents_sharing_groupe table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingGroupeTableMap::DATABASE_NAME);
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
            DocumentsSharingGroupeTableMap::clearInstancePool();
            DocumentsSharingGroupeTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildDocumentsSharingGroupe or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildDocumentsSharingGroupe object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(DocumentsSharingGroupeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DocumentsSharingGroupeTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        DocumentsSharingGroupeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DocumentsSharingGroupeTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // DocumentsSharingGroupeQuery
