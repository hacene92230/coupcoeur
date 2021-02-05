<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ["label" => "Saisir le nom d'utilisateur", "attr" => ["placeholder" => "Nom d'utilisateur"]])
            ->add("phone", TelType::class, ["label" => "Saisir le numéro de téléphone", "attr" => ["placeholder" => "Numéro de téléphone"]])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Tapez votre mot de passe', "attr" => ["placeholder" => "mot de passe"]],
                'second_options' => ['label' => 'Saisir la confirmation  de votre mot de passe', "attr" => ["placeholder" => "Confirmez votre mot de passe"]],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
