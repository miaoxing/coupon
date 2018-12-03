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
  
  :hover {
    color: #fff;
    text-decoration: none;
  }
  
  .ni {
    font-size: 16px;
  }
`;

const ProductIcon = () => {
  return <Link className="flex flex-center flex-y" href={app.url('products')}>
    <i className="ni ni-gift"/>
    商城
  </Link>
};

export default class extends React.Component {
  render() {
    return <ProductIcon/>;
  }
}
