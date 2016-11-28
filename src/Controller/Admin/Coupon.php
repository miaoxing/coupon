<?php

namespace Miaoxing\Coupon\Controller\admin;

class Coupon extends \miaoxing\plugin\BaseController
{
    protected $controllerName = '优惠券管理';

    protected $actionPermissions = [
        'sendUser,sendAll' => '发送',
        'index' => '列表',
        'new,create' => '添加',
        'edit,update,updateEnable' => '编辑',
        'destroy' => '删除',
    ];

    public function indexAction($req)
    {
        switch ($req['_format']) {
            case 'json':
                $page = $this->request('page');
                $rows = $this->request('rows');

                $coupons = wei()->coupon()
                    ->limit($rows)
                    ->page($page)
                    ->orderBy('id', 'desc')
                    ->notDeleted()
                    ->findAll();

                $data = $coupons->toArray();

                return $this->suc([
                    'message' => '读取列表成功',
                    'data' => $data,
                    'page' => (int) $req['page'],
                    'rows' => (int) $req['rows'],
                    'records' => $coupons->count(),
                ]);

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
        $coupon = wei()->coupon()->findOrInitById($req['id']);

        return get_defined_vars();
    }

    //保存优惠券
    public function updateAction($req)
    {
        $validator = wei()->validate([
            // 待验证的数据
            'data' => $req,
            // 验证规则数组
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
                'validDay' => [
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
                'validDay' => '有效时间',
                'getLimit' => '领取限制',
                'pic' => '图片',
            ],
        ]);
        if (!$validator->isValid()) {
            return $this->err($validator->getFirstMessage());
        }

        $coupon = wei()->coupon()->findId($req['id'])->fromArray($req);
        $coupon->save();

        return $this->suc('保存优惠券成功');
    }

    public function updateEnableAction($req)
    {
        if (!isset($req['enable'])) {
            return $this->err('操作失败');
        }

        $coupon = wei()->coupon()->findId($req['id'])->fromArray($req);
        $coupon->save(['enable' => $req['enable']]);

        return $this->suc();
    }

    public function deleteAction($req)
    {
        $coupon = wei()->coupon()->findOneById($req['id']);
        $coupon->softDelete();

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
                wei()->coupon->sendCoupon($couponId, $userId);
            }
        }

        return $this->json('发送优惠券成功', 1);
    }

    //发送优惠券
    public function sendAllAction()
    {
        $couponId = $this->request('couponId');
        $userCount = wei()->db->count('user', ['1' => 1]);
        $page = ceil($userCount / 1000);
        for ($i = 1; $i <= $page; ++$i) {
            $userList = wei()->user()->select('id')->limit(1000)->page($i)->fetchAll();
            if ($userList) {
                foreach ($userList as $key => $value) {
                    wei()->coupon->sendCoupon($couponId, $value['id']);
                }
            }
        }

        return $this->json('发送优惠券成功', 1);
    }
}
