# RBAC bundle for symfony

> 尝试性项目，请勿在生产环境中使用。

### DEMO

[symfony-rbac-demo][1]

### step 1

install bundle

```php
$ composer require siganushka/rbac-bundle:dev-master
```

### step 2

register bundle for kernel

```php
// ./src/bundles.php

<?php

return [
    // ...
    Siganushka\RBACBundle\SiganushkaRBACBundle::class => ['all' => true],
];
```

### step 3

configure the bundle

```php
// ./config/packages/framework.yaml

// ...

siganushka_rbac:
    // firewall pattern
    firewall_pattern: ^/admin
    // fully routes (include IS_AUTHENTICATED_FULLY to access)
    fully_routes:
        - admin_index
        - admin_ajax_xxx
        - admin_file_upload
    // anonymously routes (include IS_AUTHENTICATED_ANONYMOUSLY to access, e.g. login page)
    anonymously_routes:
        - admin_login
```

### Usage

Entity

```php
<?php

namespace App\Entity;

use Siganushka\RBACBundle\Model\RoleableTrait;
use Siganushka\RBACBundle\Model\RoleableInterface;

/**
 * @ORM\Entity
 */
class Role implements RoleableInterface
{
    use RoleableTrait;

    // ...
}
```

Form

```php
<?php

namespace App\Form;

use App\Entity\Role;
use Siganushka\RBACBundle\Form\Type\NodeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('nodes', NodeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
```

check permissions in controller

```php
<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController

class PostController extends AbstractController
{
    /**
     * @Route("/post/new", name="post_new")
     */
    public function new()
    {
        if ($this->isGranted('post_new')) {
            // ...
        }
    }
}

```

check permissions in twig

```php
{% if is_granted('post_new') %}
    <a href="{{ path('post_new') }}">Add New</a>
{% endif %}
```

### extras

Form fields

```php
// ./config/packages/twig.yaml

twig:
    // ...
    form_themes:
        // ...
        - rbac_fields.html.twig
```

Translations

``` php
// ./translations/siganushka_rbac.zh_CN.xlf

<?xml version="1.0" encoding="utf-8"?>
<xliff xmlns="urn:oasis:names:tc:xliff:document:1.2" version="1.2">
  <file source-language="zh-CN" target-language="zh-CN" datatype="plaintext" original="file.ext">
    <header>
      <tool tool-id="symfony" tool-name="Symfony"/>
    </header>
    <body>
        <trans-unit id="post_list">
        <source>post_list</source>
        <target>Post / View Posts</target>
      </trans-unit>
      <trans-unit id="post_new">
        <source>post_new</source>
        <target>Post / Add New</target>
      </trans-unit>
      <trans-unit id="post_edit">
        <source>post_edit</source>
        <target>Post / Edit</target>
      </trans-unit>
      // ...
    </body>
  </file>
</xliff>

```

update translation files

```php
$ php bin/console translation:update zh_CN --force
```


  [1]: https://github.com/siganushka/symfony-rbac-demo