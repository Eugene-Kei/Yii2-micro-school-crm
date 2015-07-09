<?php

namespace backend\modules\statistics\controllers;

use yii\web\Controller;
use Carbon\Carbon;
use backend\modules\pay\models\Pay;

class DefaultController extends Controller {

    public function actionIndex() {
        $endDateStat        = Carbon::create()->timezone("Asia/Irkutsk");
        $startDateYearStat  = clone $endDateStat;
        $startDateMonthStat = clone $startDateYearStat;
        $startDateYearStat->subYear()->addMonth()->firstOfMonth();
        $startDateMonthStat->subDays(29)->startOfDay();
        $endDateStat->addDay()->startOfDay();

        $payYearStat = Pay::find()
                ->select('SUM(`bonus_cash`) `sum_bonus_cash`, COUNT(*) `count`, SUM(`cash`) `sum_cash`, MONTH(`create_at`) `month`')
                ->where("`create_at` BETWEEN '{$startDateYearStat->toDateTimeString()}' and '{$endDateStat->toDateTimeString()}'")
                ->groupBy('`month`')
                ->orderBy('create_at')
                ->all();

        $payMonthStat = Pay::find()
                ->select('SUM(`bonus_cash`) `sum_bonus_cash`, COUNT(*) `count`, SUM(`cash`) `sum_cash`, DATE_FORMAT(`create_at`,"%e/%m") `date`')
                ->where("`create_at` BETWEEN '{$startDateMonthStat->toDateTimeString()}' and '{$endDateStat->toDateTimeString()}'")
                ->groupBy('`date`')
                ->orderBy('`create_at`')
                ->all();

        return $this->render('index', [
                    'payStat'      => $payYearStat,
                    'payMonthStat' => $payMonthStat,
                        ]
        );
    }

}
