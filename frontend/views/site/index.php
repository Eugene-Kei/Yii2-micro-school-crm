<?php
/* @var $this yii\web\View */
$this->title = 'Yii2 Micro School CRM';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Yii2 Micro School CRM</h1>
        <p>Шаблон приложения для школ танцев, спортивных секций, детских кружков и т.п.</p>
        <p>Небольшая, CRM система, с возможностью управления клиентами платежами и оплаченными занятиями.</p>
        <p>Приложение написано на PHP фреймворке Yii2.</p>
        <p>&nbsp;</p>
        <div class="row">
            <col-xs-6>
                <a class="btn btn-lg btn-success" href="https://bitbucket.org/Eugene-Kei/yii2-micro-school-crm/">Yii2 Micro School CRM на Bitbucket</a>
            </col-xs-6>
            <col-xs-6>
                <a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Официальный сайт Yii Framework</a>
            </col-xs-6>
        </div>
    </div>
    <div class="body-content">
        <div class="content">
            <h2 class="text-center" style="margin-bottom: 50px;">Возможности</h2>
            <hr/>
            <div class="row">
                <div class="col-md-6">

                    <h3 style="margin-bottom: 30px; margin-top: 40px;" class="text-center">Общие возможности</h3>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Ведение учета платежей клиентов и оплаченных занятий.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Добавление и удаление клиентов.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Создание групп занимающихся.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Настройка расписания занятий для групп занимающихся.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Настройка абонементов (стоимость, количество занятий, и "срок годности" абонемента).
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Автоматический расчет оплаченных занятий при внесении платежа для клиента.
                        Данные высчитываются на основе уже настроенных групп, расписания и абонементов.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Возможность оплачивать в долг.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Просмотр всех должников.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Возможность посмотреть всех клиентов, которые оплатили, какое-то конкретное занятие.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Возможность посмотреть все платежи клиента и все оплаченные занятия, для каждого платежа.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        При изменении расписания, автоматически обновляются данные оплаченных занятий клиентов.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Возможность отменять занятия в определенный день, в одной или нескольких группах.
                        При этом, если есть оплаченные клиентами занятия, то они автоматически перенесутся,
                        на ближайший неоплаченный день, в той же группе, для каждого клиента.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        2-х уровневая партнерская программа.
                        Партнерку можно включить/отключить в админке.
                        Настраивается комиссия первого и второго уровня, а так же, минимальная сумма платежа,
                        при которой должны начисляться бонусные балы. Бонусными балами можно оплатить часть абонемента
                        и/или
                        полностью.
                        У каждого пользователя в личном кабинете во вкладке "личные данные",
                        есть партнерская ссылка (если активна партнерская программа), которой он может делиться с
                        друзьями и
                        т.п.
                        Все пришедшие, зарегистрировавшиеся на сайте по партнерской ссылке учитываются
                        и с каждого их платежа начисляется процент аффилиату.
                        Аффилиата, так же можно задавать при создании клиента в админ панели.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Просмотр статистики платежей (доступен только директору и суперадину).
                    </p>
                </div>

                <div class="col-md-6">

                    <h3 style="margin-bottom: 30px; margin-top: 40px;" class="text-center">Возможности клиента
                        (user)</h3>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        User - авторизованный на сайте пользователь. Ему доступен личный кабинет.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Просмотр и изменение своих личных данных.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Просмотр истории платежей и оплаченных занятий для каждого платежа.
                    </p>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Просмотр оставшихся оплаченных занятий (дата, время и название группы).
                    </p>

                    <h3 style="margin-bottom: 30px; margin-top: 80px;" class="text-center">Возможности администратора
                        (admin)</h3>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Администратору доступно все, что доступно клиенту + есть доступ в админ панель.

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        В админке у него есть доступ к данным клиентов, к платежам, расписанию, и группам.
                    </p>

                    <h3 style="margin-bottom: 30px; margin-top: 80px;" class="text-center">Возможности директора
                        (director)</h3>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Директор может делать все, что администратор + настраивать абонементы,
                        партнерскую программу, контактные данные школы, а так же, имеет доступ к статистике платежей.
                    </p>

                    <h3 style="margin-bottom: 30px; margin-top: 80px;" class="text-center">Возможности суперадмина
                        (superadmin)</h3>

                    <p><i class="glyphicon glyphicon-ok text-success"></i>
                        Суперадмин может делать все вышеописанное + имеет доступ к настройке прав доступа (RBAC).
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
