<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ConvertCurrency
 * @package App\Form
 */
class ConvertCurrency extends AbstractType
{
    const FROM_CURRENCY_CODE = 'from_currency_code';
    const TO_CURRENCY_CODE = 'to_currency_code';
    const AMOUNT = 'amount';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(static::FROM_CURRENCY_CODE, TextType::class)
            ->add(static::TO_CURRENCY_CODE, TextType::class)
            ->add(static::AMOUNT, NumberType::class);
    }
}
