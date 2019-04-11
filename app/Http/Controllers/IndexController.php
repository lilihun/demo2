<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 测试首页
 * 创建时间：2019-03-24 10:52
 * 制作人：【老弟来了网制工作室】
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends Controller
{
    /**
     * 测试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test()
    {
        $is_customized = 'false';
        $is_chushi = 'false'; // 全部设计任务为 初始状态标识
        $is_createall = 'true'; // TODO 所有的定制的sku都已经发布设计任务 状态标识
        $pmc_count = 0;
        $product_ids = array(); // 【定制产品】的基础物料编码
        $design_status_arr = array();
        $button_batch = '审核';
        $button_edit_sku = '编辑SKU';
        $button_design = '发布设计任务';
        $button_edit_design = '编辑设计任务';
        $button_view = '查看设计稿';

        $row['order_id'] = '123'; // order_id
        // 模拟商品id
        $product_ids = [
            '0' => '111',
            '1' => '222',
        ];

        // 查询当前order_id的所有的pmc数据集合
        $filter = [
            'order_id'=> $row['order_id'],
        ];
        $res_order_pmc = DB::table("order_pmc")->where($filter)->where('design_status','<>','5')->get();
        if ($res_order_pmc->isNotEmpty()) {
            $res_order_pmc = $res_order_pmc->all();
            $res_order_pmc = array_map('get_object_vars', $res_order_pmc);
        } else {
            $res_order_pmc = array();
        }

        $is_complete = 'false'; // 是否已全部定稿标识
        $pmc_product_id = array();
        if ($res_order_pmc) {
            foreach ($res_order_pmc as $k1 => $v1) {
                $pmc_product_id[$v1['product_id']] = $v1['product_id'];
                $design_status_arr[] = $v1['design_status'];
                $pmc_count += $v1['design_status'];
            }
            if (!empty($product_ids) && ($product_ids == sort($pmc_product_id))) { // TODO 去重 然后重新排序
                // 说明 已经全部创建了设计任务
                $is_createall = 'false';
            }
        } else {
            $is_chushi = 'true'; // 初始状态
        }

        //是否为初始状态
        if ($is_chushi == 'true') {
            // 初始状态 【编辑SKU】、【发布设计任务】
            return $button_edit_sku . '&nbsp;|&nbsp;' . $button_design;
        } else {
            // 【非】初始状态
            if ($is_createall != 'true') {
                // 说明 已经全部创建了设计任务
                foreach ($design_status_arr as $k5 => $v5) {
                    if (strpos('4,6', "$v5") === false) { // todo 需要加上双引号，否则不能识别是否存在
                        $is_complete = 'false';
                        break;
                    } else {
                        // 全部已定稿
                        $is_complete = 'true';
                    }
                }
            }

            if ($is_complete == 'true') {
                // 全部完成 【审核】、【查看设计稿】
                return $button_batch . '&nbsp;|&nbsp;' . $button_view;
            } else {
                // 部分完成
                $res_button = ''; // 按钮集合
                if ($is_createall == 'true') {
                    if (in_array('4', $design_status_arr) || in_array('6', $design_status_arr)) {
                        $res_button .= $button_batch . '&nbsp;|&nbsp;';
                    }
                    if (in_array('0', $design_status_arr) || in_array('1', $design_status_arr) || in_array('2', $design_status_arr) || in_array('7', $design_status_arr)) {
                        $res_button .= $button_edit_design . '&nbsp;|&nbsp;';
                    }
                    $res_button .= $button_edit_sku . '&nbsp;|&nbsp;' . $button_design . '&nbsp;|&nbsp;' . $button_view;
                    return $res_button;

                } else {
                    if (in_array('4', $design_status_arr) || in_array('6', $design_status_arr)) {
                        $res_button .= $button_batch . '&nbsp;|&nbsp;';
                    }
                    if (in_array('0', $design_status_arr) || in_array('1', $design_status_arr) || in_array('2', $design_status_arr) || in_array('7', $design_status_arr)) {
                        $res_button .= $button_edit_design . '&nbsp;|&nbsp;';
                    }
                    $res_button .= $button_view; // todo 取消【编辑SKU】
                    return $res_button;
                }
            }
        }
    }
}
