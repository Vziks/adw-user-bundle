# ADW User Bundle #



### Installation ###

Add repository

```
"repositories": [
    {"type": "vcs", "url": "https://bitbucket.org/prodhub/adw-user-bundle.git"}        
]
```

Install vendor

```
$ composer require adw/user-bundle '^1.0'
```

Enable bundle for framework

```
app/AppKernel.php
...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...            
            new ADW\UserBundle\ADWUserBundle(),
            ...
        );
```
Install asset

```
$ php app/console asset:install
```

Routing

```
app/config/routing.yml
...
adw_user:
    resource: "@ADWUserBundle/Controller/"
    type:     annotation
    prefix:   /
```

Migration DB schema

```
$ php app/console doctrine:migration:diff
$ php app/console doctrine:migration:migrate
```

Update DB schema

```
$ php app/console doctrine:schema:update --force
```

Security Config

```
Add

security:
    encoders:        
        ADW\UserBundle\Entity\AdminUser: sha512
    providers:
        admin_user_provider:
            id: adw.entity.admin_user_repository
    firewalls:
        admin:
            provider: admin_user_provider
            pattern: ^/admin
            form_login:
                login_path: /admin/auth
                check_path: /admin/security/login/
                username_parameter: _email
                password_parameter: _password
                default_target_path: /admin
                success_handler: "adw.security.handler.admin_authentication_handler"
                failure_handler: "adw.security.handler.admin_authentication_handler"
                require_previous_session: false
            logout:
                path: /admin/logout
                target: /admin
                invalidate_session: false
            anonymous: true
            
    access_control:
        - { path: ^/admin/auth$, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: [ROLE_ADMIN, IS_AUTHENTICATED_REMEMBERED ]}            
     
        
```