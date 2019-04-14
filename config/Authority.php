<?php
/*
 *      权限管理配置文件, 所有权限单位都是对应各类操作
 *    todo  如不在权限管控内 默认不管控
 *
 * */
return [
    'cateName'=>[
        /**
         * 一级、二级分类名称
         * 这个作为新增、编辑角色权限中的一级、二级分类的名称
         *
         */
        'cxma'=>'小程序码管理', // 一级分类名称  AuthorList对应下面的 AuthorList中的键名称cxma
        'cxma_list'=>'列表',   // 二级分类名称  cxma_list对应下面的 AuthorList中的键名称cxma->cxma_list
        'cxma_haibao'=>'海报', // 二级分类名称

    ],

    /**
     * 注意：路由中如果带有参数，在下面配置时，无需带上，否则权限检测不到。
     * 如：'admin/hb_cxma/edit/{id?}'  直接写成这样既可：'admin/hb_cxma/edit'
     */
    'AuthorList'=>
        [
//            'shouye'=>  //  首页栏目
//                [
//                    'index'=>    //首页相关操作
//                        [
//                            '/admin'=>"首页列表",
//                        ],
//                ],
            'cxma'=> //  小程序码管理
                [
                    'cxma_list'=>// 列表
                        [
                            'admin/cxma/index'=>'【小程序码】列表',
                            'admin/cxma/addFirst'=>'【小程序码】编辑',
                            'admin/cxma/do_addFirst'=>'【小程序码】保存',
                            'admin/cxma/destroy'=>'【小程序码】删除',
                        ],
                    'cxma_haibao'=>//海报
                        [
                            'admin/hb_cxma/index'=>'【海报】列表',
                            'admin/hb_cxma/edit'=>'【海报】编辑',
                            'admin/hb_cxma/store'=>'【海报】保存',
                            'admin/hb_cxma/destroy'=>'【海报】删除',
                        ],
                ],


        ]

];



























