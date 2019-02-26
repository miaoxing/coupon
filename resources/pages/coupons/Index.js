import React from "react";
import styled from "styled-components";
import app from "app";

const Link = styled.a`
  position: fixed;
  bottom: 70px;
  right: 20px;
  z-index: 888;
  background: #11a662;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  opacity: 0.7;
  color: #fff;
  font-size: 10px;
  
  & + & {
    bottom: 124px;
  }
  
  :hover {
    color: #fff;
    text-decoration: none;
  }
  
  .ni {
    font-size: 16px;
  }
`;

const ProductIcon = () => {
  return <>
    <Link className="d-flex flex-center flex-column" href={app.url('products')}>
      <i className="ni ni-gift"/>
      商城
    </Link>
    <Link className="d-flex flex-center flex-column" href={app.url('user-coupons')}>
      <i className="ni ni-coupon"/>
      我的
    </Link>
  </>
};

export default class extends React.Component {
  render() {
    return <ProductIcon/>;
  }
}
