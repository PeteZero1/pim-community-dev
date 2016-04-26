<?php

namespace Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnexpectedResultException;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\Helper\ResultParser;
use Pim\Component\Catalog\Repository\GroupTypeRepositoryInterface;

/**
 * Group type repository
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class GroupTypeRepository extends EntityRepository implements GroupTypeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllGroupsExceptVariantQB()
    {
        $qb = $this->createQueryBuilder('group_type')
            ->andWhere('group_type.code != :variant')
            ->setParameter('variant', 'VARIANT')
            ->addOrderBy('group_type.code', 'ASC');

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function createDatagridQueryBuilder()
    {
        $rootAlias = 'g';
        $qb = $this->createQueryBuilder($rootAlias);

        $labelExpr = sprintf(
            "(CASE WHEN translation.label IS NULL THEN %s.code ELSE translation.label END)",
            $rootAlias
        );

        $qb
            ->addSelect($rootAlias)
            ->addSelect(sprintf("%s AS label", $labelExpr));

        $qb
            ->leftJoin($rootAlias .'.translations', 'translation', 'WITH', 'translation.locale = :localeCode');

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeByGroup($code)
    {
        try {
            return $this->createQueryBuilder('group_type')
                ->innerJoin('group_type.groups', 'g')
                ->select('group_type.variant')
                ->where('g.code = :code')
                ->setParameter('code', $code)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (UnexpectedResultException $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdentifier($code)
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierProperties()
    {
        return ['code'];
    }

    /**
     * {@inheritdoc}
     */
    public function findTypes($isVariant, $locale)
    {
        $query = $this->createQueryBuilder('g')
            ->select('g.id, g.code, t.label, t.locale')
            ->leftJoin('g.translations', 't')
            ->andWhere('g.variant = :variant')
            ->setParameter('variant', $isVariant)
            ->getQuery();

        $types = ResultParser::parseTranslations($query->getArrayResult(), $locale);

        asort($types);

        return $types;
    }

    /**
     * {@inheritdoc}
     */
    public function findTypeIds($isVariant)
    {
        $query = $this->createQueryBuilder('g')
            ->select('g.id')
            ->leftJoin('g.translations', 't')
            ->andWhere('g.variant = :variant')
            ->setParameter('variant', $isVariant)
            ->getQuery();

        $ids = ResultParser::parseIds($query->getArrayResult());

        return $ids;
    }
}
