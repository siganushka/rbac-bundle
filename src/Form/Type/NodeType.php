<?php

namespace Siganushka\RBACBundle\Form\Type;

use Siganushka\RBACBundle\Node\NodeCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    private $nodeCollection;
    private $translationDomain;

    public function __construct(NodeCollection $nodeCollection, string $translationDomain)
    {
        $this->nodeCollection = $nodeCollection;
        $this->translationDomain = $translationDomain;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['nodes'] = $this->nodeCollection->all();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => true,
            'expanded' => true,
            'choices' => $this->nodeCollection->keys(),
            'choice_translation_domain' => $this->translationDomain,
            'choice_value' => function ($choice) {
                return $choice;
            },
            'choice_label' => function ($choice, $key, $value) {
                return $choice;
            },
            'choice_attr' => function ($choice, $key, $value) {
                $node = $this->nodeCollection->get($choice);

                return ['checked' => $node['checked'], 'disabled' => $node['disabled']];
            },
        ]);
    }

    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    }
}
