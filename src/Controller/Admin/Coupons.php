<?php

namespace Miaoxing\Coupon\Controller\Admin;

class Coupons extends \Miaoxing\Plugin\BaseController
{
    protected $controllerName = '优惠券管理';

    protected $actionPermissions = [
        'sendUser' => '发送',
        'index' => '列表',
        'new,create' => '添加',
        'edit,update,updateEnable' => '编辑',
        'destroy' => '删除',
        'upload' => '批量发送优惠券',
    ];

    protected $displayPageHeader = true;

    public function indexAction($req)
    {
        switch ($req['_format']) {
            case 'json':
                $coupons = wei()->couponModel()
                    ->paginate()
                    ->sort();

                if ($req['start_date']) {
                    $coupons->andWhere('created_at >= ?', $req['start_date']);
                }

                if ($req['end_date']) {
                    $coupons->andWhere('created_at <= ?', $req['end_date'] . '23:59:59');
                }

                $coupons->findAll();

                return $coupons->toRet();

            default:
                $userId = $this->request('userId');

                return get_defined_vars();
        }
    }

    public function newAction($req)
    {
        return $this->editAction($req);
    }

    public function editAction($req)
    {
        $coupon = wei()->couponModel()->findId($req['id']);

        $products = $coupon->getProducts()->toArray();

        return get_defined_vars();
    }

    //保存优惠券
    public function updateAction($req)
    {
        $validator = wei()->validate([
            'data' => $req,
            'rules' => [
                'name' => [
                    'minLength' => 1,
                ],
                'money' => [
                    'minLength' => 1,
                    'digit' => 1,
                ],
                'getLimit' => [
                    'minLength' => 1,
                    'digit' => 1,
                    'required' => false,
                ],
                'quantity' => [
                    'minLength' => 1,
                    'digit' => 1,
                ],
                'pic' => [
                    'minLength' => 1,
                ],
            ],
            // 数据项名称的数组,用于错误信息提示
            'names' => [
                'name' => '名称',
                'money' => '金额',
                'getLimit' => '领取限制',
                'quantity' => '库存数量',
                'pic' => '图片',
            ],
        ]);
        if (!$validator->isValid()) {
            return $this->err($validator->getFirstMessage());
        }

        $coupon = wei()->couponModel()->findId($req['id'])->fromArray($req);
        $coupon->save();

        return $this->suc();
    }

    public function updateEnableAction($req)
    {
        if (!isset($req['enable'])) {
            return $this->err('操作失败');
        }

        $coupon = wei()->couponModel()->findId($req['id'])->fromArray($req);
        $coupon->save(['enable' => $req['enable']]);

        return $this->suc();
    }

    public function deleteAction($req)
    {
        wei()->couponModel()->findOneById($req['id'])->destroy();

        return $this->suc();
    }

    public function sendUserAction($req)
    {
        if ($req['groupId']) {
            $userList = [];
            $users = wei()->user()->where('groupId=?', $req['groupId'])->fetchAll();
            if ($users) {
                foreach ($users as $user) {
                    $userList[] = $user['id'];
                }
            }
        } else {
            $userList = explode(',', $req['userlist']);
        }
        $couponList = explode(',', $req['couponlist']);
        foreach ($userList as $key => $userId) {
            foreach ($couponList as $key1 => $couponId) {
                wei()->couponModel->sendCoupon($couponId, $userId);
            }
        }

        return $this->json('发送优惠券成功', 1);
    }

    /**
     * 批量发送优惠券
     *
     * @param $req
     * @return array
     */
    public function uploadAction($req)
    {
        set_time_limit(0);

        if (count($req['data']) > 500) {
            return $this->err([
                'errors' => [
                    [
                        'code' => -1,
                        'message' => '一次性不能上传超过500条记录',
                        'id' => '-',
                    ],
                ],
                'totalCount' => 0,
                'createCount' => 0,
                'updateCount' => 0,
            ]);
        }

        $errors = [];
        $totalCount = 0;
        $createCount = 0;
        $updateCount = 0;
        foreach ((array) $req['data'] as $key => $value) {
            $coupon = wei()->couponModel()->findOrInitById($value[3]);
            if ($coupon->isNew()) {
                $errors[] = [
                    'code' => -1,
                    'message' => '不存在该优惠券',
                    'id' => $value[0],
                ];

                ++$totalCount;

                continue;
            }

            if ($value[4] <= 0) {
                $errors[] = [
                    'code' => -1,
                    'message' => '发放数量不可为零',
                    'id' => $value[0],
                ];

                ++$totalCount;

                continue;
            }

            // 优先查询openId
            $user = wei()->user()->find(['wechatOpenId' => $value[1]]);
            if (!$value[1] || !$user) {
                // 其次查询所有个人中心手机号码
                $users = wei()->user()->findAll(['mobile' => $value[2]]);
                if ($users->length() <= 0) {
                    // 最后查询所有地址中的手机号码
                    $address = wei()->address()->select('distinct userId')->findAll(['contact' => $value[2]]);
                    if ($address->length() <= 0) {
                        $errors[] = [
                            'code' => -1,
                            'message' => '不存在该用户',
                            'id' => $value[0],
                        ];

                        ++$totalCount;

                        continue;
                    }

                    // 根据地址获取用户
                    $users = wei()->user()->findAll(['id' => array_column($address->toArray(), 'userId')]);
                }

                // 相同手机号的用户分别发放优惠券
                foreach ($users as $user) {
                    $ret = wei()->couponModel->sendCoupon($coupon['id'], $user['id'], $value[4]);
                    if ($ret['code'] !== 1) {
                        break;
                    }
                }
            } else {
                // 能用openid找到用户的直接发放优惠券
                $ret = wei()->couponModel->sendCoupon($coupon['id'], $user['id'], $value[4]);
            }

            // 记录导入错误信息
            if ($ret['code'] !== 1) {
                $errors[] = [
                    'code' => $ret['code'],
                    'message' => $ret['message'],
                    'id' => $value[0],
                ];
            } else {
                ++$createCount;
            }

            ++$totalCount;
        }

        if ($errors) {
            wei()->logger->warning('批量发放优惠券发生错误', $errors);

            return $this->err([
                'errors' => $errors,
                'totalCount' => $totalCount,
                'createCount' => $createCount,
                'updateCount' => $updateCount,
            ]);
        } else {
            return $this->suc([
                'totalCount' => $totalCount,
                'createCount' => $createCount,
                'updateCount' => $updateCount,
            ]);
        }
    }
}
