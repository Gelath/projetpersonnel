<?php

namespace App\Form;

use App\Entity\Topics;
use App\Entity\Message;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TopicsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $verif = $event->getData();
                $form = $event->getForm();
     
                if (!$verif || null === $verif->getId()) {
                    $form->add('firstMessage', TextareaType::class,[
                'mapped' => false,  
                ]);

                $form->add('submit', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']]);
                
                } else{

                  $form->add('submit', SubmitType::class, ['label'=>'Modifier', 'attr'=>['class'=>'btn-primary btn-block']]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Topics::class,
        ]);
    }
}
