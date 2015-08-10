Yii2 Micro School CRM
=====================


Шаблон приложения для школ танцев, спортивных секций, детских кружков и т.п.

Небольшая, CRM система, с возможностью управления клиентами платежами и оплаченными занятиями.

Приложение написано на php фреймворке [Yii 2](http://www.yiiframework.com/).


Демонстрация приложения: 
frontend - [http://crm.irkutsk-dance.ru](http://crm.irkutsk-dance.ru).
backend - [http://admin.crm.irkutsk-dance.ru](http://admin.crm.irkutsk-dance.ru).





## Общие возможности


* Ведение учета платежей клиентов и оплаченных занятий.

* Добавление и удаление клиентов.

* Создание групп занимающихся.

* Настройка расписания занятий для групп занимающихся.

* Настройка абонементов (стоимость, количество занятий, и "срок годности" абонемента).

* Автоматический расчет оплаченных занятий при внесении платежа для клиента.
Данные высчитываются на основе уже настроенных групп, расписания и абонементов.

* Возможность оплачивать в долг.

* Просмотр всех должников.

* Возможность посмотреть всех клиентов, которые оплатили, какое-то конкретное занятие.

* Возможность посмотреть все платежи клиента и все оплаченные занятия, для каждого платежа.

* При изменении расписания, автоматически обновляются данные оплаченных занятий клиентов.

* Возможность отменять занятия в определенный день, в одной или нескольких группах. При этом, если есть оплаченные клиентами занятия, то они автоматически перенесутся, на ближайший неоплаченный день, в той же группе, для каждого клиента.

* Есть 2-х уровневая партнерская программа.
Партнерку можно включить/отключить в админке. Настраивается комиссия первого и второго уровня, а так же, минимальная сумма платежа, при которой должны начисляться бонусные балы. Бонусными балами можно оплатить часть абонемента или полностью. У каждого пользователя в личном кабинете во вкладке "личные данные", есть партнерская ссылка (если активна партнерская программа), которой он может делиться с друзьями и т.п. Все пришедшие, зарегистрировавшиеся на сайте по партнерской ссылке учитываются и с каждого их платежа начисляется процент аффилиату. Аффилиата, так же можно задавать при создании клиента в админ панели.

* Просмотр статистики платежей (доступна только директору и суперадину).


### Возможности клиента (user)
**User** - авторизованный на сайте пользователь. Ему доступен личный кабинет.

Просмотр и изменение своих личных данных.

Просмотр истории платежей и оплаченных занятий для каждого платежа.

Просмотр оставшихся оплаченных занятий (дата, время и название группы).


### Возможности администратора (admin)
Администратору доступно все, что доступно клиенту + есть доступ в админ панель.

В админке у него есть доступ к данным клиентов, к платежам, расписанию, группам. Доступна возможность публиковать новости на сайте.


### Возможности директора (director)
Директор может делать все, что администратор + настраивать абонементы, партнерскую программу, контактные данные школы, а так же, имеет доступ к статистике платежей.


###Возможности суперадмина (superadmin)
Суперадмин может делать все вышеописанное + имеет доступ к настройке прав доступа (RBAC).




## Установка


Установка с помощью [Composer](http://getcomposer.org/).

**Выполните:**

```
composer global require "fxp/composer-asset-plugin:~1.0.0"
composer create-project --prefer-dist eugene-kei/yii2-micro-school-crm yii2-micro-school-crm
```

Первая команда установит [composer asset plugin](https://github.com/francoispluchino/composer-asset-plugin/)

Который позволяет управлять зависимостями пакетов *bower* и *npm* через *сomposer*. Это нужно сделать, только 1 раз.

Вторая команда установит приложение *yii2-micro-school-crm* в директорию *yii2-micro-school-crm*.
Вы можете заменить имя директории, если потребуется.




## Подготовка приложения

После того, как приложение установлено, нужно проделать следующие действия, для его инициализации.



1.Выполните команду `init` и выберите `dev` для разработки приложения.

   ```
   php /path/to/yii-application/init
   ```


   Или же, для продакшена, выполните `init` в неинтерактивном режиме.

   ```
   php /path/to/yii-application/init --env=Production --overwrite=All
   ```



2.Создайте базу данных и настройте соответствующие параметры `components['db']` в файле `common/config/main-local.php`.



3.Для применения миграций, выполните следующие команды в консоли:

 `yii migrate` - для применения общих миграций приложения;
 
 `yii migrate --migrationPath=@eugenekei/news/migrations` - для миграции модуля *eugene-kei/yii2-simple-news*.



4.Настройте document roots вашего веб сервера:


   - Для frontend `/path/to/yii2-micro-school-crm/frontend/web/` и использования URL `http://frontend.dev/`
   
   
   - Для backend `/path/to/yii2-micro-school-crm/backend/web/` и использования URL `http://backend.dev/`
   
   
   
   Для Apache это может выглядеть следующим образом:

       <VirtualHost *:80>
           ServerName frontend.dev
           ServerAlias 127.0.0.1
           DocumentRoot /path/to/yii2-micro-school-crm/frontend/web/
           
           <Directory "/path/to/yii2-micro-school-crm/frontend/web/">
               # use mod_rewrite for pretty URL support
               RewriteEngine on
               # If a directory or a file exists, use the request directly
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteCond %{REQUEST_FILENAME} !-d
               # Otherwise forward the request to index.php
               RewriteRule . index.php
           
               # ...other settings...
           </Directory>
       </VirtualHost>
       
       <VirtualHost *:80>
           ServerName backend.dev
           ServerAlias 127.0.0.1
           DocumentRoot /path/to/yii2-micro-school-crm/backend/web/
           
           <Directory "/path/to/yii2-micro-school-crm/backend/web/">
               # use mod_rewrite for pretty URL support
               RewriteEngine on
               # If a directory or a file exists, use the request directly
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteCond %{REQUEST_FILENAME} !-d
               # Otherwise forward the request to index.php
               RewriteRule . index.php
           
               # ...other settings...
           </Directory>
       </VirtualHost>



   Для nginx:

       server {
           charset utf-8;
           client_max_body_size 128M;
       
           listen 80; ## listen for ipv4
           #listen [::]:80 default_server ipv6only=on; ## listen for ipv6
       
           server_name frontend.dev;
           root        /path/to/yii2-micro-school-crm/frontend/web/;
           index       index.php;
       
           access_log  /path/to/yii2-micro-school-crm/log/frontend-access.log;
           error_log   /path/to/yii2-micro-school-crm/frontend-error.log;
       
           location / {
               # Redirect everything that isn't a real file to index.php;
               try_files $uri $uri/ /index.php?$args;
           }
       
           # uncomment to avoid processing of calls to non-existing static files by Yii
           #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
           #    try_files $uri =404;
           #}
           #error_page 404 /404.html;
       
           location ~ \.php$ {
               include fastcgi_params;
               fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
               fastcgi_pass   127.0.0.1:9000;
               #fastcgi_pass unix:/var/run/php5-fpm.sock;
               try_files $uri =404;
           }
       
           location ~ /\.(ht|svn|git) {
               deny all;
           }
       }
        
       server {
           charset utf-8;
           client_max_body_size 128M;
       
           listen 80; ## listen for ipv4
           #listen [::]:80 default_server ipv6only=on; ## listen for ipv6
       
           server_name backend.dev;
           root        /path/to/yii2-micro-school-crm/backend/web/;
           index       index.php;
       
           access_log  /path/to/yii2-micro-school-crm/log/backend-access.log;
           error_log   /path/to/yii2-micro-school-crm/log/backend-error.log;
       
           location / {
               # Redirect everything that isn't a real file to index.php
               try_files $uri $uri/ /index.php?$args;
           }
       
           # uncomment to avoid processing of calls to non-existing static files by Yii
           #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
           #    try_files $uri =404;
           #}
           #error_page 404 /404.html;
       
           location ~ \.php$ {
               include fastcgi_params;
               fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
               fastcgi_pass   127.0.0.1:9000;
               #fastcgi_pass unix:/var/run/php5-fpm.sock;
               try_files $uri =404;
           }
       
           location ~ /\.(ht|svn|git) {
               deny all;
           }
       }




5.Откройте файл hosts

   - Windows: `c:\Windows\System32\Drivers\etc\hosts`
   - Linux: `/etc/hosts`

   И добавьте в него следующие строки:

       
       127.0.0.1   frontend.dev
       
       127.0.0.1   backend.dev



После установки приложения, в нем уже зарегистрирован 1 пользователь с правами суперадмина. 

Логин (телефон) - **123456**

Пароль - **123456**


Для того, чтбы сменить пароль, нужно заказать сброс пароля по адресу `http://frontend.dev/site/request-password-reset/`, 
предварительно, сменив email на настоящий, в админке.





## Зависимости

Приложение **yii2-micro-school-crm** написано на базе шаблона приложения **yii2-app-advanced**.

Инструкция по установке, в основном взята оттуда.



### Список зависимостей:
```
    "php": ">=5.4.0",
    "yiisoft/yii2": ">=2.0.4",
    "yiisoft/yii2-bootstrap": "*",
    "yiisoft/yii2-swiftmailer": "*",
    "mdmsoft/yii2-admin": "1.*",
    "dmstr/yii2-adminlte-asset": "2.*",
    "eugene-kei/yii2-simple-news": "*",
    "kartik-v/yii2-grid": "*",
    "kartik-v/yii2-editable": "*",
    "kartik-v/yii2-date-range": "*",
    "kartik-v/yii2-widget-datepicker": "*",
    "kartik-v/yii2-widget-select2": "*",
    "kartik-v/yii2-widget-switchinput": "*",
    "kartik-v/yii2-widget-depdrop": "@dev",
    "kartik-v/yii2-widget-sidenav": "*",
    "vova07/yii2-fileapi-widget": "*",
    "2amigos/yii2-chartjs-widget": "~2.0",
    "nesbot/carbon": "1.*"
```

