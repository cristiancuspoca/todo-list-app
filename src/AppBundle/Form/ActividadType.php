<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// Ejemplo para eventos
    	// ->add('nombre', TextType::class), array('attr' => array('onChange' => 'onChange(evnt)'))

        $builder
            ->add('nombre', TextType::class)
            ->add('categoria', EntityType::class, [
                    'class' => 'AppBundle:Categoria',
                ])
            ->add('estado', CheckboxType::class, [
            	'label' => 'Terminada', 
            	'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Enviar']);
    }
}