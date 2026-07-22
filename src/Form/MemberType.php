<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Ministry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, ['label' => 'Nome completo', 'attr' => [ 'class' => 'form-control']])
            ->add('cpf', TextType::class, ['label' => 'CPF', 'required' => false, 'attr' => [ 'class' => 'form-control']])
            ->add('rg', TextType::class, ['label' => 'RG', 'required' => false, 'attr' => [ 'class' => 'form-control']])
            ->add('birthDate', DateType::class, ['label' => 'Nascimento', 'widget' => 'single_text', 'required' => false, 'input' => 'datetime_immutable', 'attr' => [ 'class' => 'form-control']])
            ->add('gender', ChoiceType::class, ['label' => 'Sexo', 'required' => false, 'choices' => ['Masculino' => 'M', 'Feminino' => 'F'], 'attr' => [ 'class' => 'form-select']])
            ->add('maritalStatus', ChoiceType::class, ['label' => 'Estado civil', 'required' => false, 'choices' => [
                'Solteiro(a)' => 'solteiro', 'Casado(a)' => 'casado', 'Viúvo(a)' => 'viuvo',
                'Divorciado(a)' => 'divorciado', 'União estável' => 'uniao_estavel',
            ], 'attr' => [ 'class' => 'form-select']])
            ->add('phone', TextType::class, ['label' => 'Telefone / WhatsApp', 'required' => false, 'attr' => [ 'class' => 'form-control']])
            ->add('email', EmailType::class, ['label' => 'E-mail', 'required' => false, 'attr' => [ 'class' => 'form-control']])
            ->add('address', TextareaType::class, ['label' => 'Endereço', 'required' => false, 'attr' => [ 'class' => 'form-control']])
            ->add('baptismDate', DateType::class, ['label' => 'Data de batismo', 'widget' => 'single_text', 'required' => false, 'input' => 'datetime_immutable', 'attr' => [ 'class' => 'form-control']])
            ->add('membershipDate', DateType::class, ['label' => 'Membro desde', 'widget' => 'single_text', 'required' => false, 'input' => 'datetime_immutable', 'attr' => [ 'class' => 'form-control']])
            ->add('entryType', ChoiceType::class, ['label' => 'Forma de ingresso', 'required' => false, 'choices' => [
                'Batismo' => 'batismo', 'Transferência' => 'transferencia', 'Aclamação' => 'aclamacao', 'Conversão' => 'conversao',
            ], 'attr' => [ 'class' => 'form-control']])
            ->add('churchRole', ChoiceType::class, ['label' => 'Função na igreja', 'choices' => [
                'Membro' => 'membro', 'Obreiro' => 'obreiro', 'Diácono' => 'diacono',
                'Presbítero' => 'presbitero', 'Evangelista' => 'evangelista', 'Pastor' => 'pastor',
            ], 'attr' => [ 'class' => 'form-select']])
            ->add('ministry', EntityType::class, ['label' => 'Ministério', 'class' => Ministry::class, 'required' => false, 'placeholder' => '— nenhum —', 'attr' => [ 'class' => 'form-select']])
            ->add('status', ChoiceType::class, ['label' => 'Situação', 'choices' => [
                'Ativo' => 'ativo', 'Inativo' => 'inativo', 'Em disciplina' => 'disciplina', 'Transferido' => 'transferido',
            ], 'attr' => [ 'class' => 'form-select']])
            ->add('photo', FileType::class, [
                'label' => 'Foto',
                'mapped' => false,
                'required' => false, 'attr' => [ 'class' => 'form-control'],
                'constraints' => [new File(maxSize: '4M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'], mimeTypesMessage: 'Envie uma imagem JPG, PNG ou WEBP.')],
            ])
            ->add('notes', TextareaType::class, ['label' => 'Observações', 'attr' => [ 'class' => 'form-control'], 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Member::class]);
    }
}
