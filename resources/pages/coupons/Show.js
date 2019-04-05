import React from "react";
import {List} from "antd-mobile";
import {Link} from "react-router-dom";
import app from "app";
import InfiniteList from "components/InfiniteList";
import styled from "styled-components";
import axios from "axios";
import '../../../public/css/coupon.css';
import Ret from "components/Ret";
import map from 'lodash/map';

const Img = styled.img`
  .am-list-item & {
    height: 64px;
    width: auto;
  }
`;

const Stamp = styled.div`
  .stamp01 {
    background: ${props => props.bgColor || '#50ADD3'};
    background: radial-gradient(rgba(0, 0, 0, 0) 0, rgba(0, 0, 0, 0) 4px, ${props => props.bgColor || '#50ADD3'} 4px);
    background-size: 12px 8px;
    background-position: -5px 10px;
  }

  .stamp01:before {
    background-color: ${props => props.bgColor || '#50ADD3'};
  }

  .stamp01 .copy .submit {
    background-color: ${props => props.btnColor || '#fff'};
    color: ${props => props.btnFontColor || '#000'};
  }
`;

export default class extends React.Component {
  state = {};

  componentDidMount() {
    axios(app.curShowUrl()).then(({data}) => this.setState(data));
  }

  handleClick = () => {
    axios.post(app.url('coupons/%s/get-coupon', app.id), {}, {loading: true}).then(({data}) => {
      app.ret(data, () => {
        if (this.state.data.redirectUrl) {
          window.location = this.state.data.redirectUrl;
        } else {
          app.reload();
        }
      });
    });
  };

  render() {
    if (!this.state.code) {
      return '';
    }
    const coupon = this.state.data;

    return <Ret ret={this.state}>
      <Stamp className="coupon-container" {...coupon.styles}>
        <div className="stamp stamp01">
          <i/>
          <div className="par">
            <p className="f-16">{coupon.name}</p>
            <sub className="sign f-20">￥</sub>
            <span className="f-24">{coupon.money}</span>
            <sub>优惠券</sub>
            <p className="f-16">订单满{coupon.limitAmount || '0'}元可使用</p>
          </div>
          <div className="copy f-20">
            <p className="f-12">
              {coupon.dateType === 1 ?
                <>领取后{coupon.validDay}天有效</> :
                <>{coupon.startedUseAt.substr(0, 10)} ~ {coupon.endedUseAt.substr(0, 10)} 有效</>}
            </p>
            {this.state.receiveRet.code === 1 ?
              <a className="js-get-coupon submit f-14" onClick={this.handleClick}>点击领取</a> :
              <span className="non-submit f-14">{this.state.receiveRet.shortMessage}</span>}
          </div>
        </div>

        <div className="coupon-remark p-0 mb-0 text-primary">
          备注: {coupon.remark || '-'}
        </div>
      </Stamp>

      <InfiniteList
        url={app.actionUrl('%s/products', app.id)}
        render={({data}) => {
          return <List
            renderHeader={() => '可用商品列表'}
          >
            {map(data, row => {
              return <List.Item key={row.id}>
                <a href={app.url('products/%s', row.id)} className="d-flex w-100">
                  <Img className="mr-3" src={row.images[0]}/>
                  <div className="d-flex flex-column justify-content-between">
                    <div className="text-body">{row.name}</div>
                    <div className="text-primary">¥{row.price}</div>
                  </div>
                </a>
              </List.Item>
            })}
          </List>
        }}
      />
    </Ret>
  }
}
