<?php
// src/Form/TaskType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Yaml\Tests\A;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
                'label'=>'La citation',
                'required' => true,
            ])
            ->add('meta', TextType::class,[
                'label'=>'Les métadonées',
                'required' => true,
            ],)
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }
}