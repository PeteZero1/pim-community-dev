<?php
namespace Pim\Bundle\ProductBundle\AttributeType;

use Oro\Bundle\FlexibleEntityBundle\AttributeType\NumberType as OroNumberType;
use Oro\Bundle\FlexibleEntityBundle\Model\FlexibleValueInterface;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttribute;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Number attribute type
 *
 * @author    Filips Alpe <filips@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class NumberType extends OroNumberType
{
    /**
     * {@inheritdoc}
     */
    protected function defineCustomAttributeProperties(AbstractAttribute $attribute)
    {
        $properties = array(
            array(
                'name'      => 'defaultValue',
                'fieldType' => 'number'
            ),
            array(
                'name'      => 'numberMin',
                'fieldType' => 'number'
            ),
            array(
                'name'      => 'numberMax',
                'fieldType' => 'number'
            ),
            array(
                'name'      => 'decimalsAllowed',
                'fieldType' => 'checkbox'
            ),
            array(
                'name'      => 'negativeAllowed',
                'fieldType' => 'checkbox'
            ),
            array(
                'name'      => 'searchable',
                'fieldType' => 'checkbox'
            ),
            array(
                'name'      => 'translatable',
                'fieldType' => 'checkbox',
                'options'   => array(
                    'disabled'  => (bool) $attribute->getId(),
                    'read_only' => (bool) $attribute->getId()
                )
            ),
            array(
                'name'      => 'availableLocales',
                'fieldType' => 'pim_product_available_locales'
            ),
            array(
                'name'      => 'scopable',
                'fieldType' => 'pim_product_scopable',
                'options'   => array(
                    'disabled'  => (bool) $attribute->getId(),
                    'read_only' => (bool) $attribute->getId()
                )
            ),
            array(
                'name'      => 'unique',
                'fieldType' => 'checkbox',
                'options'   => array(
                    'disabled'  => (bool) $attribute->getId(),
                    'read_only' => (bool) $attribute->getId()
                )
            )
        );

        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pim_product_number';
    }
}
