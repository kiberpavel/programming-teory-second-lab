<?php

namespace App\Http\Controllers;

use App\Services\TimeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FactorialController extends Controller
{
    public function countingWithFor(Request $request) {
        TimeService::start();
        $memory = memory_get_usage();

        $data = $request->input();
        $result = 1;
        for ($i = 1; $i <= $data['n']; $i++) {
            $result *= $i;
        }

        $checker = $this->formulaStirling($data['n']);
        if ($result > PHP_INT_MAX) {
            return response()->json(
                'Результат возведения в степень превышает максимально доступный',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $zeros = $this->zero($data['n']);

        return response()->json(
            ['Результат' => [
                'Выражение' => $result,
                'Количество конечных нулей' => $zeros,
                'Время выполнения' => TimeService::finish() . 'sec',
                'Потрачено памяти' => (memory_get_usage() - $memory) . ' байт',
                'Максимальное значение для типа integer' =>  PHP_INT_MAX,
                'Размер целого числа в байтах в текущей сборке PHP' => PHP_INT_SIZE . ' байт',

            ]],
            Response::HTTP_OK
        );
    }

    public function countingWithRecursive(Request $request) {
        TimeService::start();
        $memory = memory_get_usage();

        $data = $request->input();
        $result = 1;

        for ($i = 1; $i <= $data['n']; $i++) {
            $result = $data['n']*$this->factorial($data['n']-1);
        }

        if ($result > PHP_INT_MAX) {
            return response()->json(
                'Результат возведения в степень превышает максимально доступный',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $zeros = $this->zero($data['n']);

        return response()->json(
            ['Результат' => [
                'Выражение' => $result,
                'Количество конечных нулей' => $zeros,
                'Время выполнения' => TimeService::finish() . 'sec',
                'Потрачено памяти' => (memory_get_usage() - $memory) . ' байт',
                'Максимальное значение для типа integer' =>  PHP_INT_MAX,
                'Размер целого числа в байтах в текущей сборке PHP' => PHP_INT_SIZE . ' байт',

            ]],
            Response::HTTP_OK
        );

    }

    public function countingWithGmp(Request $request) {
        TimeService::start();
        $memory = memory_get_usage();

        $data = $request->input();
        $result = gmp_fact($data['n']);

        if ($result > PHP_INT_MAX) {
            return response()->json(
                'Результат возведения в степень превышает максимально доступный',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $zeros = $this->zero($data['n']);

        return response()->json(
            ['Результат' => [
                'Выражение' => gmp_strval($result),
                'Количество конечных нулей' => $zeros,
                'Время выполнения' => TimeService::finish() . 'sec',
                'Потрачено памяти' => (memory_get_usage() - $memory) . ' байт',
                'Максимальное значение для типа integer' =>  PHP_INT_MAX,
                'Размер целого числа в байтах в текущей сборке PHP' => PHP_INT_SIZE . ' байт',

            ]],
            Response::HTTP_OK
        );

    }

    private function factorial($n) {
        $result = 1;
         for ($i = 1; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    }

    private function formulaStirling($n) {
        $pi = 3.14;
        $epsilon = 2.718;

        $result = ceil(sqrt(2*$pi*$n)*pow(($n/$epsilon),$n));

        return $result;
    }

    private function zero($n) {
         $count = 0;

        for ($i = 5; $n / $i >= 1; $i *= 5)
            $count += floor($n / $i);

        return $count;
    }
}
