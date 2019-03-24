<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// Ejemplo para eventos
    	// ->add('nombre', TextType::class), array('attr' => array('onChange' => 'onChange(evnt)'))

        $builder
            ->add('nombre', TextType::class)
            ->add('guardar', SubmitType::class, ['label' => 'Enviar']);
    }
}